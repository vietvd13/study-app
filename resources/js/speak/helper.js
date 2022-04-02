import Cookies from 'js-cookie';
import CONST_COOKIE from '@/const/cookie';
import CONST_TOGGLE_STATUS from '@/const/toggle_status';

export function checkStatus(status) {
  return CONST_TOGGLE_STATUS.LIST_STATUS_TOGGLE.includes(status);
}

// STATUS_PERMISSION
export function getPermission() {
  const GET_STATUS_PERMISSION = Cookies.get(CONST_COOKIE.STATUS_PERMISSION);

  if (GET_STATUS_PERMISSION) {
    return GET_STATUS_PERMISSION;
  }

  return CONST_TOGGLE_STATUS.STATUS_OFF;
}

export function setPermission(status) {
  if (checkStatus(status)) {
    Cookies.set(CONST_COOKIE.STATUS_PERMISSION, status);
  }
}

// STATUS_RECORD
export function getRecord() {
  const GET_STATUS_RECORD = Cookies.get(CONST_COOKIE.STATUS_RECORD);

  if (GET_STATUS_RECORD) {
    return GET_STATUS_RECORD;
  }

  return CONST_TOGGLE_STATUS.STATUS_OFF;
}

export function setRecord(status) {
  if (checkStatus(status)) {
    Cookies.set(CONST_COOKIE.STATUS_RECORD, status);
  }
}

// STATUS_PROCESS
export function getProccess() {
  const GET_STATUS_PROCESS = Cookies.get(CONST_COOKIE.STATUS_PROCESS);

  if (GET_STATUS_PROCESS) {
    return GET_STATUS_PROCESS;
  }

  return CONST_TOGGLE_STATUS.STATUS_OFF;
}

export function setProcess(status) {
  if (checkStatus(status)) {
    Cookies.set(CONST_COOKIE.STATUS_PROCESS, status);
  }
}
