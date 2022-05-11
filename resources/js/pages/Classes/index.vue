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
          <b-row v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])">
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
                aria-controls="table-classes"
              >
                <template #cell(activity)="data">
                  <b-col>
                    <b-row>
                      <b-col>
                        <b-button class="btn-custom-green" size="sm" @click="onClickActivity(data.item)">
                          <i class="fas fa-file-alt" />
                        </b-button>
                      </b-col>
                    </b-row>
                  </b-col>
                </template>

                <template #cell(arrangement)="data">
                  <b-col>
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
                  </b-col>
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
                :disabled="isProcess"
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
          <b-button class="btn-custom-outline-charade" :disabled="isProcess" @click="onClickCancelModalDelete()">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>

          <b-button variant="danger" :disabled="isProcess" @click="onClickSubmitModalDelete()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
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
            <b-col cols="12" sm="12" md="12" lg="12" :xl="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN]) ? 6 : 12">

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
                            <b-button v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])" variant="danger" size="sm" :disabled="isProcess" @click="deleteStudentInClass(student, index)">
                              <i class="fas fa-trash" />
                            </b-button>
                          </div>
                        </template>

                        <b-card-text>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_FULLNAME') }}: </b>{{ student.name }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}: </b>{{ student.phone }}</span>
                          </div>
                          <div>
                            <span><b>{{ $t('ACCOUNT.LABEL_FORM_EMAIL') }}: </b>{{ student.email }}</span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_BLIND') }}: </b>
                              <b-badge v-if="student.isBlind === 1" variant="danger">
                                {{ $t('CLASSES.TEXT_YES') }}
                              </b-badge>
                              <b-badge v-if="student.isBlind === 0" variant="success">
                                {{ $t('CLASSES.TEXT_NO') }}
                              </b-badge>
                            </span>
                          </div>
                          <div>
                            <span>
                              <b>{{ $t('ACCOUNT.LABEL_FORM_STATUS') }}: </b>
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

            <b-col v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])" cols="12" sm="12" md="12" lg="12" xl="6">

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
                              <b-button class="btn-custom-green" size="sm" :disabled="isProcess" @click="addStudentToClass(student, index)">
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
                  :disabled="isProcess"
                />
              </div>
            </b-col>
          </b-row>

        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="visibleModalAssignStudent = false">
            {{ hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN]) ? $t('CLASSES.BUTTON_CANCEL') : $t('CLASSES.BUTTON_CLOSE') }}
          </b-button>

          <b-button v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])" class="btn-custom-green" @click="onSubmitAssignStudent()">
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
            <b-col cols="12" sm="12" md="12" lg="12" :xl="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN]) ? 6 : 12">
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
                            <b-button v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])" variant="danger" size="sm" :disabled="isProcess" @click="deleteCourseInClass(course, index)">
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
                                :disabled="isProcess || !hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])"
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
                                :disabled="isProcess || !hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])"
                              >
                                <template #button-content>
                                  <i class="fad fa-calendar-day icon-date" />
                                </template>
                              </b-form-datepicker>
                            </div>

                            <div class="item-input">
                              <label>{{ $t('CLASSES.LABLE_TEACHER') }}</label>
                              <b-form-select
                                v-model="listCourseSelected[index]['teacher_id']"
                                :disabled="isProcess || !hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])"
                              >
                                <b-form-select-option :value="null">
                                  {{ $t('CLASSES.PLACEHOLDER_SELECT_TEACHER') }}
                                </b-form-select-option>
                                <b-form-select-option
                                  v-for="teacher in listCourseSelected[index]['teacher_list']"
                                  :key="teacher.text"
                                  :value="teacher.value"
                                >
                                  {{ teacher.text }}
                                </b-form-select-option>
                              </b-form-select>
                            </div>
                          </div>
                        </template>
                      </b-card>
                    </div>
                  </div>
                </template>
              </div>
            </b-col>

            <b-col v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])" cols="12" sm="12" md="12" lg="12" xl="6">
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
                              <b-button class="btn-custom-green" size="sm" :disabled="isProcess" @click="addCourseToClass(course, index)">
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
                  :disabled="isProcess"
                />
              </div>
            </b-col>
          </b-row>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="visibleModalAssignCourse = false">
            {{ hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN]) ? $t('CLASSES.BUTTON_CANCEL') : $t('CLASSES.BUTTON_CLOSE') }}
          </b-button>

          <b-button v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN])" class="btn-custom-green" :disabled="isProcess" @click="onSubmitAssignCourse()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalActivity"
        size="xl"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        scrollable
        body-class="modal-activity-content"
        footer-class="modal-activity-footer"
      >
        <template #modal-header>
          <h5>{{ $t('CLASSES.MODAL_TITLE_ACTIVITY') }}: {{ isClassHandle.name }}</h5>
        </template>

        <template #default>
          <b-row v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN, CONST_ROLE.LIST_ROLE.TEACHER])">
            <b-col>
              <div class="item-input">
                <label>{{ $t('CLASSES.LABEL_FORM_NAME') }}</label>
                <b-form-input v-model="isActivity.name" :placeholder="$t('CLASSES.PLACEHOLDER_FORM_NAME')" :disabled="isProcess" />
              </div>
            </b-col>
          </b-row>

          <b-row v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN, CONST_ROLE.LIST_ROLE.TEACHER])">
            <b-col>
              <div class="item-input">
                <label>{{ $t('CLASSES.LABEL_FORM_DESCRIPTION') }}</label>
                <b-form-textarea
                  v-model="isActivity.description"
                  :placeholder="$t('CLASSES.PLACEHOLDER_FORM_DESCRIPTION')"
                  rows="5"
                  max-rows="10"
                  :disabled="isProcess"
                />
              </div>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <div class="item-input">
                <div v-for="(activity) in itemActivity" :key="`activity-${activity.id}`" class="item-activity">
                  <b-card>
                    <template #header>
                      <div class="d-flex justify-content-between">
                        <div class="align-self-center">
                          <span><b>{{ activity.name }}</b></span>
                        </div>
                        <b-button v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.TEACHER])" class="btn-custom-green" size="sm" :disabled="isProcess" @click="onClickGradeActivity(activity)">
                          <i class="fas fa-pencil-alt" />
                        </b-button>

                        <b-button v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.STUDENT])" class="btn-custom-green" size="sm" :disabled="isProcess" @click="onClickHandinActivity(activity)">
                          <i class="fas fa-paper-plane" />
                        </b-button>
                      </div>
                    </template>

                    <template #default>
                      <b-row>
                        <b-col>
                          <div class="item-input">
                            {{ activity.description }}
                          </div>
                        </b-col>
                      </b-row>
                    </template>

                    <template v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.STUDENT])" #footer>
                      <b-row v-if="activity['file_path']">
                        <b-col>
                          <div class="item-input">
                            <b>{{ $t('CLASSES.GRADE') }}: </b> {{ activity.grade ? activity.grade : $t('CLASSES.NOT_GRADE') }}
                          </div>
                          <div class="item-input">
                            <b v-if="activity.grade">{{ $t('CLASSES.COMMENT') }}: </b> {{ activity.comment ? activity.comment : '' }}
                          </div>
                        </b-col>
                      </b-row>
                      <b-row v-else>
                        <b-col>
                          <div class="item-input">
                            {{ $t('CLASSES.ALERT_NO_SUBMIT') }}
                          </div>
                        </b-col>
                      </b-row>
                    </template>
                  </b-card>
                </div>
              </div>
              <div class="pagination-course">
                <b-pagination
                  v-model="paginationActivity.page"
                  pills
                  size="sm"
                  first-number
                  last-number
                  align="right"
                  :total-rows="paginationActivity.total"
                  :per-page="paginationActivity.perPage"
                  :disabled="isProcess"
                />
              </div>
            </b-col>
          </b-row>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="onClickCancelActivity()">
            {{ hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN, CONST_ROLE.LIST_ROLE.TEACHER]) ? $t('CLASSES.BUTTON_CANCEL') : $t('CLASSES.BUTTON_CLOSE') }}
          </b-button>

          <b-button v-if="hasRole(getCurrentRole(), [CONST_ROLE.LIST_ROLE.ADMIN, CONST_ROLE.LIST_ROLE.TEACHER])" class="btn-custom-green" :disabled="isProcess" @click="onClickSubmitActivity()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalHandinActivity"
        size="xl"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        scrollable
        body-class="modal-handin-activity-content"
        footer-class="modal-handin-activity-footer"
      >
        <template #modal-header>
          <h5>{{ isActivityHandle.name }}</h5>
        </template>

        <template #default>
          <b-row>
            <b-col>
              <div class="item-input">
                <label>{{ $t('CLASSES.LABEL_FORM_DESCRIPTION') }}</label>
                <b-form-textarea
                  v-model="isHandinActivity.description"
                  :placeholder="$t('CLASSES.PLACEHOLDER_FORM_DESCRIPTION')"
                  rows="5"
                  max-rows="10"
                  :disabled="isProcess"
                />
              </div>
            </b-col>
          </b-row>

          <b-row>
            <b-col>
              <div class="item-input">
                <label>{{ $t('CLASSES.LABEL_FORM_FILE') }}</label>
                <input id="input-handin-activity" type="file" name="input-handin-activity" @change="chooseFile">
                <div>
                  <b-button
                    class="btn-custom-green"
                    @click="clickChooseFile()"
                  >
                    <i class="fas fa-cloud-upload-alt" style="margin-right: 10px;" />
                    {{ $t('CLASSES.PLACEHOLDER_DOCS_FILE') }}
                  </b-button>
                </div>
              </div>
            </b-col>
          </b-row>

          <b-row v-if="isHandinActivity.file">
            <b-col>
              <div class="item-input">
                <i class="fas fa-file" />
                <span style="margin-left: 5px;">{{ isHandinActivity.file.name }}</span>
              </div>
            </b-col>
          </b-row>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="onClickCancelHandinActivity()">
            {{ $t('CLASSES.BUTTON_CANCEL') }}
          </b-button>

          <b-button class="btn-custom-green" :disabled="isProcess" @click="onClickSubmitHandinActivity()">
            <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
            {{ $t('CLASSES.BUTTON_SUBMIT') }}
          </b-button>
        </template>
      </b-modal>

      <b-modal
        v-model="visibleModalGradeActivity"
        size="xl"
        no-close-on-esc
        no-close-on-backdrop
        hide-header-close
        scrollable
        body-class="modal-grade-activity-content"
        footer-class="modal-grade-activity-footer"
      >
        <template #modal-header>
          <h5>{{ isActivityHandle.name }}</h5>
        </template>

        <template #default>
          <b-row>
            <b-col cols="12" sm="12" md="12" lg="12" xl="3">
              <div class="list-student">
                <div v-for="handin in itemHandinActivity" :key="handin.id" class="item-input" @click="onClickViewHandin(handin)">
                  <b-card>
                    <template #header>
                      {{ handin['student']['user_code'] }}
                    </template>
                    <b-card-text>
                      <div>
                        <span><b>{{ $t('ACCOUNT.LABEL_FORM_FULLNAME') }}: </b>{{ handin['student']['name'] }}</span>
                      </div>
                      <div>
                        <span><b>{{ $t('ACCOUNT.LABEL_FORM_TELEPHONE') }}: </b>{{ handin['student']['phone'] }}</span>
                      </div>
                      <div>
                        <span>
                          <b>{{ $t('ACCOUNT.LABEL_FORM_BLIND') }}: </b>
                          <b-badge v-if="handin['student']['isBlind'] === 1" variant="danger">
                            {{ $t('CLASSES.TEXT_YES') }}
                          </b-badge>
                          <b-badge v-if="handin['student']['isBlind'] === 0" variant="success">
                            {{ $t('CLASSES.TEXT_NO') }}
                          </b-badge>
                        </span>
                      </div>
                      <div>
                        <span>
                          <b>{{ $t('ACCOUNT.LABEL_FORM_STATUS') }}: </b>
                          <b-badge v-if="handin['student']['status'] === 1" variant="success">
                            {{ $t('CLASSES.TEXT_ACTIVE') }}
                          </b-badge>
                          <b-badge v-if="handin['student']['status'] === 0" variant="danger">
                            {{ $t('CLASSES.TEXT_INACTIVE') }}
                          </b-badge>
                        </span>
                      </div>
                    </b-card-text>
                  </b-card>
                </div>
              </div>

              <div v-if="itemHandinActivity.length && isActivityHandle['id']" class="zone-active-grade">
                <div class="item-input">
                  <b-input-group class="mt-3">
                    <template #append>
                      <b-input-group-text>100</b-input-group-text>
                    </template>
                    <b-form-input v-model="isGrade['grade']" type="number" :min="0" :max="100" :disabled="isProcess" />
                  </b-input-group>
                </div>
                <div class="item-input">
                  <b-form-textarea
                    v-model="isGrade['comment']"
                    rows="8"
                    max-rows="8"
                    :disabled="isProcess"
                  />
                </div>

                <div class="item-input">
                  <b-button block class="btn-custom-green" :disabled="isProcess" @click="submitGrade()">
                    <i v-if="isProcess" class="fad fa-spinner-third fa-spin" />
                    {{ $t('CLASSES.BUTTON_SUBMIT') }}
                  </b-button>
                </div>
              </div>
            </b-col>

            <b-col cols="12" sm="12" md="12" lg="12" xl="9">
              <vue-pdf-app id="view-pdf" :pdf="isGrade['pdf']" style="min-height: 700px;" />
            </b-col>
          </b-row>
        </template>

        <template #modal-footer>
          <b-button variant="outline-danger" :disabled="isProcess" @click="onClickCloseGradeActivity()">
            {{ $t('CLASSES.BUTTON_CLOSE') }}
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
  getAllClassTeacher: '/class/teacher/list',
  getAllClassStudent: '/classes/student/list',
  getOne: '/classes',
  postClasses: '/classes',
  putClasses: '/classes',
  deleteClasses: '/classes',
  getStudent: '/user/students',
  assignStudent: '/classes/students',
  getAllCourse: '/courses',
  getOneCourse: '/courses',
  assignCourse: '/classes/courses',
  postActivity: '/class/action/create-action',
  getAllActivity: '/class/action/teacher/actions',
  postHandinActivity: '/class/action/student/handin',
  getAllHandinActivity: '/class/action/teacher/handin',
  postSubmitGrade: '/class/action/teacher/grade',
  getActionDetail: '/class/action/student/action-detail',
};
import {
  getAllClasses,
  getAllClassTeacher,
  getAllClassStudent,
  getOneClasses,
  postClasses,
  putClasses,
  deleteClasses,
  getStudent,
  assignStudent,
  assignCourse,
  postActivity,
  getAllActivity,
  postHandinActivity,
  getAllHandinActivity,
  postSubmitGrade,
  getActionDetail,
} from '@/api/modules/classes';
import {
  getAllCourse,
  getOneCourse,
} from '@/api/modules/course';

