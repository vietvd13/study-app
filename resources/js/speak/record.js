import { getIsRecord, setStartRecord, setEndRecord } from './helper';

export function handleRecord(key) {
	window.onkeydown = event => {
		if (event.code === key.code || event.keyCode === key.keyCode) {
			event.preventDefault();

			startRecord();
		}
	};

	window.onkeyup = event => {
		if (event.code === key.code || event.keyCode === key.keyCode) {
			event.preventDefault();

			endRecord();
		}
	};
}

function startRecord() {
	if (getIsRecord() === '0') {
		setStartRecord();

		console.log('Start Record');

		setTimeout(() => {
			if (getIsRecord() === '1') {
				endRecord();
			}
		}, 10000);
	}
}

function endRecord() {
	setEndRecord();

	console.log('End Record');
}
