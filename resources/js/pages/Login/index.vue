<template>
    <div class="page-login">
        <b-container>
            <div class="login-form col-xl-6 col-lg-6 mx-auto">
                <div class="login-form-content">
                    <b-row>
                        <b-col>
                            <div class="login-form-content__logo">
                                <b-img center :src="Logo" alt="Logo"></b-img>
                            </div>

                            <!-- Title -->
                            <div class="login-form-content__header">
                                <h1>{{ $t('LOGIN.TITLE') }}</h1>
                            </div>

                            <!-- Account -->
                            <div class="login-form-content__body">
                                <div class="item-input">
                                    <b-form-input
                                        v-model="User.account"
                                        type="text"
                                        spellcheck="false"
                                        :placeholder="$t('LOGIN.PLACEHOLDER_ACCOUNT')"
                                    />
                                </div>

                                <div class="item-input">
                                    <b-input-group>
                                        <b-form-input
                                            v-model="User.password"
                                            :type="showPassword ? 'text' : 'password'"
                                            spellcheck="false"
                                            autocomplete="new-password"
                                            :placeholder="$t('LOGIN.PLACEHOLDER_PASSWORD')"
                                        />
                                        <b-input-group-append is-text>
                                            <i @click="showPassword = !showPassword" :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                        </b-input-group-append>
                                    </b-input-group>
                                </div>
                            </div>

                            <div class="login-form-content__footer">
                                <b-row>
                                    <b-col>
                                        <b-button>{{ $t('LOGIN.BUTTON_LOGIN') }}</b-button>
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
import Logo from '@/assets/images/student.png';

export default {
    name: 'Login',
    data() {
        return {
            Logo,
            User: {
                account: '',
                password: '',
            },
            showPassword: false,
            rememberMe: false,
        };
    },
};
</script>

<style lang="scss" scoped>
@import '@/scss/_variables';
@import '@/scss/modules/_login';
</style>
