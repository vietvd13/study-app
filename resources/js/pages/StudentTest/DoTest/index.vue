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

    <div class="do-test">
      <b-col>
        <b-row>
          <b-col cols="12" sm="12" md="12" lg="12" xl="8">
            <div class="display-question">
              <div v-for="(question, idxQuestion) in listQuestions" :id="`question-${idxQuestion + 1}`" :key="question.id" class="question">
                <b-card>
                  <div class="text-question">
                    <span>
                      <i
                        v-show="blindSupport"
                        class="fad fa-list-music icon-play-sound"
                        @click="handlePlaySound(question.question_audio)"
                      />
                      <b>{{ $t('DO_TEST.TEXT_QUESTION') }} {{ idxQuestion + 1 }}: </b>
                      {{ question.question }}
                    </span>
                  </div>

                  <div class="text-answer">
                    <b-form-group>
                      <b-form-radio-group
                        v-model="listQuestions[idxQuestion]['current_answer']"
                        stacked
                      >
                        <b-form-radio
                          v-for="answer in question.answers"
                          :key="answer.id"
                          :name="answer.answer"
                          :value="answer.id"
                        >
                          {{ answer.answer }}
                        </b-form-radio>
                      </b-form-radio-group>
                    </b-form-group>
                  </div>
                </b-card>
              </div>
            </div>
          </b-col>

          <b-col cols="12" sm="12" md="12" lg="12" xl="4">
            <div class="display-current-test">
              <b-card>
                <b-row>
                  <template v-for="number in listQuestions.length">
                    <b-col :key="number" cols="3" sm="3" md="3" lg="3" xl="3">
                      <div class="d-flex justify-content-center">
                        <span :class="{ 'number-test': true, 'active': listQuestions[number - 1]['current_answer'] }">{{ number }}</span>
                      </div>
                    </b-col>
                  </template>
                </b-row>
              </b-card>
            </div>
          </b-col>
        </b-row>
      </b-col>

      <b-col>
        <b-row>
          <b-col cols="12" sm="12" md="12" lg="12" xl="8">
            <b-button block class="btn-custom-green" @click="handleSubmit()">{{ $t('DO_TEST.SUBMIT') }}</b-button>
          </b-col>
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
        <b-button class="btn-custom-green" @click="goToDashboard()">{{ $t('DO_TEST.BUTTON_GO_TO_DASHBOARD') }}</b-button>
      </template>
    </b-modal>

  </b-overlay>
</template>

<script>

const URL_API = {
  getOneTest: '/test/student/detail',
  postAnswerTest: '/test/student/answer',
  getResultTest: '/test/student/view-grade',
};

import { getOneTest, postAnswerTest, getResultTest } from '@/api/modules/do_test';

import { playSound, clearSound } from '@/speak/sound';
import { getBlind } from '@/speak/helper';

import NotifyDoTest from '@/toast/modules/doTest';

import { validateSubmitAnswer } from './validate';

import CONST_CONTROL_VOICE from '@/speak/const';

import { sleep } from '@/utils/sleep';

const KEY = {
  up: {
    keyCode: 38,
    code: 'ArrowUp',
  },
  down: {
    keyCode: 40,
    code: 'ArrowDown',
  },
};

