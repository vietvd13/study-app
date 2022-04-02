<template>
  <div id="sidebar-wrapper">
    <MenuSidebar class="sidebar-menu" :routes="routes" />
  </div>
</template>

<script>
import MenuSidebar from './MenuSidebar.vue';

export default {
  name: 'Sidebar',
  components: {
    MenuSidebar,
  },
  computed: {
    routes() {
      return this.$router.options.routes.filter(item => item.hidden !== true);
    },
  },
};
</script>

<style lang="scss" scoped>
	@import '@/scss/variables.scss';
	@import '@/scss/modules/_layout.scss';
</style>
