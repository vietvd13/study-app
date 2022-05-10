import Layout from '@/layout';

const StudentTest = {
  path: '/student-test',
  name: 'StudentTest',
  meta: {
    title: 'ROUTER.STUDENT_TEST',
    icon: 'fad fa-book-reader',
    roles: ['STUDENT'],
  },
  component: Layout,
  redirect: { name: 'ChooseClass' },
  children: [
    {
      path: 'choose-class',
      name: 'ChooseClass',
      meta: {
        title: 'ROUTER.CHOOSE_CLASS',
      },
      component: () => import(/* webpackChunkName: "Choose_Class" */ '@/pages/StudentTest/ChooseClass/index'),
    },
    {
      path: 'choose-test',
      name: 'ChooseTest',
      meta: {
        title: 'ROUTER.CHOOSE_TEST',
      },
      component: () => import(/* webpackChunkName: "Choose_Test" */ '@/pages/StudentTest/ChooseTest/index'),
    },
    {
      path: 'do-test',
      name: 'DoTest',
      meta: {
        title: 'ROUTER.DO_TEST',
      },
      component: () => import(/* webpackChunkName: "Do_Test" */ '@/pages/StudentTest/DoTest/index'),
    },
  ],
};

export default StudentTest;
