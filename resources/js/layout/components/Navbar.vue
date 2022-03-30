<template>
	<div class="nav-bar">
		<b-navbar toggleable="lg" type="dark">
			<b-navbar-brand @click.prevent.stop="$emit('toggle')">
				<i id="toggle-menu" class="fas fa-bars" />
			</b-navbar-brand>

			<b-navbar-toggle target="nav-collapse">
				<template #default="{ expanded }">
					<i v-if="expanded" class="fas fa-angle-up" />
					<i v-else class="fas fa-angle-down" />
				</template>
			</b-navbar-toggle>

			<b-collapse id="nav-collapse" is-nav>
				<b-navbar-nav>
					<span class="app-name">{{ $t(titleRoute) }}</span>
				</b-navbar-nav>

				<b-navbar-nav class="ml-auto">
					<LangSelector />
				</b-navbar-nav>
			</b-collapse>
		</b-navbar>
	</div>
</template>

<script>
	import LangSelector from './LangSelector';

	export default {
		name: 'Navbar',
		components: {
			LangSelector
		},
		computed: {
			titleRoute() {
				return this.$route.meta.title;
			}
		}
	};
</script>

<style lang="scss" scoped>
	@import '@/scss/_variables';
	@import '@/scss/modules/_layout.scss';
</style>
