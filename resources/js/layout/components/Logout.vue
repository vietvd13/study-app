<template>
  <div class="display-item">
    <b-button class="btn-custom-green" @click="doLogout()">
      {{ name }}
    </b-button>
  </div>
</template>

<script>
import NotifyLogout from '@/toast/modules/logout';

export default {
  name: 'Logout',
  computed: {
    name() {
      return this.$store.getters.name;
    },
  },
  methods: {
    doLogout() {
      this.$store.dispatch('auth/doLogout')
        .then(() => {
          this.$router.push('/login');
          NotifyLogout.logoutSuccess();
        })
        .catch(() => {
          NotifyLogout.exception();
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.display-item {
    height: 40px;

    .btn-custom-green {
        height: 100%;
        min-width: 150px;
    }
}

@media (max-width: 991px) {
    .display-item {
        display: flex;
        .btn-custom-green {
            width: 100%;
        }
    }
}
</style>
