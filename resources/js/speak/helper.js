import Cookies from 'js-cookie';

const STATUS_PERMISSION = 'STATUS_PERMISSION';
const STATUS_RECORD = 'STATUS_RECORD';
const STATUS_PROCESS = 'STATUS_PROCESS';

// STATUS_PERMISSION
export function getStatusPermission() {
	const GET_STATUS_PERMISSION = Cookies.get(STATUS_PERMISSION);

	if (GET_STATUS_PERMISSION) {
		return GET_STATUS_PERMISSION;
	}

	return '0';
}

export function setHasPermission() {
	Cookies.set(STATUS_PERMISSION, '1');
}

export function setNotHasPermission() {
	Cookies.set(STATUS_PERMISSION, '0');
}

// STATUS_RECORD
export function getIsRecord() {
	const GET_STATUS_RECORD = Cookies.get(STATUS_RECORD);

	if (GET_STATUS_RECORD) {
		return GET_STATUS_RECORD;
	}

	return '0';
}

export function setStartRecord() {
	Cookies.set(STATUS_RECORD, '1');
}

export function setEndRecord() {
	Cookies.set(STATUS_RECORD, '0');
}

// STATUS_PROCESS
export function getStatusProccess() {
	const GET_STATUS_PROCESS = Cookies.get(STATUS_PROCESS);

	if (GET_STATUS_PROCESS) {
		return GET_STATUS_PROCESS;
	}

	return '0';
}

export function setStartProcess() {
	Cookies.set(STATUS_PROCESS, '1');
}

export function setEndProcess() {
	Cookies.set(STATUS_PROCESS, '0');
}
