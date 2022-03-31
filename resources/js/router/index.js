import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Login from './modules/Login';
import Dashboard from './modules/Dashboard';
import Account from './modules/Account';
import Test from './modules/Test';
import PageNotFound from './modules/PageNotFound';

export const constantRoutes = [
	{
		path: '/',
		hidden: true,
		redirect: { name: 'Dashboard' }
	},
	Login,
	Dashboard,
	Account,
	Test,
	PageNotFound,
	{
		path: '*',
		hidden: true,
		redirect: { name: 'PageNotFound' }
	}
];

export const asyncRoutes = [];

const createRouter = () =>
	new VueRouter({
		scrollBehavior: () => ({ y: 0 }),
		routes: constantRoutes
	});

const router = createRouter();

export function resetRouter() {
	const newRouter = createRouter();
	router.matcher = newRouter.matcher;
}

export default router;
