import request from '../config';

export function getAllClassByTeacher(url, params) {
  return request.getRequest(url, params);
}

export function getAllTestByClass(url, params) {
  return request.getRequest(url, params);
}

export function getResultTest(url, params) {
  return request.getRequest(url, params);
}
