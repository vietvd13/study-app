import request from '../config';

export function getAllClasses(url, params) {
  return request.getRequest(url, params);
}

export function getAllClassTeacher(url, params) {
  return request.getRequest(url, params);
}

export function getAllCourse(url, params) {
  return request.getRequest(url, params);
}

export function postCreateTest(url, data) {
  return request.postRequest(url, data);
}
