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

    <div class="student-test">
      <b-col>
        <b-row v-show="listTest.length === 0">
          <b-col>
            <div class="text-center">
              {{ $t('CHOOSE_TEST.TEXT_NO_DATA') }}
            </div>
          </b-col>
        </b-row>
        <b-row v-show="listTest.length > 0">
          <template v-for="item in listTest">
            <b-col :key="item.id" cols="12" sm="12" md="6" lg="6" xl="3">
              <div class="display-test">
                <b-card>
                  <template #header>
                    <h6 class="mb-0 text-center">{{ $t('CHOOSE_TEST.TEXT_TEST') }}</h6>
                  </template>
                  <div class="text-center">{{ item['test_name'] }}</div>
                  <!-- <div class="text-center">{{ item['course_id'] }}</div> -->
                  <template #footer>
                    <b-button block class="btn-custom-green" @click="onClickGoToTest(item['id'])">
                      <i class="far fa-paper-plane" style="margin-right: 5px" />
                      {{ $t('CHOOSE_TEST.TEXT_GO_TO_TEST') }}
                    </b-button>

                    <b-button block class="btn-custom-green" style="margin-top: 10px;" @click="handleViewResultTest(item['id'])">
                      <i class="fas fa-file-certificate" style="margin-right: 5px" />
                      {{ $t('CHOOSE_TEST.VIEW_GRADE') }}
                    </b-button>
                  </template>
                </b-card>
              </div>
            </b-col>
          </template>
        </b-row>
      </b-col>
    </div>

    <b-modal
      v-model="modalTestResult"
      no-close-on-esc
      no-close-on-backdrop
      hide-header-close
      body-class="modal-view-result-content"
      footer-class="modal-view-result-footer"
    >
      <template #modal-header>
        <h5>{{ $t('DO_TEST.TITLE_VIEW_RESULT') }}</h5>
      </template>

      <template #default>
        <b-col>
          <b-row>
            <b-col>
              <h1 class="text-center text-grade">{{ `${testResult.total_of_correct}/${testResult.total_of_questions}` }}</h1>
            </b-col>
          </b-row>
        </b-col>
      </template>

      <template #modal-footer>
        <b-button variant="outline-danger" @click="modalTestResult = !modalTestResult">{{ $t('CHOOSE_TEST.CLOSE') }}</b-button>
      </template>
    </b-modal>
  </b-overlay>
</template>

<script>

const URL_API = {
  getAllTestByClass: '/test/student/list-test',
  getResultTest: '/test/student/view-grade',
};

import { getAllTestByClass } from '@/api/modules/choose_test';
import { getResultTest } from '@/api/modules/do_test';

export default {
  name: 'ChooseTest',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      listTest: [],

      modalTestResult: false,
      testResult: {
        total_of_questions: '',
        total_of_answerd: '',
        total_of_correct: '',
        blind_support_file: '',
      },
    };
  },
  created() {
    this.handleCheckFlow();
  },
  methods: {
    handleCheckFlow() {
      this.overlay.show = true;

      const CHOOSE_CLASS = this.$store.getters.choose_class;

      if (CHOOSE_CLASS > 0) {
        this.handleGetAllTestByClass();
      } else {
        this.$router.push('/student-test/choose-class');
      }

      this.overlay.show = false;
    },
    async handleGetAllTestByClass() {
      try {
        const URL = URL_API.getAllTestByClass;
        const PARAMS = {
          class_id: this.$store.getters.choose_class,
          page: 1,
          per_page: 10000,
        };

        const res = await getAllTestByClass(URL, PARAMS);

        if (res) {
          this.listTest = res['data'];
        }
      } catch (error) {
        console.log(error);
      }
    },
    onClickGoToTest(id) {
      if (id) {
        this.$store.dispatch('studentTest/setChooseTest', id)
          .then(() => {
            this.$router.push('/student-test/do-test');
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    async handleViewResultTest(id) {
      try {
        const URL = URL_API.getResultTest;
        const PARAMS = {
          test_id: id,
          blind: false,
        };

        const res = await getResultTest(URL, PARAMS);

        if (res) {
          this.testResult = {
            total_of_questions: res.total_of_questions,
            total_of_answerd: res.total_of_answerd,
            total_of_correct: res.total_of_correct,
            blind_support_file: res.blind_support_file,
          };

          this.modalTestResult = true;
        }
      } catch (error) {
        console.log(error);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
  @import "@/scss/_variables";

  .student-test {
    padding: 10px;
    height: calc(100vh - 57px);

    .display-test {
      margin-bottom: 10px;
    }
  }

  .icon-loading {
    font-size: 50px;
    color: $forest-green;
  }
</style>
