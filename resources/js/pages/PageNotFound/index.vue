<template>
  <div class="page-not-found">
    <b-container>
      <div class="display-page-not-found col-xl-10 col-lg-10 mx-auto">
        <div class="display-page-not-found__image">
          <b-img fluid center :src="ImagePageNotFound" alt="Page Not Found" />
        </div>

        <div class="display-page-not-found__detail">
          <h2>{{ $t('PAGE_NOT_FOUND.DETAIL') }}</h2>
        </div>

        <div class="display-page-not-found__control">
          <b-button class="btn-go-to-home btn-custom-green" @click="goToHome()">
            <i class="fas fa-home-lg" />
            {{ $t('PAGE_NOT_FOUND.GO_TO_HOME') }}
          </b-button>
        </div>
      </div>
    </b-container>
  </div>
</template>

<script>
import ImagePageNotFound from '@/assets/images/404.png';

export default {
  name: 'PageNotFound',
  data() {
    return {
      ImagePageNotFound,
    };
  },
  methods: {
    goToHome() {
      this.$router.push('/');
    },
  },
};
</script>

<style lang="scss" scoped>
	@import '@/scss/_variables';
	@import '@/scss/modules/_PageNotFound';
</style>
