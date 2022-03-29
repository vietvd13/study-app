<template>
	<div id="wrapper" :class="toggle">
		<Sidebar />
		<div id="page-content-wrapper">
			<Navbar @toggle="toggleMenu()" />
			<div class="app-main">
				<AppMain />
			</div>
		</div>
	</div>
</template>

<script>
	import Navbar from './components/Navbar';
	import Sidebar from './components/Sidebar';
	import AppMain from './components/AppMain';

	export default {
		name: 'Layout',
		components: {
			Navbar,
			Sidebar,
			AppMain
		},
		data() {
			return {
				toggle: '',
				toggleBool: false
			};
		},
		methods: {
			toggleMenu() {
				this.toggleBool = !this.toggleBool;
				if (this.toggleBool === true) {
					this.toggle = 'toggled';
				} else {
					this.toggle = '';
				}
			}
		}
	};
</script>

<style lang="scss">
	@import '@/scss/_variables.scss';
	@import '@/scss/modules/_layout.scss';

	#wrapper {
		#page-content-wrapper {
			.app-main {
				background-color: $seashell;
			}
		}
	}
</style>
