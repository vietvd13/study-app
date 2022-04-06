import Notification from '../notification';
import i18n from '@/lang';

export default {
  addSuccess(email) {
    Notification.notifySuccess(i18n.t('NOTIFY.ACCOUNT.ADD_SUCCESS', { email: email }));
  },
  addError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  updateSuccess(email) {
    Notification.notifySuccess(i18n.t('NOTIFY.ACCOUNT.UPDATE_SUCCESS', { email: email }));
  },
  updateError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  deleteSuccess() {
    Notification.notifySuccess('NOTIFY.ACCOUNT.DELETE_SUCCESS');
  },
  deleteError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  validateForm(message) {
    Notification.notifyWarning(i18n.t(message));
  },
  exception() {
    Notification.notifyError(i18n.t('NOTIFY.SYSTEM.EXCEPTION'));
  },
  server(message) {
    Notification.notifyWarning(message);
  },
};

