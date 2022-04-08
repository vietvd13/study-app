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
    <div class="classes">
      <div class="classes__header">
        <b-card>
          <b-row>
            <b-col cols="12" sm="12" md="6" lg="4" xl="4">
              <div class="form-item">
                <label for="filter-name">{{ $t('CLASSES.LABEL_FILTER_NAME') }}</label>
                <b-form-input
                  id="filter-name"
                  v-model="isFilter.name"
                  :placeholder="$t('CLASSES.PLACEHOLDER_FILTER_NAME')"
                />
              </div>
            </b-col>
          </b-row>
        </b-card>
      </div>

      <div class="classes__content">
        <b-card>
          <b-row>
            <b-col>
              <div class="d-flex justify-content-end classes__content__add">
                <b-button class="btn-custom-green" @click="onClickAdd()">
                  <i class="fas fa-plus-circle" />
                  <span>{{ $t('CLASSES.BUTTON_ADD_NEW') }}</span>
                </b-button>
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-table
                id="table-classes"
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
                aria-controls="table-classes"
              >
                <template #cell(arrangement)="data">
                  <b-row>
                    <b-col cols="6" sm="6" md="6" lg="6" xl="6">
                      <b-button class="btn-custom-green" size="sm" @click="onClickAssignStudent(data.item)">
                        <i class="fas fa-user-alt" />
                      </b-button>
                    </b-col>

                    <b-col cols="6" sm="6" md="6" lg="6" xl="6">
                      <b-button class="btn-custom-green" size="sm" @click="onClickAssignCourse(data.item)">
                        <i class="fas fa-books" />
                      </b-button>
                    </b-col>
                  </b-row>
                </template>

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
                    {{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}
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
                aria-controls="table-classes"
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
        body-class="modal-classes-content"
        footer-class="modal-classes-footer"
      >
        <template #modal-header>
          <h5 v-if="isAction === 'ADD'">{{ $t('CLASSES.MODAL_TITLE_ADD') }}</h5>
          <h5 v-if="isAction === 'UPDATE'">{{ $t('CLASSES.MODAL_TITLE_UPDATE') }}</h5>
        </template>

        <template #default>
          <div class="item-input">
            <label for="form-name">{{ $t('CLASSES.LABEL_FORM_NAME') }}</label>
            <b-form-input id="form-name" v-model="isClass.name" :disabled="isProcess" :placeholder="$t('CLASSES.PLACEHOLDER_FORM_NAME')" />
          </div>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="onClickCancelModalForm()">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>
          <b-button class="btn-custom-green" :disabled="isProcess" @click="onClickSumbitModalForm()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
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
          <h5>{{ $t('CLASSES.MODAL_TITLE_DELETE') }}</h5>
        </template>

        <template #default>
          <p>{{ $t('CLASSES.CONTENT_MODAL_DELETE') }}</p>
        </template>

        <template #modal-footer>
          <b-button class="btn-custom-outline-charade" @click="onClickCancelModalDelete()">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>

          <b-button variant="danger" @click="onClickSubmitModalDelete()">
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalAssignStudent"
        size="xl"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-assign-students-content"
        footer-class="modal-assign-students-footer"
      >
        <template #modal-header>
          <h5>{{ $t('CLASSES.MODAL_TITLE_ASSIGN_STUDENT') }}</h5>
        </template>

        <template #default>
          <b-row>
            <b-col cols="12" sm="12" md="12" lg="12" xl="6">

              <div class="title-list">
                <b-card>
                  <b-card-text>
                    <h5>{{ $t('CLASSES.TITLE_LIST_STUDENT_IN_CLASS', { name: isClassHandle.name }) }}</h5>
                  </b-card-text>
                </b-card>
              </div>

              <div class="display-total-student">
                <b-card>
                  <b-card-text>
                    <span>{{ $t('CLASSES.TITLE_NUMBER_STUDENT', { total: listStudentSelected.length }) }}</span>
                  </b-card-text>
                </b-card>
              </div>

              <div class="list-student">

                <template v-if="listStudentSelected.length === 0">
                  <div class="display-student">
                    <b-card>
                      <b-card-text>
                        <span>{{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}</span>
                      </b-card-text>
                    </b-card>
                  </div>
                </template>

                <template v-if="listStudentSelected.length > 0">
                  <div v-for="(student, index) in listStudentSelected" :key="student.id">
                    <div class="display-student">
                      <b-card>
                        <template #header>
                          <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                              <span><b>{{ student.user_code }}</b></span>
                            </div>
                            <b-button variant="danger" size="sm" @click="deleteStudentInClass(student, index)">
                              <i class="fas fa-trash" />
                            </b-button>
                          </div>
                        </template>

                        <b-card-text>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_FULLNAME') }}:</b>{{ student.name }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}:</b>{{ student.phone }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_EMAIL') }}:</b>{{ student.email }}</span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_BLIND') }}:</b>
                              <b-badge v-if="student.status === 1" variant="danger">
                                {{ $t('CLASSES.TEXT_YES') }}
                              </b-badge>
                              <b-badge v-if="student.status === 0" variant="success">
                                {{ $t('CLASSES.TEXT_NO') }}
                              </b-badge>
                            </span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_STATUS') }}:</b>
                              <b-badge v-if="student.status === 1" variant="success">
                                {{ $t('CLASSES.TEXT_ACTIVE') }}
                              </b-badge>
                              <b-badge v-if="student.status === 0" variant="danger">
                                {{ $t('CLASSES.TEXT_INACTIVE') }}
                              </b-badge>
                            </span>
                          </div>
                        </b-card-text>
                      </b-card>
                    </div>
                  </div>
                </template>
              </div>
            </b-col>

            <b-col cols="12" sm="12" md="12" lg="12" xl="6">

              <div class="title-list">
                <b-card>
                  <b-card-text>
                    <h5>{{ $t('CLASSES.TITLE_LIST_STUDENT_IN_SYSTEM') }}</h5>
                  </b-card-text>
                </b-card>
              </div>

              <div class="item-input-search">
                <b-form-input
                  id="search-user-code"
                  v-model="searchUserCode"
                  :disabled="isProcess"
                  :placeholder="$t('CLASSES.PLACEHOLDER_SEARCH_USER_CODE')"
                  @keyup.enter="handleGetListStudent()"
                />
              </div>

              <div class="list-student">

                <template v-if="listStudentSystem.length === 0">
                  <div class="display-student">
                    <b-card>
                      <b-card-text>
                        <span>{{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}</span>
                      </b-card-text>
                    </b-card>
                  </div>
                </template>

                <template v-if="listStudentSystem.length > 0">
                  <div v-for="(student, index) in listStudentSystem" :key="student.id">
                    <div class="display-student">
                      <b-card>
                        <template #header>
                          <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                              <span><b>{{ student.user_code }}</b></span>
                            </div>
                            <template v-if="!listStudentIdSelected.includes(student.id)">
                              <b-button class="btn-custom-green" size="sm" @click="addStudentToClass(student, index)">
                                <i class="fas fa-plus-circle" />
                              </b-button>
                            </template>
                          </div>
                        </template>

                        <b-card-text>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_FULLNAME') }}:</b>{{ student.name }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}:</b>{{ student.phone }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_EMAIL') }}:</b>{{ student.email }}</span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_BLIND') }}:</b>
                              <b-badge v-if="student.status === 1" variant="danger">
                                {{ $t('CLASSES.TEXT_YES') }}
                              </b-badge>
                              <b-badge v-if="student.status === 0" variant="success">
                                {{ $t('CLASSES.TEXT_NO') }}
                              </b-badge>
                            </span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_STATUS') }}:</b>
                              <b-badge v-if="student.status === 1" variant="success">
                                {{ $t('CLASSES.TEXT_ACTIVE') }}
                              </b-badge>
                              <b-badge v-if="student.status === 0" variant="danger">
                                {{ $t('CLASSES.TEXT_INACTIVE') }}
                              </b-badge>
                            </span>
                          </div>
                        </b-card-text>
                      </b-card>
                    </div>
                  </div>
                </template>
              </div>

              <div class="pagination-student">
                <b-pagination
                  v-model="paginationStudent.page"
                  pills
                  size="sm"
                  first-number
                  last-number
                  align="right"
                  :total-rows="paginationStudent.total"
                  :per-page="paginationStudent.perPage"
                />
              </div>
            </b-col>
          </b-row>

        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="visibleModalAssignStudent = false">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>

          <b-button class="btn-custom-green" @click="onSubmitAssignStudent()">
            <i v-if="isProcess" :disabled="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalAssignCourse"
        size="xl"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-assign-course-content"
        footer-class="modal-assign-course-footer"
      >
        <template #modal-header>
          <h5>{{ $t('CLASSES.MODAL_TITLE_ASSIGN_COURSE') }}</h5>
        </template>

        <template #default>
          <b-row>
            <b-col cols="12" sm="12" md="12" lg="12" xl="6">
              <div class="title-list">
                <b-card>
                  <b-card-text>
                    <h5>{{ $t('CLASSES.TITLE_LIST_COURSE_IN_CLASS', { name: isClassHandle.name }) }}</h5>
                  </b-card-text>
                </b-card>
              </div>

              <div class="list-course">
                <template v-if="listCourseSelected.length === 0">
                  <div class="display-course">
                    <b-card>
                      <b-card-text>
                        <span>{{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}</span>
                      </b-card-text>
                    </b-card>
                  </div>
                </template>

                <template v-if="listCourseSelected.length > 0">
                  <div v-for="(course, index) in listCourseSelected" :key="course.id">
                    <div class="display-course">
                      <b-card>
                        <template #header>
                          <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                              <span><b>{{ course.name }}</b></span>
                            </div>
                            <b-button variant="danger" size="sm" @click="deleteCourseInClass(course, index)">
                              <i class="fas fa-trash" />
                            </b-button>
                          </div>
                        </template>
                        <template #default>
                          <div class="select-start-end-date">
                            <div class="item-input">
                              <label>{{ $t('CLASSES.LABLE_START') }}</label>
                              <b-form-datepicker
                                v-model="listCourseSelected[index]['start_date']"
                                class="date_picker"
                                :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                                :max="listCourseSelected[index]['end_date']"
                                :locale="locale"
                              >
                                <template #button-content>
                                  <i class="fad fa-calendar-day icon-date" />
                                </template>
                              </b-form-datepicker>
                            </div>

                            <div class="item-input">
                              <label>{{ $t('CLASSES.LABLE_END') }}</label>
                              <b-form-datepicker
                                v-model="listCourseSelected[index]['end_date']"
                                class="date_picker"
                                :date-format-options="{ year: 'numeric', month: '2-digit', day: '2-digit' }"
                                :min="listCourseSelected[index]['start_date']"
                                :locale="locale"
                              >
                                <template #button-content>
                                  <i class="fad fa-calendar-day icon-date" />
                                </template>
                              </b-form-datepicker>
                            </div>
                          </div>
                        </template>
                      </b-card>
                    </div>
                  </div>
                </template>
              </div>
            </b-col>

            <b-col cols="12" sm="12" md="12" lg="12" xl="6">
              <div class="title-list">
                <b-card>
                  <b-card-text>
                    <h5>{{ $t('CLASSES.TITLE_LIST_COURSE_IN_SYSTEM') }}</h5>
                  </b-card-text>
                </b-card>
              </div>

              <div class="list-course">
                <template v-if="listCourseSystem.length === 0">
                  <div class="display-course">
                    <b-card>
                      <b-card-text>
                        <span>{{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}</span>
                      </b-card-text>
                    </b-card>
                  </div>
                </template>

                <template v-if="listCourseSystem.length > 0">
                  <div v-for="(course, index) in listCourseSystem" :key="course.id">
                    <div class="display-course">
                      <b-card no-body>
                        <template #header>
                          <div class="d-flex justify-content-between item">
                            <div class="align-self-center">
                              <span><b>{{ course.name }}</b></span>
                            </div>
                            <template v-if="!listCourseIdSelected.includes(course.id)">
                              <b-button class="btn-custom-green" size="sm" @click="addCourseToClass(course, index)">
                                <i class="fas fa-plus-circle" />
                              </b-button>
                            </template>
                          </div>
                        </template>
                      </b-card>
                    </div>
                  </div>
                </template>
              </div>

              <div class="pagination-course">
                <b-pagination
                  v-model="paginationCourse.page"
                  pills
                  size="sm"
                  first-number
                  last-number
                  align="right"
                  :total-rows="paginationCourse.total"
                  :per-page="paginationCourse.perPage"
                />
              </div>
            </b-col>
          </b-row>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="visibleModalAssignCourse = false">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>
          <b-button class="btn-custom-green" :disabled="isProcess" @click="onSubmitAssignCourse()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
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
  getAll: '/classes',
  getOne: '/classes',
  postClasses: '/classes',
  putClasses: '/classes',
  deleteClasses: '/classes',
  getStudent: '/user/students',
  assignStudent: '/classes/students',
  getAllCourse: '/courses',
  assignCourse: '/classes/courses',
};
import {
  getAllClasses,
  getOneClasses,
  postClasses,
  putClasses,
  deleteClasses,
  getStudent,
  assignStudent,
  assignCourse,
} from '@/api/modules/classes';
import {
  getAllCourse,
} from '@/api/modules/course';

import {
  validateAddClasses,
  validateUpdateClasses,
  validateAssingCourse,
} from './validate';

import NotifyClasses from '@/toast/modules/classes';

export default {
  name: 'Classes',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      isClass: {
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
        total: 0,
      },
      paginationStudent: {
        page: 1,
        perPage: 10,
        total: 0,
      },
      paginationCourse: {
        page: 1,
        perPage: 10,
        total: 0,
      },

      searchUserCode: '',
      listStudentSelected: [],
      listStudentSystem: [],
      listStudentIdSelected: [],

      listCourseSelected: [],
      listCourseSystem: [],
      listCourseIdSelected: [],

      visibleModalForm: false,
      visibleModalDelete: false,
      visibleModalAssignStudent: false,
      visibleModalAssignCourse: false,
      isAction: '',
      idHandle: null,
      isProcess: false,
      isClassHandle: {
        id: null,
        name: '',
        level: 1,
      },
    };
  },
  computed: {
    fields() {
      return [
        {
          key: 'name',
          label: this.$t('CLASSES.TABLE_TITLE_NAME'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'arrangement',
          label: this.$t('CLASSES.TABLE_TITLE_ARRANGEMENT'),
          thClass: 'base-th',
          tdClass: 'base-td base-arrangement',
        },
        {
          key: 'actions',
          lebael: this.$t('CLASSES.TABLE_TITLE_ACTIONS'),
          thClass: 'base-th',
          tdClass: 'base-td base-actions',
        },
      ];
    },
    locale() {
      const language = this.$store.getters.language;

      if (language === 'en') {
        return 'en';
      }
      if (language === 'vn') {
        return 'vi';
      }

      return 'en';
    },
  },
  created() {
    this.initData();
  },
  methods: {
    async initData() {
      this.overlay.show = true;
      await this.handleGetAllClasses();
      this.overlay.show = false;
    },
    async handleGetAllClasses() {
      const URL = URL_API['getAll'];

      const PARAMS = {
        page: this.pagination.page,
        per_page: this.pagination.perPage,
      };

      try {
        const res = await getAllClasses(URL, PARAMS);

        if (res['status'] === 200) {
          this.items = res['data']['data'];
          this.pagination.page = res['data']['current_page'];
          this.pagination.total = res['data']['total'];
        } else {
          NotifyClasses.server(res['message']);
        }
      } catch {
        NotifyClasses.exception();
      }
    },
    async handleGetOneClasses(id) {
      const URL = `${URL_API['getOne']}/${id}`;

      try {
        const res = await getOneClasses(URL);

        if (res['status'] === 200) {
          this.isClass.name = res['data']['name'];
          this.listStudentSelected = res['data']['students'];
          this.listStudentIdSelected = this.getListKey('id', res['data']['students']);

          this.listCourseSelected = res['data']['courses'];
          this.listCourseIdSelected = this.getListKey('id', res['data']['courses']);
        } else {
          NotifyClasses.server(res['message']);
        }
      } catch {
        NotifyClasses.exception();
      }
    },
    async handleAddClasses() {
      this.isProcess = true;
      const URL = URL_API.postClasses;

      const DATA = {
        name: this.isClass.name,
        level: this.isClass.level,
      };

      const validate = validateAddClasses(DATA);

      if (validate.status) {
        try {
          const res = await postClasses(URL, DATA);

          if (res['status'] === 200) {
            this.isProcess = false;
            this.hideModalForm();
            NotifyClasses.addSuccess(res.data.name);
            this.initData();
          } else {
            this.isProcess = true;
            NotifyClasses.server(res['message']);
          }
        } catch (error) {
          NotifyClasses.addError(error);
          this.isProcess = false;
        }
      } else {
        NotifyClasses.validateForm(`NOTIFY.CLASSES.${validate.message.shift()}`);
        this.isProcess = false;
      }
    },
    async handleUpdateClasses(id) {
      this.isProcess = true;
      const URL = `${URL_API.putClasses}/${id}`;

      const DATA = {
        name: this.isClass.name,
      };

      const validate = validateUpdateClasses(DATA);

      if (validate.status) {
        try {
          const res = await putClasses(URL, DATA);

          if (res['status'] === 200) {
            this.isProcess = false;
            this.hideModalForm();
            NotifyClasses.updateSuccess(res.data.name);
            this.initData();
          } else {
            NotifyClasses.server(res['message']);
          }
        } catch (error) {
          NotifyClasses.updateError(error);
          this.isProcess = false;
        }
      } else {
        NotifyClasses.validateForm(`NOTIFY.CLASSES.${validate.message.shift()}`);
        this.isProcess = false;
      }
    },
    async handleDeleteClasses(id) {
      const URL = `${URL_API.deleteClasses}/${id}`;

      try {
        const res = await deleteClasses(URL);

        if (res['status'] === 200) {
          this.hidenModalDelete();
          NotifyClasses.deleteSuccess();
          this.initData();
        } else {
          NotifyClasses.server(res['message']);
        }
      } catch {
        NotifyClasses.exception();
      }
    },
    async handleGetListStudent() {
      const URL = URL_API.getStudent;
      const PARAMS = {
        user_code: this.searchUserCode,
        page: this.paginationStudent['page'],
        per_page: this.paginationStudent['perPage'],
      };

      try {
        const res = await getStudent(URL, PARAMS);

        this.listStudentSystem = res['data'];
        this.paginationStudent['page'] = res['current_page'];
        this.paginationStudent['total'] = res['total'];
      } catch (error) {
        NotifyClasses.server(error);
      }
    },
    async handleGetListCourse() {
      const URL = URL_API.getAllCourse;
      const PARAMS = {
        page: this.paginationCourse['page'],
        per_page: this.paginationCourse['perPage'],
      };

      try {
        const res = await getAllCourse(URL, PARAMS);

        this.listCourseSystem = res['data']['data'];
        this.paginationCourse['page'] = res['data']['current_page'];
        this.paginationCourse['total'] = res['data']['total'];
      } catch (error) {
        NotifyClasses.server(error);
      }
    },
    resetModalForm() {
      const DEFAULT = {
        name: '',
        level: 1,
      };

      this.isClass = DEFAULT;
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
      await this.handleGetOneClasses(id);
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
        await this.handleAddClasses();
      }

      if (this.isAction === ACTION_UPDATE) {
        await this.handleUpdateClasses(this.idHandle);
      }
    },
    onClickCancelModalDelete() {
      this.hidenModalDelete();
    },
    async onClickSubmitModalDelete() {
      await this.handleDeleteClasses(this.idHandle);
    },
    async onClickAssignStudent(isClass) {
      this.isClassHandle.id = isClass.id;
      this.isClassHandle.name = isClass.name;
      this.isClassHandle.level = 1;

      await this.handleGetListStudent();
      await this.handleGetOneClasses(isClass.id);
      this.visibleModalAssignStudent = true;
    },
    async onClickAssignCourse(isClass) {
      this.isClassHandle.id = isClass.id;
      this.isClassHandle.name = isClass.name;
      this.isClassHandle.level = 1;

      await this.handleGetListCourse();
      await this.handleGetOneClasses(isClass.id);
      this.visibleModalAssignCourse = true;
    },
    addStudentToClass(student, index) {
      this.listStudentSelected.push(student);
      this.listStudentIdSelected.push(student.id);
    },
    deleteStudentInClass(student, index) {
      this.listStudentSelected.splice(index, 1);
      this.listStudentIdSelected = this.removeItemInArr(this.listStudentIdSelected, student.id);
    },
    addCourseToClass(course, index) {
      this.listCourseSelected.push(course);
      this.listCourseIdSelected.push(course.id);
    },
    deleteCourseInClass(course, index) {
      this.listCourseSelected.splice(index, 1);
      this.listCourseIdSelected = this.removeItemInArr(this.listCourseIdSelected, course.id);
    },
    removeItemInArr(arr, value) {
      const index = arr.indexOf(value);
      if (index > -1) {
        arr.splice(index, 1);
      }

      return arr;
    },
    hideModalAssignStudent() {
      this.visibleModalAssignStudent = false;
      this.listStudentSelected = [];
      this.listStudentIdSelected = [];
    },
    createDataAssignStudent(id_class, list_student_selected) {
      let idx = 0;
      const len = list_student_selected.length;
      const student_list = [];

      while (idx < len) {
        student_list.push(
          {
            student_id: list_student_selected[idx]['id'],
          }
        );

        idx++;
      }

      return {
        class_id: id_class,
        data: student_list,
      };
    },
    getListKey(key, arr) {
      let idx = 0;
      const len = arr.length;
      const result = [];

      while (idx < len) {
        result.push(arr[idx][key]);

        idx++;
      }

      return result;
    },
    async onSubmitAssignStudent() {
      this.isProcess = true;
      const URL = URL_API.assignStudent;
      const DATA = this.createDataAssignStudent(this.isClassHandle.id, this.listStudentSelected);

      try {
        const res = await assignStudent(URL, DATA);

        if (res['status'] === 200) {
          this.hideModalAssignStudent();
          NotifyClasses.assignStudentSuccess();
        } else {
          NotifyClasses.server(res['message']);
        }

        this.isProcess = false;
      } catch (error) {
        NotifyClasses.updateError(error);
        this.isProcess = false;
        this.hideModalAssignStudent();
      }
    },
    createDataAssignCourse(idClass, listCourse) {
      const DATA = {
        class_id: idClass,
        data: [],
      };

      let idx = 0;
      const len = listCourse.length;

      while (idx < len) {
        DATA['data'].push({
          course_id: listCourse[idx]['id'],
          start_date: listCourse[idx]['start_date'],
          end_date: listCourse[idx]['end_date'],
        });

        idx++;
      }

      return DATA;
    },
    async onSubmitAssignCourse() {
      this.isProcess = true;

      const URL = URL_API.assignCourse;
      const DATA = this.createDataAssignCourse(this.isClassHandle.id, this.listCourseSelected);

      if (validateAssingCourse(DATA['data'])) {
        const res = await assignCourse(URL, DATA);

        if (res['status'] === 200) {
          this.visibleModalAssignCourse = false;
          NotifyClasses.assignCourseSuccess();
        } else {
          NotifyClasses.server(res['message']);
        }
      } else {
        NotifyClasses.validateForm(`NOTIFY.CLASSES.VALIDATE_LIST_ASSIGN_COURSE`);
      }

      this.isProcess = false;
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@/scss/_variables";

.classes {
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

        ::v-deep table#table-classes {
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

                    td.base-td.base-arrangement {
                        width: 200px;
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

.modal-classes-content {
    .item-input {
        margin-bottom: 10px;
    }
}

.modal-assign-students-content {
    .item-input-search {
        margin-bottom: 10px;
        padding: 5px;
    }

    .title-list {
        padding: 5px;

        .card {
            background-color: $charade;

            .card-body {
                padding: 0.5rem 0.75rem;

                h5 {
                    margin-bottom: 0;
                    color: $white;
                    font-size: 1rem;
                }
            }
        }

        margin-bottom: 5px;
    }

    .display-total-student {
        padding: 5px;

        .card {
            .card-header {
                padding: 0.5rem 0.75rem;
            }

            .card-body {
                padding: 0.5rem 0.75rem;
            }
        }

        margin-bottom: 5px;
    }

    .list-student {
        width: 100%;
        height: 500px;
        overflow: auto;
        margin-bottom: 10px;
        min-height: 46px;

        .display-student {
            width: 100%;
            overflow: hidden;
            padding: 5px;

            .card-header {
                padding: 0.5rem 0.75rem;
            }

            .card-body {
                padding: 0.5rem 0.75rem;
            }

            div {
                span {
                    word-wrap: break-word;

                    b {
                        margin-right: 5px;
                    }
                }
            }
        }
    }
}

.modal-assign-course-content {
    .title-list {
        padding: 5px;

        .card {
            background-color: $charade;

            .card-body {
                padding: 0.5rem 0.75rem;

                h5 {
                    margin-bottom: 0;
                    color: $white;
                    font-size: 1rem;
                }
            }
        }

        margin-bottom: 5px;
    }

    .list-course {
        width: 100%;
        height: 500px;
        overflow: auto;
        margin-bottom: 10px;
        min-height: 46px;

        .display-course {
            width: 100%;
            // overflow: hidden;
            padding: 5px;

            .card-header {
                padding: 0.5rem 0.75rem;
            }

            .card-body {
                padding: 0.5rem 0.75rem;
            }

            div {
                span {
                    word-wrap: break-word;

                    b {
                        margin-right: 5px;
                    }
                }
            }
        }
    }
}

.icon-loading {
    font-size: 50px;
    color: $forest-green;
}

.item-input {
    margin-bottom: 10px;
}
.icon-date {
    color: $forest-green;
    font-size: 1.25rem;
}
</style>
