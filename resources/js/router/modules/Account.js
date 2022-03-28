import Layout from '@/layout';

const Account = {
	path: '/account',
	name: 'Account',
	meta: {
		title: 'ROUTER.ACCOUNT',
		icon: 'fas fa-user-alt'
	},
	component: Layout,
	redirect: { name: 'AccountIndex' },
	children: [
		{
			path: 'index',
			name: 'AccountIndex',
			meta: {
				title: 'ROUTER.ACCOUNT'
			},
			component: () => import(/* webpackChunkName: "AccountIndex" */ '@/pages/Account/index')
		}
	]
};

export default Account;
