import { MakeToast } from './toastMessage';
import i18n from '@/lang';

const VARIANT = {
  SUCCESS: 'success',
  WARNING: 'warning',
  DANGER: 'danger',
};

const TITLE = {
  SUCCESS: 'TOAST.SUCCESS',
  WARNING: 'TOAST.WARNING',
  DANGER: 'TOAST.DANGER',
};

export default {
  notifySuccess(content) {
    MakeToast({
      variant: VARIANT.SUCCESS,
      title: i18n.t(TITLE.SUCCESS),
      content: content,
    });
  },

  notifyWarning(content) {
    MakeToast({
      variant: VARIANT.WARNING,
      title: i18n.t(TITLE.WARNING),
      content: content,
    });
  },

  notifyError(content) {
    MakeToast({
      variant: VARIANT.DANGER,
      title: i18n.t(TITLE.DANGER),
      content: content,
    });
  },
};

