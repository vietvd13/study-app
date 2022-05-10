const PageNotFound = {
  path: '/page-not-found',
  name: 'PageNotFound',
  meta: {
    title: 'ROUTER.PAGE_NOT_FOUND',
    icon: '',
  },
  hidden: true,
  component: () =>
    import(/* webpackChunkName: "Page_Not_Found" */ '@/pages/PageNotFound/index.vue'),
};

export default PageNotFound;
