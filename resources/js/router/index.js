import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Login from './modules/Login';
import Dashboard from './modules/Dashboard';
import Account from './modules/Account';
import Classes from './modules/Classes';
import Course from './modules/Course';
import Test from './modules/Test';
import PageNotFound from './modules/PageNotFound';

export const constantRoutes = [
  Login,
  {
    path: '/',
    redirect: { name: 'Dashboard' },
    hidden: true,
  },
  Dashboard,
];

export const asyncRoutes = [
  Account,
  Classes,
  Course,
  Test,
  PageNotFound,
  {
    path: '*',
    hidden: true,
    redirect: { name: 'PageNotFound' },
  },
];

const createRouter = () =>
  new VueRouter({
    scrollBehavior: () => ({ y: 0 }),
    routes: constantRoutes,
  });

const router = createRouter();

export function resetRouter() {
  const newRouter = createRouter();
  router.matcher = newRouter.matcher;
}

export default router;
