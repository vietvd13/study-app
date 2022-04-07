import Cookies from 'js-cookie';

const LIST_REMOVE = [
  'StudyAppLanguage',
  'STATUS_PERMISSION',
  'STATUS_RECORD',
  'STATUS_PROCESS',
];

export function removeCookiesOld() {
  console.log('-- CLEAR COOKIES OLD --');
  console.log(LIST_REMOVE);

  let idx = 0;
  const len = LIST_REMOVE.length;

  while (idx < len) {
    Cookies.remove(LIST_REMOVE[idx]);

    idx++;
  }
}
