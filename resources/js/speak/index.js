import { KEY_START_RECORD } from './config';
import { handleRecord } from './record';
import { setPermission } from './helper';
import CONST_TOGGLE_STATUS from '@/const/toggle_status';

function handleSpeak() {
  console.log('Runing...');

  handleRecord(KEY_START_RECORD);
}

navigator.mediaDevices
  .getUserMedia({ audio: true })
  .then(() => {
    setPermission(CONST_TOGGLE_STATUS.STATUS_ON);
    handleSpeak();
  })
  .catch((err) => {
    console.log(err);
    setPermission(CONST_TOGGLE_STATUS.STATUS_OFF);
  });
