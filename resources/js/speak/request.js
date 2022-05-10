import { postNavigation } from '@/api/modules/navigation';
import router from '@/router';
import i18n from '@/lang';
import store from '@/store';
import { MakeToast } from '@/toast/toastMessage';
import { playSound, clearSound } from './sound';
import CONST_CONTROL_VOICE from './const';

const USER_VOICE = 'user_voice';
const URL = '/navigation/voice';

export async function handleRequestNavigation(event) {
  try {
    const DATA = new FormData();

    DATA.append(USER_VOICE, event.data);

    const response = await postNavigation(URL, DATA);

    console.log(response);
    goToScreen(response);
  } catch (error) {
    console.log(error);

    clearSound();
    playSound(CONST_CONTROL_VOICE.SOUND_SYSTEM_EXCEPTION);
    MakeToast({
      variant: 'danger',
      title: i18n.t('TOAST.DANGER'),
      content: i18n.t('NOTIFY.SYSTEM.EXCEPTION'),
    });
  }
}

function goToScreen(response) {
  const action = response.action;

  switch (action) {
    case CONST_CONTROL_VOICE['ACTION_STUDENT_LIST_TEST']: {
      router.push('/test');

      break;
    }

    case CONST_CONTROL_VOICE['ACTION_STUDENT_LIST_TEST_TODAY']: {
      router.push('/test');

      break;
    }

    case CONST_CONTROL_VOICE['ACTION_NEXT_QUESTION']: {
      let STEP = store.getters.controlQuestion.step;

      store.dispatch('studentTest/setControlQuestion', {
        step: STEP++,
        action: 'next_question',
      });

      break;
    }

    case CONST_CONTROL_VOICE['ACTION_BACK_QUESTION']: {
      let STEP = store.getters.controlQuestion.step;

      store.dispatch('studentTest/setControlQuestion', {
        step: STEP++,
        action: 'back_question',
      });

      break;
    }

    case CONST_CONTROL_VOICE['ACTION_OPEN_TEST']: {
      const CURRENT_PATH = router.currentRoute.fullPath;

      if (!['/login', '/page-not-found', '/student-test/do-test'].includes(CURRENT_PATH)) {
        const ID = parseInt(response['data']['value']);

        if (ID > 0) {
          store.dispatch('studentTest/setChooseTest', ID);

          router.push('/student-test/do-test');
        }
      }

      break;
    }
      
    case CONST_CONTROL_VOICE['ACTION_CHOOSE_ANSWER']: {
      const CURRENT_PATH = router.currentRoute.fullPath;

      if (CURRENT_PATH === '/student-test/do-test') {
        const ANSWER = parseInt(response['data']['value']);

        if (ANSWER > 0) {
          let STEP = store.getters.controlChooseAnswer.step;

          store.dispatch('studentTest/setControlChooseAnswer', {
            step: STEP++,
            answer: ANSWER,
          });
        }
      }

      break;
    }

    default: {
      clearSound();
      playSound(CONST_CONTROL_VOICE['SOUND_EXCEPTION']);

      MakeToast({
        variant: 'warning',
        title: i18n.t('TOAST.WARNING'),
        content: i18n.t('NOTIFY.SYSTEM.VOICE_EXCEPTION'),
      });
    }
  }
}
