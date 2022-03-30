import AudioRecorder from 'audio-recorder-polyfill';
import mpegEncoder from 'audio-recorder-polyfill/mpeg-encoder';
import { TIME_RECORD } from './config';
import {
	getPermission,
	setPermission,
	getRecord,
	setRecord,
	getProccess,
	setProcess
} from './helper';
import CONST_TOGGLE_STATUS from '@/const/toggle_status';

AudioRecorder.encoder = mpegEncoder;
AudioRecorder.prototype.mimeType = 'audio/mpeg';
window.MediaRecorder = AudioRecorder;
import { postNavigation } from '@/api/modules/navigation';

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

function domTest(event) {
	var dom = document.getElementById('audio');

	dom.src = URL.createObjectURL(event.data);
}

async function sendData(event) {
	try {
		domTest(event);
		setProcess(CONST_TOGGLE_STATUS.STATUS_ON);

		// let DATA = new FormData();

		// DATA.append('user_voice', data.data);

		// const res = await postNavigation('/navigation/voice', DATA);
		// console.log(res);

		setProcess(CONST_TOGGLE_STATUS.STATUS_OFF);
	} catch (error) {
		console.log(error);
	}
}

function handleStartRecord() {
	if (getRecord() === CONST_TOGGLE_STATUS.STATUS_OFF) {
		console.log('Start Record');

		setRecord(CONST_TOGGLE_STATUS.STATUS_ON);
		initRecord();
	}
}

function handleEndRecord() {
	if (
		getPermission() === CONST_TOGGLE_STATUS.STATUS_ON &&
		getRecord() === CONST_TOGGLE_STATUS.STATUS_ON
	) {
		console.log('End Record');

		clearTimeout();
		setRecord(CONST_TOGGLE_STATUS.STATUS_OFF);
		recorder.stop();
		recorder.stream.getTracks().forEach(i => i.stop());
	}
}
