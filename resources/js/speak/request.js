import { postNavigation } from '@/api/modules/navigation';
import router from '@/router';
import i18n from '@/lang';
import store from '@/store';
import { MakeToast } from '@/toast/toastMessage';

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
  }
}

function goToScreen(response) {
  const action = response.action;
  const LIST_ACTION = [
    'student_list_test',
    'student_list_test_today',
    'next_question',
    'back_question',
    'open_test'
  ];

  if (LIST_ACTION.includes(action)) {
    switch (action) {
      case 'student_list_test': {
        router.push('/test');

        break;
      }

      case 'student_list_test_today': {
        router.push('/test');

        break;
      }

      case 'next_question': {
        let STEP = store.getters.controlQuestion.step;

        store.dispatch('studentTest/setControlQuestion', {
          step: STEP++,
          action: 'next_question',
        });

        break;
      }

      case 'back_question': {
        let STEP = store.getters.controlQuestion.step;

        store.dispatch('studentTest/setControlQuestion', {
          step: STEP++,
          action: 'back_question',
        });

        break;
      }

      case 'open_test': {
        const CURRENT_PATH = router.fullPath;

        if (!['/login', '/page-not-found', '/student-test/do-test'].includes(CURRENT_PATH)) {
          const ID = parseInt(response['data']['value']);

          if (ID > 0) {
            store.dispatch('studentTest/setChooseTest', ID);
  
            router.push('/student-test/do-test');
          }
        }

        break;
      }

      default: {
        MakeToast({
          variant: 'warning',
          title: i18n.t('TOAST.WARNING'),
          content: 'Hành động chưa được tạo',
        });
      }
    }
  }
}
