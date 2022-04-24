import Layout from '@/layout';

const StudentTest = {
  path: '/student-test',
  name: 'StudentTest',
  meta: {
    title: 'ROUTER.STUDENT_TEST',
    icon: 'fad fa-book-reader',
    roles: ['ADMIN', 'STUDENT'],
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
      component: () => import(/* webpackChunkName: "ChooseClass" */ '@/pages/StudentTest/ChooseClass/index'),
    },
    {
      path: 'choose-test',
      name: 'ChooseTest',
      meta: {
        title: 'ROUTER.CHOOSE_TEST',
      },
      component: () => import(/* webpackChunkName: "ChooseTest" */ '@/pages/StudentTest/ChooseTest/index'),
    },
  ],
};

export default StudentTest;
