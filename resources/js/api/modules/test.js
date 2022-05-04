import request from '../config';

export function getAllClasses(url, params) {
  return request.getRequest(url, params);
}

export function getAllClassTeacher(url, params) {
  return request.getRequest(url, params);
}

export function getDetailClass(url, params) {
  return request.getRequest(url, params);
}

export function postCreateTest(url, data) {
  return request.postRequest(url, data);
}

export function getAllTestTeacher(url, params) {
  return request.getRequest(url, params);
}
