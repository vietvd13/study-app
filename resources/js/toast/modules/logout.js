import Notification from '../notification';
import i18n from '@/lang';

export default {
  logoutSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.LOGOUT.LOGOUT_SUCCESS'));
  },
  exception() {
    Notification.notifyError(i18n.t('NOTIFY.SYSTEM.EXCEPTION'));
  },
  server(message) {
    Notification.notifyWarning(message);
  },
};