import {
  validateAddClasses,
  validateUpdateClasses,
  validateAssingCourse,
  validateActivity,
} from './validate';

import { isAvailable } from '@/utils/isAvailable';
import { hasRole, getCurrentRole } from '@/utils/hasRole';
import CONST_ROLE from '@/const/role';

import NotifyClasses from '@/toast/modules/classes';

import VuePdfApp from 'vue-pdf-app';
import 'vue-pdf-app/dist/icons/main.css';

export default {
  name: 'Classes',
  components: {
    VuePdfApp,
  },
  data() {
    return {
      hasRole,
      getCurrentRole,
      CONST_ROLE,

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

      isActivity: {
        name: '',
        description: '',
      },

      isHandinActivity: {
        description: '',
        file: null,
      },

      isFilter: {
        name: '',
      },

      items: [],
      itemActivity: [],
      itemHandinActivity: [],

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
      paginationActivity: {
        page: 1,
        perPage: 10,
        total: 0,
      },
      paginationHandinActivity: {
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
      visibleModalActivity: false,
      visibleModalHandinActivity: false,
      visibleModalGradeActivity: false,

      isAction: '',
      idHandle: null,
      isProcess: false,
      isClassHandle: {
        id: null,
        name: '',
        level: 1,
      },
      isActivityHandle: {
        id: '',
        class_id: '',
        teacher_id: '',
        name: '',
        description: '',
      },
      isGrade: {
        id: '',
        grade: 0,
        comment: '',
        pdf: '',
        student: {
          email: '',
          id: '',
          isBlind: '',
          name: '',
          phone: '',
          status: '',
          user_code: '',
        },
      },
    };
  },
  computed: {
    fields() {
      const HEADER = [
        {
          key: 'name',
          label: this.$t('CLASSES.TABLE_TITLE_NAME'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
      ];

      if (getCurrentRole() === CONST_ROLE.LIST_ROLE.ADMIN || getCurrentRole() === CONST_ROLE.LIST_ROLE.TEACHER || getCurrentRole() === CONST_ROLE.LIST_ROLE.STUDENT) {
        HEADER.push({
          key: 'activity',
          label: this.$t('CLASSES.TABLE_TITLE_ACTIVITY'),
          thClass: 'base-th',
          tdClass: 'base-td base-arrangement',
        });

        HEADER.push({
          key: 'arrangement',
          label: this.$t('CLASSES.TABLE_TITLE_ARRANGEMENT'),
          thClass: 'base-th',
          tdClass: 'base-td base-arrangement',
        });
      }

      if (getCurrentRole() === CONST_ROLE.LIST_ROLE.ADMIN) {
        HEADER.push({
          key: 'actions',
          lebael: this.$t('CLASSES.TABLE_TITLE_ACTIONS'),
          thClass: 'base-th',
          tdClass: 'base-td base-actions',
        });
      }

      return HEADER;
    },
    fieldsActivity() {
      const HEADER = [
        {
          key: 'name',
          label: this.$t('CLASSES.TABLE_TITLE_NAME'),
          sortable: true,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
      ];

      return HEADER;
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
    currentPageClass() {
      return this.pagination.page;
    },
    currentPageStudent() {
      return this.paginationStudent.page;
    },
    currentPageCourse() {
      return this.paginationCourse.page;
    },
    currentPageActivity() {
      return this.paginationActivity.page;
    },
    currenPageHandinActivity() {
      return this.paginationHandinActivity.page;
    },
  },
  watch: {
    async currentPageClass() {
      this.overlay.show = true;

      await this.handleGetAllClasses();

      this.overlay.show = false;
    },
    async currentPageStudent() {
      this.overlay.show = true;

      await this.handleGetListStudent();

      this.overlay.show = false;
    },
    async currentPageCourse() {
      this.overlay.show = true;

      await this.handleGetListCourse();

      this.overlay.show = false;
    },
    async currentPageActivity() {
      await this.handleGetListActivity();
    },
    async currenPageHandinActivity() {

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
      const PARAMS = {
        page: this.pagination.page,
        per_page: this.pagination.perPage,
      };

      let res;

      try {
        if (getCurrentRole() === CONST_ROLE.LIST_ROLE.ADMIN) {
          const URL = URL_API['getAll'];
          res = await getAllClasses(URL, PARAMS);

          if (res) {
            this.items = res['data']['data'];
            this.pagination.page = res['data']['current_page'];
            this.pagination.total = res['data']['total'];
          } else {
            NotifyClasses.server(res['message']);
          }
        }

        if (getCurrentRole() === CONST_ROLE.LIST_ROLE.TEACHER) {
          const URL = URL_API['getAllClassTeacher'];
          res = await getAllClassTeacher(URL, PARAMS);
          if (res) {
            this.items = res['data'];
            this.pagination.page = res['current_page'];
            this.pagination.total = res['total'];
          } else {
            NotifyClasses.server(res['message']);
          }
        }

        if (getCurrentRole() === CONST_ROLE.LIST_ROLE.STUDENT) {
          const URL = URL_API['getAllClassStudent'];
          res = await getAllClassStudent(URL, PARAMS);
          if (res) {
            this.items = res['data'];
            this.pagination.page = res['current_page'];
            this.pagination.total = res['total'];
          } else {
            NotifyClasses.server(res['message']);
          }
        }
      } catch (error) {
        NotifyClasses.exception();
        console.log(error);
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

          let idx = 0;
          const len = this.listCourseSelected.length;

          while (idx < len) {
            const LIST_TEACHER = await this.handleGetListTeacherInCourse(this.listCourseSelected[idx]['id']);
            this.listCourseSelected[idx]['teacher_list'] = await this.createSelectTeacher(LIST_TEACHER);

            idx++;
          }

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
      this.isProcess = true;
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

        this.isProcess = false;
      } catch {
        this.isProcess = true;
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
    async handleGetListTeacherInCourse(course_id) {
      const URL = `${URL_API.getOneCourse}/${course_id}`;

      try {
        const res = await getOneCourse(URL);

        return res['data']['teachers'];
      } catch (error) {
        NotifyClasses.server(error);
        return [];
      }
    },
    createSelectTeacher(list) {
      let idx = 0;
      const len = list.length;
      const result = [];

      while (idx < len) {
        result.push({
          value: list[idx]['id'],
          text: `${list[idx]['user_code']} - ${list[idx]['name']}`,
        });

        idx++;
      }

      return result;
    },
    async addCourseToClass(course, index) {
      course['start_date'] = '';
      course['end_date'] = '';
      course['teacher_id'] = null;
      const LIST_TEACHER = await this.handleGetListTeacherInCourse(course.id);
      course['teacher_list'] = this.createSelectTeacher(LIST_TEACHER);

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
          teacher_id: listCourse[idx]['teacher_id'],
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

          await this.handleGetA;
          NotifyClasses.assignCourseSuccess();
        } else {
          NotifyClasses.server(res['message']);
        }
      } else {
        NotifyClasses.validateForm(`NOTIFY.CLASSES.VALIDATE_LIST_ASSIGN_COURSE`);
      }

      this.isProcess = false;
    },
    async handleGetListActivity() {
      const URL = `${URL_API.getAllActivity}`;
      const PARAMS = {
        class_id: this.isClassHandle.id,
        page: this.paginationActivity.page,
        per_page: this.paginationActivity.perPage,
      };

      try {
        const res = await getAllActivity(URL, PARAMS);

        this.itemActivity = res['data'];
        this.paginationActivity.page = res.current_page;
        this.paginationActivity.total = res.total;

        let idx = 0;
        const len = res['data'].length;

        while (idx < len) {
          await this.handleGetActivityDetail(res['data'][idx]['id'], idx);

          idx++;
        }
      } catch (error) {
        console.log(error);
        NotifyClasses.server(error['response']['data']['message']);
      }
    },
    async handleGetActivityDetail(id, index) {
      try {
        const URL = URL_API.getActionDetail;
        const PARAMS = {
          action_id: id,
        };

        const res = await getActionDetail(URL, PARAMS);

        if (res['handin']) {
          this.itemActivity[index].grade = res['handin']['grade'];
          this.itemActivity[index].comment = res['handin']['comment'];
          this.itemActivity[index].file_path = res['handin']['file_path'];
        } else {
          this.itemActivity[index].grade = '';
          this.itemActivity[index].comment = '';
          this.itemActivity[index].file_path = '';
        }
      } catch (error) {
        console.log(`Error get deatil action: ${id}`);
      }
    },
    async onClickActivity(item) {
      this.isResetModalActivity();

      this.isClassHandle.id = item.id;
      this.isClassHandle.name = item.name;
      this.isClassHandle.level = 1;

      await this.handleGetListActivity(item.id);

      this.visibleModalActivity = true;
    },
    isResetModalActivity() {
      const isActivity = {
        name: '',
        description: '',
      };

      this.isActivity = isActivity;
    },
    onClickCancelActivity() {
      this.visibleModalActivity = false;
    },
    async onClickSubmitActivity() {
      const URL = URL_API.postActivity;
      const DATA = {
        class_id: this.isClassHandle.id,
        name: this.isActivity.name,
        description: this.isActivity.description,
      };

      if (validateActivity(DATA)) {
        try {
          const res = await postActivity(URL, DATA);

          if (res) {
            this.visibleModalActivity = false;
            NotifyClasses.createActivitySuccess();
          } else {
            NotifyClasses.server(res['message']);
          }
        } catch (error) {
          console.log(error);
          NotifyClasses.server(error['response']['data']['message']);
        }
      } else {
        NotifyClasses.validateCreateActivity();
      }
    },
    onClickHandinActivity(activity) {
      this.visibleModalHandinActivity = true;
      this.isActivityHandle.id = activity.id;
      this.isActivityHandle.class_id = activity.class_id;
      this.isActivityHandle.teacher_id = activity.teacher_id;
      this.isActivityHandle.name = activity.name;
      this.isActivityHandle.description = activity.description;
    },
    onClickCancelHandinActivity() {
      this.visibleModalHandinActivity = false;
    },
    isResetModalHandinActivity() {
      const isHandinActivity = {
        description: '',
        file: null,
      };

      if (isAvailable(document.getElementById('input-handin-activity'), 'value')) {
        document.getElementById('input-handin-activity').value = null;
      }

      this.isHandinActivity = isHandinActivity;
    },
    async onClickSubmitHandinActivity() {
      const URL = URL_API.postHandinActivity;

      const DATA = new FormData();
      DATA.append('action_id', this.isActivityHandle.id);
      DATA.append('class_id', this.isActivityHandle.class_id);
      DATA.append('description', this.isHandinActivity.description);
      DATA.append('files[0]', this.isHandinActivity.file);

      try {
        await postHandinActivity(URL, DATA);

        this.visibleModalHandinActivity = false;
        this.isResetModalHandinActivity();

        NotifyClasses.handinActivitySuccess();
      } catch (error) {
        console.log(error);
        NotifyClasses.exception();
        this.visibleModalHandinActivity = false;
        this.isResetModalHandinActivity();
      }
    },
    clickChooseFile() {
      const FILE = document.getElementById('input-handin-activity');
      FILE.click();
    },
    chooseFile(event) {
      this.isHandinActivity.file = event.target.files[0];
    },
    async onClickGradeActivity(activity) {
      this.visibleModalGradeActivity = true;

      try {
        const URL = URL_API.getAllHandinActivity;
        const PARAMS = {
          action_id: activity.id,
          page: this.paginationHandinActivity.page,
          per_page: this.paginationHandinActivity.perPage,
        };

        const res = await getAllHandinActivity(URL, PARAMS);

        if (res) {
          this.itemHandinActivity = res['data'];
          this.paginationHandinActivity.page = res['current_page'];
          this.paginationHandinActivity.total = res['total'];

          this.isActivityHandle.id = activity.id;
          this.isActivityHandle.class_id = activity.class_id;
          this.isActivityHandle.teacher_id = activity.teacher_id;
          this.isActivityHandle.name = activity.name;
          this.isActivityHandle.description = activity.description;
        } else {
          NotifyClasses.exception();
        }
      } catch (error) {
        console.log(error);
      }
    },
    onClickCloseGradeActivity() {
      this.visibleModalGradeActivity = false;
      this.resetViewHandin();
    },
    onClickViewHandin(handin) {
      this.isGrade = {
        id: handin.id ? handin.id : '',
        grade: handin.grade ? handin.grade : 0,
        comment: handin.comment ? handin.comment : '',
        pdf: handin.file_path ? handin.file_path : '',
        student: handin.student ? handin.student : {
          email: '',
          id: '',
          isBlind: '',
          name: '',
          phone: '',
          status: '',
          user_code: '',
        },
      };
    },
    resetViewHandin() {
      this.isGrade = {
        id: '',
        grade: 0,
        comment: '',
        pdf: '',
        student: {
          email: '',
          id: '',
          isBlind: '',
          name: '',
          phone: '',
          status: '',
          user_code: '',
        },
      };
    },
    async submitGrade() {
      this.isProcess = true;

      try {
        const URL = URL_API.postSubmitGrade;
        const DATA = {
          student_handin_id: this.isGrade.id,
          grade: this.isGrade.grade,
          comment: this.isGrade.comment,
        };

        const res = await postSubmitGrade(URL, DATA);

        if (res['status'] === 200) {
          this.onClickGradeActivity(this.isActivityHandle);
        }

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

.modal-activity-content {
  .item-input {
    margin-bottom: 15px;

    .item-activity {
      .card-header {
          padding: 0.5rem 0.75rem;
      }

      .card-body {
          padding: 0.5rem 0.75rem;
      }

      margin-bottom: 15px;
    }
  }
}

.modal-handin-activity-content {
  .item-input {
      #input-handin-activity {
          display: none;
      }

      margin-bottom: 15px;
  }
}

.modal-grade-activity-content {
  .list-student {
    max-height: 350px;
    overflow: auto;
    margin-bottom: 10px;
    padding: 10px;
  }

  .item-input {
    .card-header {
      padding: 0.5rem 0.75rem;
      font-weight: bold;
    }

    .card-body {
      padding: 0.5rem 0.75rem;
    }

    margin-bottom: 15px;
  }
}

.icon-loading {
  font-size: 50px;
  color: $forest-green;
}

.item-input {
  &:not(:last-child) {
      margin-bottom: 10px;
  }
}
.icon-date {
  color: $forest-green;
  font-size: 1.25rem;
}
.input-group-text {
  border-color: transparent;
}
</style>
