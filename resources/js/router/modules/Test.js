import Layout from '@/layout';

const Test = {
  path: '/test',
  name: 'Test',
  meta: {
    title: 'ROUTER.TEST',
    icon: 'fas fa-book',
    roles: ['ADMIN', 'TEACHER'],
  },
  component: Layout,
  redirect: { name: 'TestIndex' },
  children: [
    {
      path: 'index',
      name: 'TestIndex',
      meta: {
        title: 'ROUTER.TEST',
      },
      component: () => import(/* webpackChunkName: "Test_Index" */ '@/pages/Test/index'),
    },
  ],
};

export default Test;
