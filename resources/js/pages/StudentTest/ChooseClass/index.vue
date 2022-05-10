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

    <div class="choose-class">
      <b-col>
        <b-row v-show="listClass.length === 0">
          <b-col>
            <div class="text-center">
              {{ $t('CHOOSE_TEST.TEXT_NO_DATA') }}
            </div>
          </b-col>
        </b-row>
        <b-row v-show="listClass.length > 0">
          <template v-for="item in listClass">
            <b-col :key="item.id" cols="12" sm="12" md="6" lg="6" xl="3">
              <div class="display-class">
                <b-card>
                  <template #header>
                    <h6 class="mb-0 text-center">{{ $t('CHOOSE_CLASS.TEXT_CLASS') }}</h6>
                  </template>
                  <div class="text-center">{{ item['name'] }}</div>
                  <template #footer>
                    <b-button block class="btn-custom-green" @click="onClickGoToClass(item['id'])">
                      <i class="far fa-paper-plane" style="margin-right: 5px" />
                      {{ $t('CHOOSE_CLASS.TEXT_GO_TO_CLASS') }}
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
  getAllClassStudent: '/classes/student/list',
};

import { getAllClassStudent } from '@/api/modules/choose_class';

export default {
  name: 'ChooseClass',
  data() {
    return {
      overlay: {
        show: false,
        variant: 'light',
        opacity: 1,
        blur: '1rem',
        rounded: 'sm',
      },

      listClass: [],
    };
  },
  created() {
    this.handleGetAllClassStudent();
  },
  methods: {
    async handleGetAllClassStudent() {
      this.overlay.show = true;
      try {
        const URL = URL_API.getAllClassStudent;
        const PARAMS = {
          page: 1,
          per_page: 10000,
        };

        const res = await getAllClassStudent(URL, PARAMS);

        if (res) {
          this.listClass = res['data'];
          this.overlay.show = false;
        }
      } catch (error) {
        this.overlay.show = false;
        console.log(error);
      }
    },
    onClickGoToClass(id) {
      if (id > 0) {
        this.$store.dispatch('studentTest/setChooseClass', id)
          .then(() => {
            this.$router.push('/student-test/choose-test');
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

  .choose-class {
    padding: 10px;
    height: calc(100vh - 57px);

    .display-class {
        margin-bottom: 10px;
    }
  }

  .icon-loading {
    font-size: 50px;
    color: $forest-green;
  }
</style>
