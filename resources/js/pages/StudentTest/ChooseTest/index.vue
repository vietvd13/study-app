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
          <b-col />
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

        console.log(res);
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
    }
</style>
