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
                <template #cell(docs)="data">
                  <b-button class="btn-custom-green" size="sm" @click="onClickDocs(data.item)">
                    <i class="fas fa-file" />
                  </b-button>
                </template>

                <template #cell(arrangement)="data">
                  <b-button class="btn-custom-green" size="sm" @click="onClickAssignTeacher(data.item)">
                    <i class="fas fa-chalkboard-teacher" />
                  </b-button>
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

      <b-modal
        v-model="visibleModalAssignTeacher"
        size="xl"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-assign-teacher-content"
        footer-class="modal-assign-teacher-footer"
      >
        <template #modal-header>
          <h5>{{ $t('COURSE.MODAL_TITLE_ASSIGN_TEACHER') }}</h5>
        </template>

        <template #default>
          <b-row>
            <b-col cols="12" sm="12" md="12" lg="12" xl="6">

              <div class="title-list">
                <b-card>
                  <b-card-text>
                    <h5>{{ $t('CLASSES.TITLE_LIST_TEACHER_IN_COURSE', { name: isCourse.name }) }}</h5>
                  </b-card-text>
                </b-card>
              </div>

              <div class="display-total-teacher">
                <b-card>
                  <b-card-text>
                    <span>{{ $t('CLASSES.TITLE_NUMBER_TEACHER', { total: listTeacherSelected.length }) }}</span>
                  </b-card-text>
                </b-card>
              </div>

              <div class="list-teacher">

                <template v-if="listTeacherSelected.length === 0">
                  <div class="display-teacher">
                    <b-card>
                      <b-card-text>
                        <span>{{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}</span>
                      </b-card-text>
                    </b-card>
                  </div>
                </template>

                <template v-if="listTeacherSelected.length > 0">
                  <div v-for="(teacher, index) in listTeacherSelected" :key="teacher.id">
                    <div class="display-teacher">
                      <b-card>
                        <template #header>
                          <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                              <span><b>{{ teacher.user_code }}</b></span>
                            </div>
                            <b-button variant="danger" size="sm" @click="deleteTeacherInCourse(teacher, index)">
                              <i class="fas fa-trash" />
                            </b-button>
                          </div>
                        </template>

                        <b-card-text>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_FULLNAME') }}:</b>{{ teacher.name }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}:</b>{{ teacher.phone }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_EMAIL') }}:</b>{{ teacher.email }}</span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_BLIND') }}:</b>
                              <b-badge v-if="teacher.status === 1" variant="danger">
                                {{ $t('CLASSES.TEXT_YES') }}
                              </b-badge>
                              <b-badge v-if="teacher.status === 0" variant="success">
                                {{ $t('CLASSES.TEXT_NO') }}
                              </b-badge>
                            </span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_STATUS') }}:</b>
                              <b-badge v-if="teacher.status === 1" variant="success">
                                {{ $t('CLASSES.TEXT_ACTIVE') }}
                              </b-badge>
                              <b-badge v-if="teacher.status === 0" variant="danger">
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
                    <h5>{{ $t('CLASSES.TITLE_LIST_TEACHER_IN_SYSTEM') }}</h5>
                  </b-card-text>
                </b-card>
              </div>

              <div class="list-teacher">

                <template v-if="listTeacherSystem.length === 0">
                  <div class="display-teacher">
                    <b-card>
                      <b-card-text>
                        <span>{{ $t('CLASSES.TABLE_CONTENT_NO_DATA') }}</span>
                      </b-card-text>
                    </b-card>
                  </div>
                </template>

                <template v-if="listTeacherSystem.length > 0">
                  <div v-for="(teacher, index) in listTeacherSystem" :key="teacher.id">
                    <div class="display-teacher">
                      <b-card>
                        <template #header>
                          <div class="d-flex justify-content-between">
                            <div class="align-self-center">
                              <span><b>{{ teacher.user_code }}</b></span>
                            </div>
                            <template v-if="!listTeacherIdSelected.includes(teacher.id)">
                              <b-button class="btn-custom-green" size="sm" @click="addTeacherToCourse(teacher, index)">
                                <i class="fas fa-plus-circle" />
                              </b-button>
                            </template>
                          </div>
                        </template>

                        <b-card-text>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_FULLNAME') }}:</b>{{ teacher.name }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}:</b>{{ teacher.phone }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_EMAIL') }}:</b>{{ teacher.email }}</span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_BLIND') }}:</b>
                              <b-badge v-if="teacher.status === 1" variant="danger">
                                {{ $t('CLASSES.TEXT_YES') }}
                              </b-badge>
                              <b-badge v-if="teacher.status === 0" variant="success">
                                {{ $t('CLASSES.TEXT_NO') }}
                              </b-badge>
                            </span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_STATUS') }}:</b>
                              <b-badge v-if="teacher.status === 1" variant="success">
                                {{ $t('CLASSES.TEXT_ACTIVE') }}
                              </b-badge>
                              <b-badge v-if="teacher.status === 0" variant="danger">
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

              <div class="pagination-teacher">
                <b-pagination
                  v-model="paginationTeacher.page"
                  pills
                  size="sm"
                  first-number
                  last-number
                  align="right"
                  :total-rows="paginationTeacher.total"
                  :per-page="paginationTeacher.perPage"
                />
              </div>
            </b-col>
          </b-row>
        </template>

        <template #modal-footer>
          <b-button class="btn-custom-outline-charade" @click="onClickCancelAssignTeacher()">
            {{ $t('COURSE.BUTTON_CANCEL') }}
          </b-button>

          <b-button variant="danger" @click="onClickSubmitAssignTeacher()">
            {{ $t('COURSE.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalDocs"
        size="xl"
        scrollable
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        body-class="modal-docs-content"
        footer-class="modal-docs-footer"
      >
        <template #modal-header>
          <h5>{{ $t('COURSE.MODAL_TITLE_DOCS') }}</h5>
        </template>

        <template #default>
          <b-row>
            <b-col>
              <div class="item-input">
                <label>{{ $t('COURSE.LABEL_DOCS_NAME') }}</label>
                <b-form-input v-model="isDocs.name" :placeholder="$t('COURSE.PLACEHOLDER_DOCS_NAME')" />
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <div class="item-input">
                <label>{{ $t('COURSE.LABEL_FORM_DESCRIPTION') }}</label>
                <b-form-textarea
                  v-model="isDocs.description"
                  :placeholder="$t('COURSE.PLACEHOLDER_DOCS_DESCRIPTION')"
                  rows="5"
                  max-rows="10"
                />
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <div class="item-input">
                <label>{{ $t('COURSE.LABEL_FORM_FILE') }}</label>
                <input id="input-docs" type="file" name="input-docs" @change="chooseFile">
                <div>
                  <b-button
                    class="btn-custom-green"
                    @click="clickChooseFile()"
                  >
                    <i class="fas fa-cloud-upload-alt" style="margin-right: 10px;" />
                    {{ $t('COURSE.PLACEHOLDER_DOCS_FILE') }}
                  </b-button>
                </div>
              </div>
            </b-col>
          </b-row>
          <b-row v-if="isDocs.file">
            <b-col>
              <div class="item-input">
                <i class="fas fa-file" />
                <span style="margin-left: 5px;">{{ isDocs.file.name }}</span>
              </div>
            </b-col>
          </b-row>
          <b-row>
            <b-col>
              <b-table
                id="table-docs"
                bordered
                striped
                responsive
                no-sort-reset
                no-local-sorting
                show-empty
                :fields="fieldsDocs"
                :items="documents"
              >
                <template #cell(file)="data">
                  <div class="d-flex justify-content-center">
                    <b-row>
                      <b-col>
                        <b-button class="btn-custom-green" size="sm" @click="downloadDocs(data.item)">
                          <i class="fas fa-cloud-download" />
                        </b-button>
                      </b-col>
                      <b-col>
                        <b-button variant="danger" size="sm" @click="onClickRemoveDocs(data.item)">
                          <i class="fas fa-trash" />
                        </b-button>
                      </b-col>
                    </b-row>
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
        </template>

        <template #modal-footer>
          <b-button class="btn-custom-outline-charade" @click="onClickCancelDocs()">
            {{ $t('COURSE.BUTTON_CANCEL') }}
          </b-button>

          <b-button variant="danger" @click="onClickSubmitDocs()">
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
  getTeacher: '/user/teacher',
  assignTeacher: '/course/add-teacher',
  uploadDocs: '/course/add-document',
  deleteDocs: '/course/delete-document',
};

import {
  getAllCourse,
  getOneCourse,
  postCourse,
  putCourse,
  deleteCourse,
  getTeacher,
  assignTeacher,
  uploadDocs,
  deleteDocs,
} from '@/api/modules/course';

import {
  validateAddCourse,
  validateUpdateCourse,
  validateUploadDocs,
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
      documents: [],

      pagination: {
        page: 1,
        perPage: 10,
        total: 1,
      },
      paginationTeacher: {
        page: 1,
        perPage: 10,
        total: 1,
      },

      listTeacherSelected: [],
      listTeacherSystem: [],
      listTeacherIdSelected: [],

      visibleModalForm: false,
      visibleModalDelete: false,
      visibleModalAssignTeacher: false,
      visibleModalDocs: false,
      isAction: '',
      idHandle: null,
      isProcess: false,

      isDocs: {
        course_id: null,
        name: '',
        description: '',
        file: null,
      },
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
          key: 'docs',
          label: this.$t('COURSE.TABLE_TITLE_DOCS'),
          thClass: 'base-th base-docs',
          tdClass: 'base-td base-docs',
        },
        {
          key: 'arrangement',
          label: this.$t('COURSE.TABLE_TITLE_ARRANGEMENT'),
          thClass: 'base-th base-arrangement',
          tdClass: 'base-td base-arrangement',
        },
        {
          key: 'actions',
          lebael: this.$t('COURSE.TABLE_TITLE_ACTIONS'),
          thClass: 'base-th base-actions',
          tdClass: 'base-td base-actions',
        },
      ];
    },
    fieldsDocs() {
      return [
        {
          key: 'name',
          label: this.$t('COURSE.LABEL_DOCS_NAME'),
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'description',
          label: this.$t('COURSE.LABEL_FORM_DESCRIPTION'),
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'file',
          label: this.$t('COURSE.LABEL_FORM_FILE'),
          thClass: 'base-th base-docs',
          tdClass: 'base-td base-docs',
        },
      ];
    },
    currentPageCourse() {
      return this.pagination.page;
    },
    currentPageTeacher() {
      return this.paginationStudent.page;
    },
  },
  watch: {
    currentPageCourse() {
      this.initData();
    },
    currentPageStudent() {
      this.overlay.show = true;

      this.handleGetListTeacher();

      this.overlay.show = false;
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

          this.listTeacherSelected = res['data']['teachers'];
          this.documents = res['data']['documents'];
          this.listTeacherIdSelected = this.getListKey('id', res['data']['teachers']);
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
    createDataAssignTeacher(id_course, list_teacher_selected) {
      let idx = 0;
      const len = list_teacher_selected.length;
      const teacher_list = [];

      while (idx < len) {
        teacher_list.push(
          {
            teacher_id: list_teacher_selected[idx]['id'],
          }
        );

        idx++;
      }

      return {
        course_id: id_course,
        data: teacher_list,
      };
    },
    async handleGetListTeacher() {
      const URL = URL_API.getTeacher;
      const PARAMS = {
        page: this.paginationTeacher['page'],
        per_page: this.paginationTeacher['perPage'],
      };

      try {
        const res = await getTeacher(URL, PARAMS);

        this.listTeacherSystem = res['data'];
        this.paginationTeacher['page'] = res['current_page'];
        this.paginationTeacher['total'] = res['total'];
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
    showModalAssignTeacher() {
      this.visibleModalAssignTeacher = true;
    },
    hideModalAssignTeacher() {
      this.visibleModalAssignTeacher = false;
    },
    onClickCancelAssignTeacher() {
      this.hideModalAssignTeacher();
    },
    async onClickSubmitAssignTeacher() {
      this.isProcess = true;
      const URL = URL_API.assignTeacher;
      const DATA = this.createDataAssignTeacher(this.idHandle, this.listTeacherSelected);

      try {
        const res = await assignTeacher(URL, DATA);

        if (res['status'] === 200) {
          this.hideModalAssignTeacher();
          NotifyCourse.assignTeacherSuccess();
        } else {
          NotifyCourse.server(res['message']);
        }

        this.isProcess = false;
      } catch (error) {
        NotifyCourse.updateError(error);
        this.isProcess = false;
        this.hideModalAssignTeacher();
      }
    },
    async onClickAssignTeacher(item) {
      this.idHandle = item.id;

      await this.handleGetOneCourse(item.id);
      await this.handleGetListTeacher();
      this.showModalAssignTeacher();
    },
    addTeacherToCourse(teacher, index) {
      this.listTeacherSelected.push(teacher);
      this.listTeacherIdSelected.push(teacher.id);
    },
    deleteTeacherInCourse(teacher, index) {
      this.listTeacherSelected.splice(index, 1);
      this.listTeacherIdSelected = this.removeItemInArr(this.listTeacherIdSelected, teacher.id);
    },
    removeItemInArr(arr, value) {
      const index = arr.indexOf(value);
      if (index > -1) {
        arr.splice(index, 1);
      }

      return arr;
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
    async onClickDocs(item) {
      this.isDocs.course_id = item.id;

      await this.handleGetOneCourse(this.isDocs.course_id);

      this.visibleModalDocs = true;
    },
    onClickCancelDocs() {
      this.visibleModalDocs = false;
      this.isDocs = {
        course_id: null,
        name: '',
        description: '',
        file: null,
      };
      document.getElementById('input-docs').value = null;
    },
    clickChooseFile() {
      const FILE = document.getElementById('input-docs');
      FILE.click();
    },
    chooseFile(event) {
      this.isDocs.file = event.target.files[0];
    },
    async handleUploadDocs() {
      try {
        const DATA = new FormData();
        DATA.append('course_id', this.isDocs.course_id);
        DATA.append('name', this.isDocs.name);
        DATA.append('description', this.isDocs.description);
        DATA.append('files[0]', this.isDocs.file);

        const URL = URL_API.uploadDocs;

        const res = await uploadDocs(URL, DATA);

        if (res) {
          NotifyCourse.uploadDocsSuccess();
        }

        this.isDocs = {
          course_id: null,
          name: '',
          description: '',
          file: null,
        };
        document.getElementById('input-docs').value = null;
        this.visibleModalDocs = false;
      } catch (error) {
        NotifyCourse.updateError(error);
      }
    },
    async onClickSubmitDocs() {
      const DATA = {
        name: this.isDocs.name,
        description: this.isDocs.description,
        file: this.isDocs.file,
      };

      if (validateUploadDocs(DATA)) {
        await this.handleUploadDocs();
      } else {
        NotifyCourse.validateDocs();
      }
    },
    downloadDocs(item) {
      window.open(`/storage/${item['path']}`);
    },
    async handleDeleteDocs(item) {
      try {
        const URL = `${URL_API.deleteDocs}?course_id=${item['course_id']}&document_id=${item['id']}`;

        const res = await deleteDocs(URL);

        if (res) {
          await this.handleGetOneCourse(item.course_id);

          NotifyCourse.deleteDocsSuccess();
        }
      } catch (error) {
        NotifyCourse.updateError(error);
      }
    },
    onClickRemoveDocs(item) {
      this.handleDeleteDocs(item);
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

                    td.base-td.base-arrangement {
                        width: 200px;
                    }

                    td.base-td.base-actions {
                        width: 200px;
                    }

                    td.base-td.base-docs {
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

.modal-assign-teacher-content {
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

    .display-total-teacher {
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

    .list-teacher {
        width: 100%;
        height: 500px;
        overflow: auto;
        margin-bottom: 10px;
        min-height: 46px;

        .display-teacher {
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

.modal-docs-content {
    .item-input {
        margin-bottom: 15px;

        #input-docs {
            display: none;
        }
    }
}

::v-deep table#table-docs {
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

    td.base-td {
        text-align: center;
    }

    td.base-docs {
        width: 50px;
    }
}

.icon-loading {
    font-size: 50px;
    color: $forest-green;
}
</style>
