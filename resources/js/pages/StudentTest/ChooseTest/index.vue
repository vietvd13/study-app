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
                  <div class="text-center">{{ item['course_id'] }}</div>
                  <template #footer>
                    <b-button block class="btn-custom-green" @click="onClickGoToTest(item['id'])">
                      <i class="far fa-paper-plane" style="margin-right: 5px" />
                      {{ $t('CHOOSE_TEST.TEXT_GO_TO_TEST') }}
                    </b-button>
                  </template>
                </b-card>
              </div>
            </b-col>
          </template>
        </b-row>
      </b-col>
    </div>
  </b-overlay>
</template>

<script>

const URL_API = {
  getAllTestByClass: '/test/student/list-test',
};

import { getAllTestByClass } from '@/api/modules/choose_test';

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
      console.log(id);
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
</style>
