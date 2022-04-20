import router from '@/router';
import store from '@/store';
import { isLogged } from '@/utils/auth';
import getPageTitle from '@/utils/getPageTitle';
import { setRoutes } from '@/utils/setRoutes';

const WHITE_LIST = ['/login'];

router.beforeEach(async(to, from, next) => {
  document.title = getPageTitle(to.meta.title);

  const isUserLogged = isLogged();

  if (isUserLogged) {
    if (to.path === '/login') {
      next({ path: '/' });
    } else {
      const HAS_ROLES = store.getters.roles && store.getters.roles.length > 0;

      if (HAS_ROLES) {
        next();
      } else {
        try {
          const RES = await store.dispatch('auth/getUserInfo');
          const ROLES = RES['roles'];
          const ACCESS_ROUTES = await store.dispatch('permissions/generateRoutes', {
            roles: ROLES,
            permissions: [],
          });

          setRoutes(ACCESS_ROUTES);

          next({ ...to, replace: true });
        } catch (err) {
          console.log(err);
          await store.dispatch('auth/resetToken');
          next(`/login`);
        }
      }
    }
  } else {
    if (WHITE_LIST.indexOf(to.matched[0] ? to.matched[0].path : '') !== -1) {
      next();
    } else {
      next(`/login`);
    }
  }
});
