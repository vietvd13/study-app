import Cookies from 'js-cookie';

const STATUS_RECORD = 'STATUS_RECORD';

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
