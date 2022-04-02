<template>
  <div>
    <ul class="display-menu">
      <div class="zone-app-name">
        <h4>{{ appName }}</h4>
      </div>
      <router-link
        v-for="(itemRouter, indexRouter) in routes"
        :key="indexRouter"
        :to="itemRouter.path"
      >
        <li :class="'item ' + 'router-item-' + indexRouter">
          <i :class="itemRouter.meta.icon" />
          <span>{{ $t(itemRouter.meta.title) }}</span>
        </li>
      </router-link>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'MenuSidebar',
  props: {
    routes: {
      type: Array,
      require: true,
      default: function() {
        return [];
      },
    },
  },
  computed: {
    appName() {
      return process.env.MIX_APP_TITLE;
    },
  },
};
</script>

<style lang="scss" scoped>
	@import '@/scss/_variables.scss';
	@import '@/scss/modules/_layout.scss';
</style>
