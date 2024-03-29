<template>
  <div class="page-login">
    <b-container>
      <div class="login-form col-xl-6 col-lg-6 mx-auto">
        <div class="login-form-content">
          <b-row>
            <b-col>
              <!-- Logo -->
              <div class="login-form-content__logo">
                <b-img center :src="Logo" alt="Logo" />
              </div>

              <!-- Title -->
              <div class="login-form-content__header">
                <h1>{{ $t('LOGIN.TITLE') }}</h1>
              </div>

              <!-- Account -->
              <div class="login-form-content__body">
                <div class="item-input">
                  <b-form-input
                    v-model="User.email"
                    type="text"
                    :placeholder="$t('LOGIN.PLACEHOLDER_ACCOUNT')"
                    spellcheck="false"
                    autofocus
                    :disabled="isProcess"
                    @keyup.enter="doLogin()"
                  />
                </div>

                <div class="item-input">
                  <b-input-group>
                    <b-form-input
                      v-model="User.password"
                      :type="showPassword ? 'text' : 'password'"
                      :placeholder="$t('LOGIN.PLACEHOLDER_PASSWORD')"
                      spellcheck="false"
                      name="password"
                      autocomplete="off"
                      :disabled="isProcess"
                      @keyup.enter="doLogin()"
                    />
                    <b-input-group-append v-if="User.password" is-text>
                      <i
                        :disabled="isProcess"
                        :class="handleShowPassword()"
                        @click="showPassword = !showPassword"
                      />
                    </b-input-group-append>
                  </b-input-group>
                </div>
              </div>

              <div class="login-form-content__footer">
                <b-row>
                  <b-col>
                    <b-button
                      class="btn-custom-green"
                      :disabled="isProcess"
                      @click="doLogin()"
                    >
                      <i
                        v-if="isProcess"
                        class="fad fa-spinner-third fa-spin"
                      />
                      {{ $t('LOGIN.BUTTON_LOGIN') }}
                    </b-button>
                  </b-col>
                </b-row>
              </div>
            </b-col>
          </b-row>
        </div>
      </div>
    </b-container>
  </div>
</template>

<script>
import Logo from '@/assets/images/logo.png';
import { validEmail, validPassword } from '@/utils/validate';
import { setRoutes } from '@/utils/setRoutes';

import { getCSRF } from '@/api/modules/auth';
const URL_API = {
  urlGetCSRF: '/api/csrf-cookie',
};

import NotifyLogin from '@/toast/modules/login';

export default {
  name: 'Login',
  data() {
    return {
      Logo,
      User: {
        email: '',
        password: '',
      },
      showPassword: false,
      isProcess: false,
    };
  },
  methods: {
    handleShowPassword() {
      if (this.isProcess) {
        this.showPassword = false;
      }

      if (this.showPassword) {
        return 'fas fa-eye-slash';
      }

      return 'fas fa-eye';
    },

    doLogin() {
      this.isProcess = true;

      const Account = {
        email: this.User.email || '',
        password: this.User.password || '',
      };

      if (this.validateLogin(Account)) {
        this.callApiLogin(Account);
      }

      this.isProcess = false;
    },

    callApiLogin(Account) {
      getCSRF(URL_API['urlGetCSRF'])
        .then(() => {
          this.$store.dispatch('auth/doLogin', Account)
            .then(() => {
              const ROLES = this.$store.getters.roles;
              this.$store.dispatch(
                'permissions/generateRoutes',
                {
                  roles: ROLES,
                  permissions: [],
                }
              ).then((ACCESS_ROUTES) => {
                setRoutes(ACCESS_ROUTES);
                this.$router.push('/');
                NotifyLogin.loginSuccess();
              });
            });
        })
        .catch((err) => {
          console.log(err);
        });
    },

    validateLogin(Account) {
      if (validEmail(Account.email) === false) {
        NotifyLogin.validEmail();

        return false;
      }

      if (validPassword(Account.password) === false) {
        NotifyLogin.validPassword();

        return false;
      }

      return true;
    },
  },
};
</script>

<style lang="scss" scoped>
	@import '@/scss/_variables';
	@import '@/scss/modules/_login';
</style>
