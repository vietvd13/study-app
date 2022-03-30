<template>
	<b-nav-item-dropdown right>
		<template #button-content>
			{{ $t('NAVBAR.LANGUAGE') }}
		</template>
		<b-dropdown-item href="#" @click="setLanguage('en')">
			{{ $t('NAVBAR.ENGLISH') }}
		</b-dropdown-item>
		<b-dropdown-item href="#" @click="setLanguage('vn')">
			{{ $t('NAVBAR.VIETNAMESE') }}
		</b-dropdown-item>
	</b-nav-item-dropdown>
</template>

<script>
	import { MakeToast } from '@/toast/toastMessage';

	export default {
		name: 'LangSelector',
		methods: {
			setLanguage(lang) {
				this.$store
					.dispatch('app/setLanguage', lang)
					.then(() => {
						this.$i18n.locale = lang;

						MakeToast({
							variant: 'success',
							title: this.$t('TOAST.SUCCESS'),
							content: this.$t('I18N.CHANGE_LANGUAGE.SUCCESS')
						});
					})
					.catch(() => {
						MakeToast({
							variant: 'danger',
							title: this.$t('TOAST.DANGER'),
							content: this.$t('I18N.CHANGE_LANGUAGE.FAILED')
						});
					});
			}
		}
	};
</script>
