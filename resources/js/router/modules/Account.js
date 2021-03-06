import Layout from '@/layout';

const Account = {
  path: '/account',
  name: 'Account',
  meta: {
    title: 'ROUTER.ACCOUNT',
    icon: 'fad fa-user-alt',
    roles: ['ADMIN'],
  },
  component: Layout,
  redirect: { name: 'AccountIndex' },
  children: [
    {
      path: 'index',
      name: 'AccountIndex',
      meta: {
        title: 'ROUTER.ACCOUNT',
      },
      component: () => import(/* webpackChunkName: "Account_Index" */ '@/pages/Account/index'),
    },
  ],
};

export default Account;
