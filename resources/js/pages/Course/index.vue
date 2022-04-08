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
    <div class="course">
      <div class="course__header">
        <b-card>
          <b-row>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="form-item">
                <label for="filter-name">{{ $t('COURSE.LABEL_FILTER_NAME') }}</label>
                <b-form-input
                  id="filter-name"
                  v-model="isFilter.name"
                  :placeholder="$t('COURSE.PLACEHOLDER_FILTER_NAME')"
                />
              </div>
            </b-col>
          </b-row>
        </b-card>
      </div>

      <div class="course__content">
        <b-card>
          <b-row>
            <b-col>
              <div class="d-flex justify-content-end course__content__add">
                <b-button class="btn-custom-green" @click="onClickAdd()">
                  <i class="fas fa-plus-circle" />
                  <span>{{ $t('COURSE.BUTTON_ADD_NEW') }}</span>
                </b-button>
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-table
                id="table-course"
                bordered
                striped
                responsive
                no-sort-reset
                no-local-sorting
                show-empty
                :fields="fields"
                :items="items"
                :total-rows="pagination.total"
                :per-page="pagination.perPage"
                aria-controls="table-course"
              >
                <template #cell(actions)="data">
                  <div class="td-actions">
                    <div class="actions-edit">
                      <b-button variant="warning" size="sm" @click="onClickUpdate(data.item.id)">
                        <i class="fas fa-pencil-alt" />
                      </b-button>
                    </div>
                    <div class="actions-delete">
                      <b-button variant="danger" size="sm" @click="onClickDelete(data.item.id)">
                        <i class="fas fa-trash" />
                      </b-button>
                    </div>
                  </div>
                </template>
                <template #empty>
                  <span class="d-flex justify-content-center">
                    {{ $t('COURSE.TABLE_CONTENT_NO_DATA') }}
                  </span>
                </template>
              </b-table>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <b-pagination
                v-model="pagination.page"
                pills
                size="sm"
                first-number
                last-number
                align="right"
                :total-rows="pagination.total"
                :per-page="pagination.perPage"
                aria-controls="table-course"
              />
            </b-col>
          </b-row>
        </b-card>
      </div>

      <b-modal
        v-model="visibleModalForm"
        size="lg"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-course-content"
        footer-class="modal-course-footer"
      >
        <template #modal-header>
          <h5 v-if="isAction === 'ADD'">{{ $t('COURSE.MODAL_TITLE_ADD') }}</h5>
          <h5 v-if="isAction === 'UPDATE'">{{ $t('COURSE.MODAL_TITLE_UPDATE') }}</h5>
        </template>

        <template #default>
          <div class="item-input">
            <label for="form-name">{{ $t('COURSE.LABEL_FORM_NAME') }}</label>
            <b-form-input id="form-name" v-model="isCourse.name" :placeholder="$t('COURSE.PLACEHOLDER_FORM_NAME')" />
          </div>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" @click="onClickCancelModalForm()">
            {{ $t('COURSE.BUTTON_CANCEL') }}
          </b-button>
          <b-button class="btn-custom-green" @click="onClickSumbitModalForm()">
            {{ $t('COURSE.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalDelete"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-delete-content"
        footer-class="modal-delete-footer"
      >
        <template #modal-header>
          <h5>{{ $t('COURSE.MODAL_TITLE_DELETE') }}</h5>
        </template>

        <template #default>
          <p>{{ $t('COURSE.CONTENT_MODAL_DELETE') }}</p>
        </template>

        <template #modal-footer>
          <b-button class="btn-custom-outline-charade" @click="onClickCancelModalDelete()">
            {{ $t('COURSE.BUTTON_CANCEL') }}
          </b-button>

          <b-button variant="danger" @click="onClickSubmitModalDelete()">
            {{ $t('COURSE.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>
    </div>
  </b-overlay>
</template>

<script>
const ACITON_ADD = 'ADD';
const ACTION_UPDATE = 'UPDATE';

const URL_API = {
  getAll: '/courses',
  getOne: '/courses',
  postCourse: '/courses',
  putCourse: '/courses',
  deleteCourse: '/courses',
};

import {
  getAllCourse,
  getOneCourse,
  postCourse,
  putCourse,
  deleteCourse,
} from '@/api/modules/course';

import {
  validateAddCourse,
  validateUpdateCourse,
} from './validate';

import NotifyCourse from '@/toast/modules/course';

export default {
  name: 'Course',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      isCourse: {
        name: '',
        level: 1,
      },

      isFilter: {
        name: '',
      },

      items: [],

      pagination: {
        page: 1,
        perPage: 10,
        total: 1,
      },

      visibleModalForm: false,
      visibleModalDelete: false,
      isAction: '',
      idHandle: null,
      isProcess: false,
    };
  },
  computed: {
    fields() {
      return [
        {
          key: 'name',
          label: this.$t('COURSE.TABLE_TITLE_NAME'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'actions',
          lebael: this.$t('COURSE.TABLE_TITLE_ACTIONS'),
          thClass: 'base-th base-actions',
          tdClass: 'base-td base-actions',
        },
      ];
    },
    currentPageCourse() {
      return this.pagination.page;
    },
  },
  watch: {
    currentPageCourse() {
      this.initData();
    },
  },
  created() {
    this.initData();
  },
  methods: {
    async initData() {
      this.overlay.show = true;
      await this.handleGetAllCourse();
      this.overlay.show = false;
    },
    async handleGetAllCourse() {
      const URL = URL_API['getAll'];

      const PARAMS = {
        page: this.pagination.page,
        per_page: this.pagination.perPage,
      };

      try {
        const res = await getAllCourse(URL, PARAMS);

        if (res['status'] === 200) {
          this.items = res['data']['data'];
          this.pagination.page = res['data']['current_page'];
          this.pagination.total = res['data']['total'];
        } else {
          NotifyCourse.server(res['message']);
        }
      } catch {
        NotifyCourse.exception();
      }
    },
    async handleGetOneCourse(id) {
      const URL = `${URL_API['getOne']}/${id}`;

      try {
        const res = await getOneCourse(URL);

        if (res['status'] === 200) {
          this.isCourse.name = res['data']['name'];
        } else {
          NotifyCourse.server(res['message']);
        }
      } catch {
        NotifyCourse.exception();
      }
    },
    async handleAddCourse() {
      this.isProcess = true;
      const URL = URL_API.postCourse;

      const DATA = {
        name: this.isCourse.name,
        level: this.isCourse.level,
      };

      const validate = validateAddCourse(DATA);

      if (validate.status) {
        try {
          const res = await postCourse(URL, DATA);

          if (res['status'] === 200) {
            this.isProcess = false;
            this.hideModalForm();
            NotifyCourse.addSuccess(res.data.name);
            this.initData();
          } else {
            this.isProcess = true;
            NotifyCourse.server(res['message']);
          }
        } catch (error) {
          NotifyCourse.addError(error);
          this.isProcess = false;
        }
      } else {
        NotifyCourse.validateForm(`NOTIFY.COURSE.${validate.message.shift()}`);
        this.isProcess = false;
      }
    },
    async handleUpdateCourse(id) {
      this.isProcess = true;
      const URL = `${URL_API.putCourse}/${id}`;

      const DATA = {
        name: this.isCourse.name,
      };

      const validate = validateUpdateCourse(DATA);

      if (validate.status) {
        try {
          const res = await putCourse(URL, DATA);

          if (res['status'] === 200) {
            this.isProcess = false;
            this.hideModalForm();
            NotifyCourse.updateSuccess(res.data.name);
            this.initData();
          } else {
            NotifyCourse.server(res['message']);
          }
        } catch (error) {
          NotifyCourse.updateError(error);
          this.isProcess = false;
        }
      } else {
        NotifyCourse.validateForm(`NOTIFY.COURSE.${validate.message.shift()}`);
        this.isProcess = false;
      }
    },
    async handleDeleteCourse(id) {
      const URL = `${URL_API.deleteCourse}/${id}`;

      try {
        const res = await deleteCourse(URL);

        if (res['status'] === 200) {
          this.hidenModalDelete();
          NotifyCourse.deleteSuccess();
          this.initData();
        } else {
          NotifyCourse.server(res['message']);
        }
      } catch {
        NotifyCourse.exception();
      }
    },
    resetModalForm() {
      const DEFAULT = {
        name: '',
        level: 1,
      };

      this.isCourse = DEFAULT;
    },
    showModalForm() {
      this.visibleModalForm = true;
    },
    hideModalForm() {
      this.resetModalForm();
      this.visibleModalForm = false;
    },
    showModalDelete() {
      this.visibleModalDelete = true;
    },
    hidenModalDelete() {
      this.visibleModalDelete = false;
    },
    onClickAdd() {
      this.isAction = ACITON_ADD;
      this.showModalForm();
    },
    async onClickUpdate(id) {
      await this.handleGetOneCourse(id);
      this.isAction = ACTION_UPDATE;
      this.idHandle = id;
      this.showModalForm();
    },
    onClickDelete(id) {
      this.idHandle = id;
      this.showModalDelete();
    },
    onClickCancelModalForm() {
      this.hideModalForm();
    },
    async onClickSumbitModalForm() {
      if (this.isAction === ACITON_ADD) {
        await this.handleAddCourse();
      }

      if (this.isAction === ACTION_UPDATE) {
        await this.handleUpdateCourse(this.idHandle);
      }
    },
    onClickCancelModalDelete() {
      this.hidenModalDelete();
    },
    async onClickSubmitModalDelete() {
      await this.handleDeleteCourse(this.idHandle);
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@/scss/_variables";

.course {
    padding: 10px;
    height: calc(100vh - 57px);

    &__header {
        margin-bottom: 10px;

        .form-item {
            margin-top: 5px;
            margin-bottom: 5px;
        }
    }

    &__content {
        margin-bottom: 10px;

        &__add {
            margin-bottom: 10px;

            button {
                min-width: 150px;

                i {
                    margin-right: 10px;
                }
            }
        }

        ::v-deep table#table-course {
            thead {
                tr {
                    th.base-th {
                        min-width: 130px;
                        background-color: $charade;
                        color: $white;
                        text-align: center;
                    }
                }
            }

            tbody {
                tr {
                    td.base-td {
                        text-align: center;
                    }

                    td.base-td.base-actions {
                        width: 200px;
                    }

                    td {
                        .td-actions {
                            display: flex;

                            .actions-edit {
                                width: 50%;

                                i {
                                    color: $white;
                                }
                            }

                            .actions-delete {
                                width: 50%;

                                i {
                                    color: $white;
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

.modal-course-content {
    .item-input {
        margin-bottom: 10px;
    }
}

.icon-loading {
    font-size: 50px;
    color: $forest-green;
}
</style>
