<template>
  <b-overlay
    :show="overlay.show"
    :variant="overlay.variant"
    :opacity="overlay.opacity"
    :blur="overlay.blur"
    :rounded="overlay.sm"
  >
    <template #overlay>
      <div class="text-center">
        <i class="fad fa-spinner-third fa-spin icon-loading" />
        <p style="margin-top: 10px;">{{ $t('LOADING_TEXT') }}</p>
      </div>
    </template>
    <div class="test-management">
      <div class="test-management__header">
        <div class="d-flex justify-content-end test-management__header__add">
          <b-button class="btn-custom-green" @click="onClickAdd()">
            <i class="fas fa-plus-circle" />
            <span>{{ $t('TEST.BUTTON_ADD_NEW') }}</span>
          </b-button>
        </div>
      </div>

      <div class="test-management__content" />

      <b-modal
        v-model="visibleModalForm"
        size="xl"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-form-content"
        footer-class="modal-form-footer"
      >
        <template #modal-header>
          <h5>{{ $t('TEST.TITLE_MODAL_ADD') }}</h5>
        </template>

        <template #default>
          <div class="item-input">
            <label for="form-name">{{ $t('TEST.LABLE_FORM_NAME') }}</label>
            <b-form-input
              id="form-name"
              v-model="isTest['test_name']"
              :placeholder="$t('TEST.PLACEHOLDER_FORM_NAME')"
              :disabled="isProcess"
            />
          </div>

          <div class="item-input">
            <label for="form-class">{{ $t('TEST.LABLE_FORM_CLASS') }}</label>
            <b-form-select id="form-class" v-model="isTest.class_id" :disabled="isProcess">
              <b-form-select-option :value="null" :disabled="true">
                {{ $t('TEST.PLACEHOLDER_FORM_CLASS') }}
              </b-form-select-option>
              <b-form-select-option
                v-for="itemClass in listClass"
                :key="itemClass.id"
                :value="itemClass.id"
              >
                {{ itemClass.name }}
              </b-form-select-option>
            </b-form-select>
          </div>

          <div class="item-input">
            <label for="form-course">{{ $t('TEST.LABEL_FORM_COURSE') }}</label>
            <b-form-select id="form-course" v-model="isTest.course_id" :disabled="isProcess">
              <b-form-select-option :value="null" :disabled="true">
                {{ $t('TEST.PLACEHOLDER_FORM_COURSE') }}
              </b-form-select-option>
              <b-form-select-option
                v-for="itemCourse in listCourse"
                :key="itemCourse.id"
                :value="itemCourse.id"
              >
                {{ itemCourse.name }}
              </b-form-select-option>
            </b-form-select>
          </div>

          <div class="item-input">
            <label for="form-limit-time">{{ $t('TEST.LABEL_FORM_LIMIT_TIME') }}</label>
            <b-form-input
              id="form-name"
              v-model="isTest['limit_time']"
              type="number"
              :placeholder="$t('TEST.PLACEHOLDER_FORM_LIMIT_TIME')"
              :disabled="isProcess"
            />
          </div>

          <div class="item-input">
            <b-form-checkbox id="form-blind" v-model="isTest.blind_support" name="blind" :disabled="isProcess">
              {{ $t('TEST.LABEL_FORM_BLIND_SUPPORT') }}
            </b-form-checkbox>
          </div>

          <div class="item-input">
            <label>{{ $t('TEST.LABEL_FORM_FILE') }}</label>
            <input id="input-file-test" type="file" name="input-file-test" @change="chooseFile">
            <div>
              <b-button
                class="btn-custom-green"
                @click="clickChooseFile()"
              >
                <i class="fas fa-cloud-upload-alt" style="margin-right: 10px;" />
                {{ $t('TEST.PLACEHOLDER_FORM_FILE') }}
              </b-button>
            </div>
          </div>

          <div v-if="isTest.file" class="item-input">
            <i class="fas fa-file" />
            <span style="margin-left: 5px;">{{ isTest.file.name }}</span>
          </div>

        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="onClickCancelModalForm()">
            {{ $t('TEST.BUTTON_CANCEL') }}
          </b-button>
          <b-button class="btn-custom-green" :disabled="isProcess" @click="onClickSumbitModalForm()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('TEST.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>

