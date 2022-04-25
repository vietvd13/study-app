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
    </div>

  </b-overlay>
</template>

<script>

const URL_API = {
  getOneTest: '/test/student/detail',
};

import { getOneTest } from '@/api/modules/do_test';

import { playSound, clearSound } from '@/speak/sound';
import { getBlind } from '@/speak/helper';

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
    };
  },
  created() {
    this.handleCheckFlow();

    window.addEventListener('keydown', (event) => {
      if (getBlind() && this.overlay.show === false) {
        if (KEY.up.code === event.code && KEY.up.keyCode === event.keyCode) {
          console.log('Up');

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
        }

        if (KEY.down.code === event.code && KEY.down.keyCode === event.keyCode) {
          console.log('Down');

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

        console.log(res);

        if (res) {
          this.listQuestions = res['test']['questions'];
          this.listQuestions = this.addKeyAnswer(this.listQuestions);

          this.blindSupport = res['test']['blind_support'];
          this.current_question = -1;
        }

        this.overlay.show = false;
      } catch (error) {
        this.overlay.show = false;
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
</style>
