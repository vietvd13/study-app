import AudioRecorder from 'audio-recorder-polyfill';
import mpegEncoder from 'audio-recorder-polyfill/mpeg-encoder';
import { TIME_RECORD } from './config';
import {
  getPermission,
  setPermission,
  getRecord,
  setRecord,
  getProccess,
  setProcess,
} from './helper';
import CONST_TOGGLE_STATUS from '@/const/toggle_status';
import { handleRequestNavigation } from './request';
import { playSound } from './sound';
import CONST_SOUND from './const';

AudioRecorder.encoder = mpegEncoder;
AudioRecorder.prototype.mimeType = 'audio/mpeg';
window.MediaRecorder = AudioRecorder;

let recorder;

export function handleRecord(key) {
  window.onkeydown = event => {
    if ((event.code === key.code || event.keyCode === key.keyCode) && event.ctrlKey) {
      event.preventDefault();

      if (
        getRecord() === CONST_TOGGLE_STATUS.STATUS_OFF &&
				getProccess() === CONST_TOGGLE_STATUS.STATUS_OFF
      ) {
        handleStartRecord();
      }
    }
  };

  window.onkeyup = event => {
    if (event.code === key.code || event.keyCode === key.keyCode || event.ctrlKey) {
      event.preventDefault();
      handleEndRecord();
    }
  };
}

function initRecord() {
  navigator.mediaDevices
    .getUserMedia({ audio: true })
    .then(stream => {
      recorder = new MediaRecorder(stream);

      recorder.addEventListener('dataavailable', async event => {
        console.log(event);

        // let dom = document.getElementById('test');
        // dom.src = URL.createObjectURL(event.data);

        sendData(event);
      });

      setPermission(CONST_TOGGLE_STATUS.STATUS_ON);
      recorder.start(TIME_RECORD);
    })
    .catch(error => {
      setPermission(CONST_TOGGLE_STATUS.STATUS_OFF);
      console.log(error);
    });
}

async function sendData(event) {
  try {
    setProcess(CONST_TOGGLE_STATUS.STATUS_ON);

    handleRequestNavigation(event);

    setProcess(CONST_TOGGLE_STATUS.STATUS_OFF);
  } catch (error) {
    console.log(error);
  }
}

async function handleStartRecord() {
  if (getRecord() === CONST_TOGGLE_STATUS.STATUS_OFF) {
    console.log('Start Record');

    setRecord(CONST_TOGGLE_STATUS.STATUS_ON);
    await playSound(CONST_SOUND.SOUND_START_RECORD);
    initRecord();
  }
}

async function handleEndRecord() {
  if (
    getPermission() === CONST_TOGGLE_STATUS.STATUS_ON &&
		getRecord() === CONST_TOGGLE_STATUS.STATUS_ON
  ) {
    console.log('End Record');

    setRecord(CONST_TOGGLE_STATUS.STATUS_OFF);
    recorder.stop();
    recorder.stream.getTracks().forEach(i => i.stop());
    await playSound(CONST_SOUND.SOUND_END_RECORD);
  }
}