const URL_API = {
  getAll: '/classes',
  getAllClassTeacher: '/class/teacher/list',
  getAllCourse: '/courses',
  postCreateTest: '/test/import',
};

import {
  getAllClasses,
  getAllClassTeacher,
  getAllCourse,
  postCreateTest,
} from '@/api/modules/test';

import { isAvailable } from '@/utils/isAvailable';
import { hasRole, getCurrentRole } from '@/utils/hasRole';
import CONST_ROLE from '@/const/role';

export default {
  name: 'Test',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      isProcess: false,

      visibleModalForm: false,

      isTest: {
        test_id: '',
        test_name: '',
        class_id: null,
        course_id: null,
        blind_support: false,
        limit_time: '',
        file: null,
      },

      listClass: [],
      listCourse: [],
    };
  },
  created() {
    this.initData();
  },
  methods: {
    async initData() {
      await this.handleGetAllClasses();
      await this.handleGetAllCourse();
    },
    async handleGetAllClasses() {
      const PARAMS = {
        page: 1,
        per_page: 10000,
      };

      let res;

      try {
        if (getCurrentRole() === CONST_ROLE.LIST_ROLE.ADMIN) {
          const URL = URL_API['getAll'];
          res = await getAllClasses(URL, PARAMS);

          if (res) {
            this.listClass = res['data']['data'];
          } else {
            console.log('Can not get data classes');
          }
        }

        if (getCurrentRole() === CONST_ROLE.LIST_ROLE.TEACHER) {
          const URL = URL_API['getAllClassTeacher'];
          res = await getAllClassTeacher(URL, PARAMS);
          if (res) {
            this.listClass = res['data'];
          } else {
            console.log('Can not get data classes');
          }
        }
      } catch (error) {
        console.log(error);
      }
    },
    async handleGetAllCourse() {
      const URL = URL_API['getAllCourse'];

      const PARAMS = {
        page: 1,
        per_page: 10000,
      };

      try {
        const res = await getAllCourse(URL, PARAMS);

        if (res['status'] === 200) {
          this.listCourse = res['data']['data'];
        } else {
          console.log('Can not get data class');
        }
      } catch (error) {
        console.log(error);
      }
    },
    onClickAdd() {
      this.visibleModalForm = true;
    },
    onClickCancelModalForm() {
      this.visibleModalForm = false;
    },
    isResetModalForm() {
      this.isTest = {
        test_id: '',
        test_name: '',
        class_id: '',
        course_id: '',
        blind_support: false,
        limit_time: '',
      };

      if (isAvailable(document.getElementById('input-file-test'), 'value')) {
        document.getElementById('input-file-test').value = null;
      }
    },
    clickChooseFile() {
      const FILE = document.getElementById('input-file-test');
      FILE.click();
    },
    chooseFile(event) {
      this.isTest.file = event.target.files[0];
    },
    async onClickSumbitModalForm() {
      this.isProcess = true;

      try {
        const URL = URL_API['postCreateTest'];

        const DATA = new FormData();
        DATA.append('name', this.isTest.test_name ? this.isTest.test_name : '');
        DATA.append('class_id', this.isTest.class_id ? this.isTest.class_id : null);
        DATA.append('course_id', this.isTest.course_id ? this.isTest.course_id : null);
        DATA.append('limit_time', this.isTest.limit ? this.isTest.limit : 0);
        DATA.append('blind_support', this.isTest.blind_support ? 1 : 0);
        DATA.append('file', this.isTest.file ? this.isTest.file : null);

        const res = await postCreateTest(URL, DATA);

        console.log(res);

        this.visibleModalForm = false;
        this.isResetModalForm();
        this.isProcess = false;
      } catch (error) {
        this.isProcess = false;
        console.log(error);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
    @import '@/scss/_variables';

    .test-management {
		padding: 10px;
        height: calc(100vh - 57px);

        &__header {
            &__add {
				margin-bottom: 10px;

				button {
					min-width: 150px;

					i {
						margin-right: 10px;
					}
				}
            }
        }
    }

    .modal-form-content {
    	.item-input {
            #input-file-test {
                display: none;
            }

            &:not(:last-child) {
			    margin-bottom: 10px;
            }
		}
    }

    .icon-loading {
        font-size: 50px;
        color: $forest-green;
    }
</style>
