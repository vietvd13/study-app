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

    <div class="view-grade">
      <b-col>
        <b-card>
          <b-row>
            <b-col cols="12" sm="12" md="6" lg="6" xl="6">
              <b-form-select
                id="filter-class"
                v-model="selectClass"
              >
                <b-form-select-option :value="null">
                  {{ $t('DASHBOARD.PLEASE_SELECT') }}
                </b-form-select-option>
                <b-form-select-option
                  v-for="itemClass in listClass"
                  :key="itemClass.text"
                  :value="itemClass.value"
                >
                  {{ itemClass.text }}
                </b-form-select-option>
              </b-form-select>
            </b-col>

            <b-col cols="12" sm="12" md="6" lg="6" xl="6">
              <b-form-select
                id="filter-test"
                v-model="selectTest"
              >
                <b-form-select-option :value="null">
                  {{ $t('DASHBOARD.PLEASE_SELECT') }}
                </b-form-select-option>
                <b-form-select-option
                  v-for="itemTest in listTest"
                  :key="itemTest.text"
                  :value="itemTest.value"
                >
                  {{ itemTest.text }}
                </b-form-select-option>
              </b-form-select>
            </b-col>
          </b-row>
        </b-card>

        <div style="margin-top: 10px;">
          <b-card>
            <b-table
              id="table-grade"
              bordered
              striped
              responsive
              :no-sort-reset="true"
              no-local-sorting
              show-empty
              :fields="fields"
              :items="listGrade"
            >
              <template #cell(result)="data">
                {{ data.item.grade }}
              </template>

              <template #cell(actions)="data">
                <b-button
                  class="btn-custom-green"
                  @click="handleViewDetailGrade(data.item.id)"
                >
                  <i class="fas fa-eye" />
                </b-button>
              </template>

              <template #empty>
                <span class="d-flex justify-content-center">
                  {{ $t('ACCOUNT.TABLE_CONTENT_NO_DATA') }}
                </span>
              </template>
            </b-table>
          </b-card>
        </div>
      </b-col>
    </div>

    <b-modal
      v-model="modalGrade"
      size="xl"
      no-close-on-esc
      no-close-on-backdrop
      hide-header-close
      body-class="modal-grade-content"
      footer-class="modal-grade-footer"
    >
      <template #modal-header>
        <h5>{{ $t('DASHBOARD.MODAL_TITLE_GRADE') }}</h5>
      </template>

      <template #default>
        <div class="display-question">
          <div v-for="(question, idxQuestion) in viewResult['test_of_student']" :id="`question-${idxQuestion + 1}`" :key="question.id" class="question">
            <div class="text-question">
              <span>
                <b>{{ $t('DO_TEST.TEXT_QUESTION') }} {{ idxQuestion + 1 }}: </b>
                {{ question.question }}
              </span>
            </div>

            <div class="text-answer">
              <b-form-group>
                <b-form-radio-group
                  v-model="question['choose_answer']"
                  stacked
                >
                  <b-form-radio
                    v-for="(answer, indexAnswer) in question.answers"
                    :key="answer.id"
                    :name="answer.answer"
                    :value="answer.id"
                    :class="handleStyleAnswer(question, answer.id, indexAnswer)"
                    :disabled="true"
                  >
                    <span class="highlight-answer">{{ answer.answer }}</span>
                  </b-form-radio>
                </b-form-radio-group>
              </b-form-group>
            </div>
          </div>
        </div>
      </template>

      <template #modal-footer>
        <b-button variant="outline-danger" @click="handleCloseModalGrade()">
          {{ $t('DASHBOARD.BUTTON_CLOSE') }}
        </b-button>
      </template>
    </b-modal>

  </b-overlay>
</template>

<script>

const URL_API = {
  getAllClassByTeacher: '/class/teacher/list',
  getAllTestByClass: '/test/student/list-test',
  getResultTest: '/test/teacher/view-grade',
};

import {
  getAllClassByTeacher,
  getAllTestByClass,
  getResultTest,
} from '@/api/modules/dashboard';

