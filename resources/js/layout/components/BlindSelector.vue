<template>
  <b-nav-item-dropdown right class="display-item">
    <template #button-content>
      <span v-if="blind === '1'" class="text-btn">{{ $t('NAVBAR.BLIND_MODE') }}: {{ $t('NAVBAR.ON') }}</span>
      <span v-if="blind === '0'" class="text-btn">{{ $t('NAVBAR.BLIND_MODE') }}: {{ $t('NAVBAR.OFF') }}</span>
    </template>

    <b-dropdown-item href="#" @click="setBlindMode('1')">
      {{ $t('NAVBAR.ON') }}
    </b-dropdown-item>
    <b-dropdown-item href="#" @click="setBlindMode('0')">
      {{ $t('NAVBAR.OFF') }}
    </b-dropdown-item>
  </b-nav-item-dropdown>
</template>

<script>
import { MakeToast } from '@/toast/toastMessage';

export default {
  name: 'BlindSelect',
  computed: {
    blind() {
      return this.$store.getters.blind;
    },
  },
  methods: {
    setBlindMode(status) {
      if (['0', '1'].includes(status)) {
        this.$store.dispatch('blind/setBlind', status)
          .then(() => {
            const message = {
              variant: 'success',
              title: this.$t('TOAST.SUCCESS'),
            };

            if (this.blind === '0') {
              message.content = this.$t('NAVBAR.MESSAGE_OFF_BLIND');
            }

            if (this.blind === '1') {
              message.content = this.$t('NAVBAR.MESSAGE_ON_BLIND');
            }

            MakeToast(message);
          })
          .catch(() => {
            MakeToast({
              variant: 'danger',
              title: this.$t('TOAST.DANGER'),
              content: this.$t('NAVBAR.MESSAGE_CHANGE_BLIND_ERROR'),
            });
          });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.text-btn {
    margin-right: 7px;
}
</style>
