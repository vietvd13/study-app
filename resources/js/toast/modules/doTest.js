import Notification from '../notification';
import i18n from '@/lang';

export default {
  submitAnswerSuccess(message) {
    Notification.notifySuccess(message);
  },
  validateSubmitAnswer() {
    Notification.notifyWarning(i18n.t('NOTIFY.DO_TEST.SUMIT_ANSWER_VALIDATE'));
  },
  noSupportBlind() {
    Notification.notifyWarning(i18n.t('NOTIFY.DO_TEST.NO_SUPPORT_BLIND'));
  },
  exception() {
    Notification.notifyError(i18n.t('NOTIFY.SYSTEM.EXCEPTION'));
  },
  server(message) {
    Notification.notifyWarning(message);
  },
};
