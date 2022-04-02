import Layout from '@/layout';

const Course = {
  path: '/course',
  name: 'Course',
  meta: {
    title: 'ROUTER.COURSE',
    icon: 'fad fa-books',
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
