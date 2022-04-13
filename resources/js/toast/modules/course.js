import Notification from '../notification';
import i18n from '@/lang';

export default {
  addSuccess(name) {
    Notification.notifySuccess(i18n.t('NOTIFY.COURSE.ADD_SUCCESS', { name: name }));
  },
  addError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  updateSuccess(name) {
    Notification.notifySuccess(i18n.t('NOTIFY.COURSE.UPDATE_SUCCESS', { name: name }));
  },
  updateError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  deleteSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.COURSE.DELETE_SUCCESS'));
  },
  deleteError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  assignTeacherSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.COURSE.ASSIGN_TEACHER_SUCCESS'));
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