export default {
  name: 'DoTest',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      listQuestions: [],
      blindSupport: 0,
      current_question: null,

      modalTestResult: false,
      testResult: {
        total_of_questions: '',
        total_of_answerd: '',
        total_of_correct: '',
        blind_support_file: '',
      },
    };
  },
  computed: {
    controlQuestion() {
      return this.$store.getters.controlQuestion;
    },
    controlChooseAnswer() {
      return this.$store.getters.controlChooseAnswer;
    },
    controlSubmit() {
      return this.$store.getters.submitTest;
    },
  },
  watch: {
    controlQuestion: {
      handler: function() {
        const CONTROL_QUESTION = this.$store.getters.controlQuestion;

        if (CONTROL_QUESTION['action'] === 'next_question') {
          if (this.blindSupport) {
            this.handleNextQuestion();
          } else {
            if (getBlind()) {
              clearSound();
              playSound(CONST_CONTROL_VOICE['SOUND_NO_SUPPORT_BLIND']);
            }
            NotifyDoTest.noSupportBlind();
          }
        }

        if (CONTROL_QUESTION['action'] === 'back_question') {
          if (this.blindSupport) {
            this.handleBackQuestion();
          } else {
            if (getBlind()) {
              clearSound();
              playSound(CONST_CONTROL_VOICE['SOUND_NO_SUPPORT_BLIND']);
            }
            NotifyDoTest.noSupportBlind();
          }
        }
      },
      deep: true,
    },
    controlChooseAnswer: {
      handler: function() {
        const CHOOSE_ANSWER = this.$store.getters.controlChooseAnswer;

        if (this.current_question >= 0) {
          const ANSWER = CHOOSE_ANSWER.answer - 1;
          const MAX_ANSWER = this.listQuestions[this.current_question]['answers'].length;

          if (ANSWER >= 0 && ANSWER < MAX_ANSWER) {
            this.listQuestions[this.current_question].current_answer = this.listQuestions[this.current_question]['answers'][CHOOSE_ANSWER.answer - 1]['id'];

            if (getBlind()) {
              clearSound();
              playSound(CONST_CONTROL_VOICE['SOUND_CHOOSE_ANSWER_SUCCESS']);
            }
          } else {
            if (getBlind()) {
              clearSound();
              playSound(CONST_CONTROL_VOICE['SOUND_CHOOSE_ANSWER_NOT_CORRECT']);
            }
            NotifyDoTest.chooseAnswerNotCorrect();
          }
        }
      },
      deep: true,
    },
    controlSubmit() {
      this.handleSubmit();
    },
  },
  created() {
    this.handleCheckFlow();

    window.addEventListener('keydown', (event) => {
      if (getBlind() && this.overlay.show === false) {
        if (KEY.up.code === event.code && KEY.up.keyCode === event.keyCode) {
          if (this.blindSupport) {
            this.handleNextQuestion();
          } else {
            clearSound();
            playSound(CONST_CONTROL_VOICE['SOUND_NO_SUPPORT_BLIND']);
            NotifyDoTest.noSupportBlind();
          }
        }

        if (KEY.down.code === event.code && KEY.down.keyCode === event.keyCode) {
          if (this.blindSupport) {
            this.handleBackQuestion();
          } else {
            clearSound();
            playSound(CONST_CONTROL_VOICE['SOUND_NO_SUPPORT_BLIND']);
            NotifyDoTest.noSupportBlind();
          }
        }
      }
    });
  },
  methods: {
    handleCheckFlow() {
      this.overlay.show = true;

      const CHOOSE_TEST = this.$store.getters.choose_test;

      if (CHOOSE_TEST > 0) {
        this.handleGetOneTest();
      } else {
        this.$router.push('/student-test/choose-test');
      }

      this.overlay.show = false;
    },
    async handleGetOneTest() {
      this.overlay.show = true;

      try {
        const URL = URL_API.getOneTest;
        const ID = this.$store.getters.choose_test;
        const PARAMS = {
          test_id: ID,
        };

        const res = await getOneTest(URL, PARAMS);

        if (res) {
          this.listQuestions = res['test']['questions'];
          this.listQuestions = this.addKeyAnswer(this.listQuestions);

          this.blindSupport = res['test']['blind_support'];
          this.current_question = -1;
        }

        this.overlay.show = false;

        if (getBlind()) {
          clearSound();
          playSound(res['test']['voice_file']);
        }
      } catch (error) {
        this.overlay.show = false;

        if (getBlind()) {
          clearSound();
          playSound(CONST_CONTROL_VOICE.SOUND_SYSTEM_EXCEPTION);
        }

        NotifyDoTest.exception();
        console.log(error);
      }
    },
    addKeyAnswer(list) {
      const len = list.length;
      let idx = 0;

      while (idx < len) {
        list[idx]['current_answer'] = null;

        idx++;
      }

      return JSON.parse(JSON.stringify(list));
    },
    handlePlaySound(path) {
      clearSound();
      playSound(`/${path}`);
    },
    checkIndexQuestion() {
      return this.current_question >= 0 && this.current_question < this.listQuestions.length;
    },
    handleNextQuestion() {
      console.log('NEXT');
      if (this.checkIndexQuestion()) {
        this.current_question = this.current_question + 1;

        if (this.current_question >= this.listQuestions.length) {
          this.current_question = 0;
        }
      } else {
        this.current_question = 0;
      }

      console.log(this.current_question);

      if (this.checkIndexQuestion()) {
        this.handlePlaySound(this.listQuestions[this.current_question]['question_audio']);
      }
    },
    handleBackQuestion() {
      console.log('BACK');
      if (this.checkIndexQuestion()) {
        this.current_question = this.current_question - 1;

        if (this.current_question < 0) {
          this.current_question = this.listQuestions.length - 1;
        }
      } else {
        this.current_question = this.listQuestions.length - 1;
      }

      console.log(this.current_question);

      if (this.checkIndexQuestion()) {
        this.handlePlaySound(this.listQuestions[this.current_question]['question_audio']);
      }
    },
    async handleSubmit() {
      this.overlay.show = true;

      const DATA = [];

      const len = this.listQuestions.length;
      let idx = 0;

      while (idx < len) {
        const ANSWER = {
          question_id: this.listQuestions[idx]['id'] || null,
          answer_id: this.listQuestions[idx]['current_answer'] || null,
        };

        DATA.push(ANSWER);

        idx++;
      }

      if (validateSubmitAnswer(DATA)) {
        try {
          const URL = URL_API.postAnswerTest;
          const BODY = {
            answers: DATA,
          };

          const res = await postAnswerTest(URL, BODY);

          if (res['status'] === 200) {
            NotifyDoTest.submitAnswerSuccess(res['message']);

            try {
              await this.handleViewResultTest();
            } catch (error) {
              console.log(error);
              NotifyDoTest.exception();
            }
          }

          this.overlay.show = false;
        } catch (error) {
          this.overlay.show = false;
          console.log(error);

          if (getBlind()) {
            clearSound();
            playSound(CONST_CONTROL_VOICE.SOUND_SYSTEM_EXCEPTION);
          }
          NotifyDoTest.exception();
        }
      } else {
        this.overlay.show = false;
        if (getBlind()) {
          clearSound();
          playSound(CONST_CONTROL_VOICE.SOUND_VALIDATE_SUBMIT_TEST);
        }
        NotifyDoTest.validateSubmitAnswer();
      }
    },
    async handleViewResultTest() {
      try {
        const URL = URL_API.getResultTest;
        const PARAMS = {
          test_id: this.$store.getters.choose_test,
          blind: this.$store.getters.blind,
        };

        const res = await getResultTest(URL, PARAMS);

        await sleep(3000);

        if (res) {
          console.log(res);

          this.testResult = {
            total_of_questions: res.total_of_questions,
            total_of_answerd: res.total_of_answerd,
            total_of_correct: res.total_of_correct,
            blind_support_file: res.blind_support_file,
          };

          this.modalTestResult = true;

          if (this.testResult.blind_support_file) {
            if (getBlind()) {
              clearSound();
              playSound(this.testResult.blind_support_file);
            }
          }
        }
      } catch (error) {
        console.log(error);
        NotifyDoTest.exception();
      }
    },
    goToDashboard() {
      this.$router.push('/dashboard');
    },
  },
};
</script>

<style lang="scss" scoped>
  @import "@/scss/_variables";

  ::v-deep .do-test {
    padding: 10px;
    height: calc(100vh - 57px);

    overflow: auto;

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

        .custom-control-input:checked ~ .custom-control-label::before {
            color: $white;
            border-color: $green-haze;
            background-color: $green-haze;
        }
      }
    }

    .display-current-test {
      margin-bottom: 10px;
      position: sticky;
      top: 0;

      .number-test {
        padding: 5px 12px;
        border: 1px solid $green-haze;
        border-radius: .25rem;
        cursor: pointer;

        &:hover {
          background-color: $green-haze;
          color: $white;
        }
      }

      .active {
        border: 1px solid $green-haze;
        background-color: $green-haze;
        color: $white;
      }
    }
  }

  .modal-view-result-content {
    .text-grade {
      font-size: 70px;
    }
  }

  .icon-loading {
    font-size: 50px;
    color: $forest-green;
  }
</style>
