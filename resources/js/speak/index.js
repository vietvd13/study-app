import { KEY_START_RECORD } from './config';
import { handleRecord } from './record';

function handleSpeak() {
	console.log('Runing...');

	handleRecord(KEY_START_RECORD);
}

handleSpeak();
