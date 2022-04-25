import AudioRecorder from 'audio-recorder-polyfill';
import mpegEncoder from 'audio-recorder-polyfill/mpeg-encoder';
import { TIME_RECORD } from './config';
import {
  getPermission,
  getRecord,
  setRecord,
  getProccess,
  setProcess,
  getBlind,
} from './helper';
import { isLogged } from '@/utils/auth';
import CONST_TOGGLE_STATUS from '@/const/toggle_status';
import { handleRequestNavigation } from './request';
import { playSound, clearSound } from './sound';
import CONST_SOUND from './const';

AudioRecorder.encoder = mpegEncoder;
AudioRecorder.prototype.mimeType = 'audio/mpeg';
window.MediaRecorder = AudioRecorder;

let recorder;

export function handleRecord(key) {
  window.onkeydown = event => {
    if (getBlind()) {
      if (key.code.includes(event.code) && event.keyCode === key.keyCode) {
        event.preventDefault();

        if (
          getRecord() === CONST_TOGGLE_STATUS.STATUS_OFF && getProccess() === CONST_TOGGLE_STATUS.STATUS_OFF
        ) {
          if (isLogged()) {
            clearSound();
            handleStartRecord();
          }
        }
      }
    }
  };

  window.onkeyup = event => {
    if (getBlind()) {
      if (key.code.includes(event.code) && event.keyCode === key.keyCode) {
        event.preventDefault();

        if (isLogged()) {
          clearSound();
          handleEndRecord();
        }
      }
    }
  };
}

function initRecord() {
  navigator.mediaDevices
    .getUserMedia({ audio: true })
    .then(stream => {
      recorder = new MediaRecorder(stream);

      recorder.addEventListener('dataavailable', async event => {
        sendData(event);
      });

      recorder.start(TIME_RECORD);
    })
    .catch(error => {
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

function handleStartRecord() {
  if (getRecord() === CONST_TOGGLE_STATUS.STATUS_OFF) {
    console.log('VOICE NAVIGATION: Start Record');

    setRecord(CONST_TOGGLE_STATUS.STATUS_ON);
    playSound(CONST_SOUND.SOUND_START_RECORD);
    initRecord();
  }
}

function handleEndRecord() {
  if (
    getPermission() === CONST_TOGGLE_STATUS.STATUS_ON &&
	getRecord() === CONST_TOGGLE_STATUS.STATUS_ON
  ) {
    console.log('VOICE NAVIGATION: End Record');

    setRecord(CONST_TOGGLE_STATUS.STATUS_OFF);
    recorder.stop();
    recorder.stream.getTracks().forEach(i => i.stop());
    playSound(CONST_SOUND.SOUND_END_RECORD);
  }
}
