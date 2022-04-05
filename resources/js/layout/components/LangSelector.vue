<template>
  <b-nav-item-dropdown right class="display-item">
    <template #button-content>
      <span v-if="language === 'en'" class="text-btn">{{ $t('NAVBAR.ENGLISH') }}</span>
      <span v-if="language === 'vn'" class="text-btn">{{ $t('NAVBAR.VIETNAMESE') }}</span>
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
  computed: {
    language() {
      return this.$store.getters.language;
    },
  },
  methods: {
    setLanguage(lang) {
      this.$store
        .dispatch('app/setLanguage', lang)
        .then(() => {
          this.$i18n.locale = lang;

          MakeToast({
            variant: 'success',
            title: this.$t('TOAST.SUCCESS'),
            content: this.$t('I18N.CHANGE_LANGUAGE.SUCCESS'),
          });
        })
        .catch(() => {
          MakeToast({
            variant: 'danger',
            title: this.$t('TOAST.DANGER'),
            content: this.$t('I18N.CHANGE_LANGUAGE.FAILED'),
          });
        });
    },
  },
};
</script>

<style lang="scss" scoped>
.text-btn {
    margin-right: 7px;
}
</style>
