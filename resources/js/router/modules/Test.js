import Layout from '@/layout';

const Test = {
  path: '/test',
  name: 'Test',
  meta: {
    title: 'ROUTER.TEST',
    icon: 'fas fa-book',
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
      component: () => import(/* webpackChunkName: "TestIndex" */ '@/pages/Test/index'),
    },
  ],
};

export default Test;
