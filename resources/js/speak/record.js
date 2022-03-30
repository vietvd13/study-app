import AudioRecorder from 'audio-recorder-polyfill';
import mpegEncoder from 'audio-recorder-polyfill/mpeg-encoder';
import {
	getIsRecord,
	setStartRecord,
	setEndRecord,
	getStatusPermission,
	setHasPermission,
	setNotHasPermission,
	getStatusProccess,
	setStartProcess,
	setEndProcess
} from './helper';

AudioRecorder.encoder = mpegEncoder;
AudioRecorder.prototype.mimeType = 'audio/mp3';
window.MediaRecorder = AudioRecorder;

let recorder;

navigator.mediaDevices
	.getUserMedia({ audio: true })
	.then(stream => {
		recorder = new MediaRecorder(stream);
		clearRecord();
		setHasPermission();

		recorder.addEventListener('dataavailable', e => {
			setStartProcess();
			console.log(e.data);
			setEndProcess();
		});
	})
	.catch(error => {
		setNotHasPermission();
		console.log(error);
	});

export function handleRecord(key) {
	window.onkeydown = event => {
		if ((event.code === key.code || event.keyCode === key.keyCode) && event.ctrlKey) {
			event.preventDefault();

			if (getStatusPermission() === '1') {
				handleStartRecord();
			}
		}
	};

	window.onkeyup = event => {
		if ((event.code === key.code || event.keyCode === key.keyCode) && event.ctrlKey) {
			event.preventDefault();

			if (getStatusPermission() === '1') {
				handleEndRecord();
			}
		}
	};
}

function handleStartRecord() {
	if (getIsRecord() === '0' && getStatusProccess() === '0') {
		setStartRecord();

		startRecord();

		console.log('Start Record');

		setTimeout(() => {
			if (getIsRecord() === '1') {
				handleEndRecord();
			}
		}, 10000);
	}
}

function handleEndRecord() {
	setEndRecord();
	endRecord();
	clearRecord();

	console.log('End Record');
}

function startRecord() {
	recorder.start();
}

function endRecord() {
	recorder.stop();
}

function clearRecord() {
	recorder.stream.getTracks().forEach(i => i.stop());
}
