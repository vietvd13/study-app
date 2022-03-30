import { KEY_START_RECORD } from './config';
import { startSpeak } from './startSeapk';

function handleSpeak() {
	console.log('Runing...');

	startSpeak(KEY_START_RECORD);
}

handleSpeak();
