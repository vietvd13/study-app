import Notification from '../notification';
import i18n from '@/lang';

export default {
  addSuccess(name) {
    Notification.notifySuccess(i18n.t('NOTIFY.CLASSES.ADD_SUCCESS', { name: name }));
  },
  addError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  updateSuccess(name) {
    Notification.notifySuccess(i18n.t('NOTIFY.CLASSES.UPDATE_SUCCESS', { name: name }));
  },
  updateError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  deleteSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.CLASSES.DELETE_SUCCESS'));
  },
  deleteError(error) {
    Notification.notifyError(error['response']['data']['message']);
  },
  validateForm(message) {
    Notification.notifyWarning(i18n.t(message));
  },
  validateCreateActivity() {
    Notification.notifyWarning(i18n.t('NOTIFY.CLASSES.VALIDATE_CREATE_ACTIVITY'));
  },
  assignStudentSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.CLASSES.ASSIGN_STUDENT_SUCCESS'));
  },
  assignCourseSuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.CLASSES.ASSIGN_COURSE_SUCCESS'));
  },
  createActivitySuccess() {
    Notification.notifySuccess(i18n.t('NOTIFY.CLASSES.CREATE_ACTIVITY_SUCCESS'));
  },
  exception() {
    Notification.notifyError(i18n.t('NOTIFY.SYSTEM.EXCEPTION'));
  },
  server(message) {
    Notification.notifyWarning(message);
  },
};
