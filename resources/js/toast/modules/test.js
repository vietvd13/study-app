import Notification from '../notification';
import i18n from '@/lang';

export default {
  addSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.TEST.ADD_SUCCESS'));
  },
  addError() {
    Notification.notifyError(i18n.t('NOTIFY.TEST.ADD_FAILD'));
  },
  updateSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.TEST.UPDATE_SUCCESS'));
  },
  updateError() {
    Notification.notifyError(i18n.t('NOTIFY.TEST.UPDATE_FAILED'));
  },
  validateForm() {
    Notification.notifyWarning(i18n.t('NOTIFY.TEST.VALIDATE_FORM'));
  },
  exception() {
    Notification.notifyError(i18n.t('NOTIFY.SYSTEM.EXCEPTION'));
  },
  server(message) {
    Notification.notifyWarning(message);
  },
};
