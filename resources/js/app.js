import Vue from 'vue';
import App from '@/pages/App.vue';

// Permission
import '@/permission';

// Router
import router from '@/router';

// Multi language
import i18n from '@/lang';

// Store
import store from '@/store';

// Event bus
import Bus from '@/eventBus';
Vue.use(Bus);

// Component library
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

// Icon Library
import '@/assets/font-awesome/css/all.min.css';

// Style
import '@/scss/_index.scss';

Vue.config.productionTip = false;

new Vue({
    el: '#app',
    router,
    store,
    i18n,
    render: h => h(App),
});
