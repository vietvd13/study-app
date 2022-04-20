import Layout from '@/layout';

const Course = {
  path: '/course',
  name: 'Course',
  meta: {
    title: 'ROUTER.COURSE',
    icon: 'fad fa-books',
    roles: ['ADMIN', 'TEACHER'],
  },
  component: Layout,
  redirect: { name: 'CourseIndex' },
  children: [
    {
      path: 'index',
      name: 'CourseIndex',
      meta: {
        title: 'ROUTER.COURSE',
      },
      component: () => import(/* webpackChunkName: "Course" */ '@/pages/Course/index'),
    },
  ],
};

export default Course;
