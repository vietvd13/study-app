import Notification from '../notification';
import i18n from '@/lang';

export default {
  loginSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.LOGIN.LOGIN_SUCCESS'));
  },
  validateEmail() {
    Notification.notifyWarning(i18n.t('NOTIFY.LOGIN.VALIDATE_EMAIL'));
  },
  validatePassword() {
    Notification.notifyWarning(i18n.t('NOTIFY.LOGIN.VALIDATE_PASSWORD'));
  },
  exception() {
    Notification.notifyError(i18n.t('NOTIFY.SYSTEM.EXCEPTION'));
  },
  server(message) {
    Notification.notifyWarning(message);
  },
};