export default {
  name: 'Dashboard',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },
      selectClass: null,
      selectTest: null,
      listClass: [],
      listTest: [],

      listGrade: [],

      modalGrade: false,
      viewResult: {
        id: -1,
        name: '',
        user_code: '',
        email: '',
        phone: '',
        status: -1,
        isBlind: -1,
        test_of_student: [],
      },
    };
  },
  computed: {
    fields() {
      return [
        {
          key: 'user_code',
          label: this.$t('ACCOUNT.TABLE_TITLE_STUDENT_ID'),
          sortable: false,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'name',
          label: this.$t('ACCOUNT.TABLE_TITLE_FULLNAME'),
          sortable: false,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'phone',
          label: this.$t('ACCOUNT.TABLE_TITLE_PHONE'),
          sortable: false,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'email',
          label: this.$t('ACCOUNT.TABLE_TITLE_EMAIL'),
          sortable: false,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'result',
          label: this.$t('DASHBOARD.TALBE_HEADER_VIEW_RESULT'),
          sortable: false,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
        {
          key: 'actions',
          label: this.$t('DASHBOARD.TALBE_HEADER_VIEW_ACTIONS'),
          sortable: false,
          thClass: 'base-th',
          tdClass: 'base-td',
        },
      ];
    },
  },
  watch: {
    selectClass() {
      this.selectTest = null;
      this.listTest = [];
      this.listGrade = [];

      this.handleGetAllTest();
    },
    selectTest() {
      if (this.selectTest) {
        this.handleViewGrade();
      } else {
        this.listGrade = [];
      }
    },
  },
  created() {
    this.initData();
  },
  methods: {
    async initData() {
      await this.handleGetAllClass();
    },
    async handleGetAllClass() {
      this.overlay.show = true;
      try {
        const URL = URL_API.getAllClassByTeacher;
        const PARAMS = {
          page: 1,
          per_page: 10000,
        };

        const res = await getAllClassByTeacher(URL, PARAMS);

        if (res) {
          const DATA = res['data'];
          const RESULT = [];

          const len = DATA.length;
          let idx = 0;

          while (idx < len) {
            RESULT.push({
              value: DATA[idx]['id'],
              text: DATA[idx]['name'],
            });

            idx++;
          }

          this.listClass = RESULT;
          this.overlay.show = false;
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },
    async handleGetAllTest() {
      this.overlay.show = true;
      if (this.selectClass) {
        try {
          const URL = URL_API.getAllTestByClass;
          const PARAMS = {
            class_id: this.selectClass,
            page: 1,
            per_page: 10000,
          };

          const res = await getAllTestByClass(URL, PARAMS);

          if (res) {
            const DATA = res['data'];
            const RESULT = [];

            const len = DATA.length;
            let idx = 0;

            while (idx < len) {
              RESULT.push({
                value: DATA[idx]['id'],
                text: DATA[idx]['test_name'],
              });

              idx++;
            }

            this.listTest = RESULT;
            this.overlay.show = false;
          }
        } catch (error) {
          console.log(error);
          this.overlay.show = false;
        }
      }

      this.overlay.show = false;
    },
    async handleViewGrade() {
      this.overlay.show = true;

      try {
        const URL = URL_API.getResultTest;
        const PARAMS = {
          test_id: this.selectTest,
        };

        const res = await getResultTest(URL, PARAMS);
        this.listGrade = res;

        const len = this.listGrade.length;
        let idx = 0;

        while (idx < len) {
          this.listGrade[idx]['grade'] = this.generateGrade(this.listGrade[idx]['test_of_student'], idx);

          idx++;
        }

        this.overlay.show = false;
      } catch (error) {
        console.log(error);
        this.overlay.show = false;
      }

      this.overlay.show = false;
    },
    generateGrade(result, index) {
      const TOTAL_QUESTION = result.length;
      let INDEX_QUESTION = 0;

      let GRADE = 0;

      while (INDEX_QUESTION < TOTAL_QUESTION) {
        const ANSWER = result[INDEX_QUESTION]['answers'];

        const TOTAL_ANSWER = ANSWER.length;
        let INDEX_ANSWER = 0;

        while (INDEX_ANSWER < TOTAL_ANSWER) {
          if (ANSWER[INDEX_ANSWER]['selected']) {
            if (ANSWER[INDEX_ANSWER]['is_correct'] === 1) {
              GRADE = GRADE + 1;
            }

            this.listGrade[index]['test_of_student'][INDEX_QUESTION]['choose_answer'] = ANSWER[INDEX_ANSWER]['id'];
          }

          INDEX_ANSWER++;
        }

        INDEX_QUESTION++;
      }

      return `${GRADE}/${TOTAL_QUESTION}`;
    },
    handleViewDetailGrade(id) {
      const len = this.listGrade.length;
      let idx = 0;

      while (idx < len) {
        if (this.listGrade[idx]['id'] === id) {
          this.viewResult = this.listGrade[idx];
        }

        idx++;
      }

      this.modalGrade = true;
    },
    handleCloseModalGrade() {
      this.modalGrade = false;

      const VIEW_GRADE = {
        id: -1,
        name: '',
        user_code: '',
        email: '',
        phone: '',
        status: -1,
        isBlind: -1,
        test_of_student: [],
      };

      this.viewResult = VIEW_GRADE;
    },
    handleStyleAnswer(question, answer_id, index_answer) {
      if (question['choose_answer'] === answer_id) {
        if (question['answers'][index_answer]['is_correct'] === 1) {
          return 'correct';
        } else {
          return 'in-correct';
        }
      }
    },
  },
};
</script>

<style lang="scss" scoped>
	@import '@/scss/_variables';

  .view-grade {
		padding: 10px;
    height: calc(100vh - 57px);

			::v-deep table#table-grade {
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

  .icon-loading {
    font-size: 50px;
    color: $forest-green;
  }

  .modal-grade-content {
    .display-question {
      margin-bottom: 10px;

      .question {
        margin-bottom: 10px;
      }

      .text-question{
        .icon-play-sound {
          cursor: pointer;
          margin-right: 5px;
          color: $green-haze;
        }
      }

      .text-answer {
        margin-top: 20px;

        label {
          font-weight: 400;
        }

        .custom-radio {
          &:not(:last-child) {
            margin-bottom: 15px;
          }
        }

        ::v-deep .correct {
          .highlight-answer {
            color: $green-haze;
          }

          .custom-control-input:checked ~ .custom-control-label::before {
            color: $white;
            border-color: $green-haze;
            background-color: $green-haze;
          }
        }

        ::v-deep .in-correct {
          .highlight-answer {
            color: $cardinal;
          }

          .custom-control-input:checked ~ .custom-control-label::before {
            color: $white;
            border-color: $cardinal;
            background-color: $cardinal;
          }
        }
      }
    }
  }
</style>
