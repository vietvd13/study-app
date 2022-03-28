<template>
	<div class="view-page">
		<router-view :key="key" />
	</div>
</template>

<script>
	export default {
		name: 'AppMain',
		computed: {
			key() {
				return this.$route.fullPath;
			}
		}
	};
</script>

<style lang="scss" scoped>
	.view-page {
		margin: 10px;
	}
</style>
