import { postNavigation } from '@/api/modules/navigation';
import router from '@/router';
import i18n from '@/lang';
import { MakeToast } from '@/toast/toastMessage';

const USER_VOICE = 'user_voice';
const URL = '/navigation/voice';

export async function handleRequestNavigation(event) {
  try {
    const DATA = new FormData();

    DATA.append(USER_VOICE, event.data);

    const response = await postNavigation(URL, DATA);

    console.log(response);
    goToScreen(response.action);
  } catch (error) {
    console.log(error);
  }
}

function goToScreen(page) {
  const LIST_ACTION = ['student_list_test', 'student_list_test_today'];

  if (LIST_ACTION.includes(page)) {
    switch (page) {
      case 'student_list_test': {
        router.push('/test');

        break;
      }

      case 'student_list_test_today': {
        router.push('/test');

        break;
      }

      default: {
        MakeToast({
          variant: i18n.t('TOAST.WARNING'),
          title: 'Voice control',
          content: 'Hành động chưa được tạo',
        });
      }
    }
  }
}
