import request from '../config';

export function getAllCourse(url, params) {
  return request.getRequest(url, params);
}

export function getOneCourse(url) {
  return request.getRequest(url);
}

export function postCourse(url, data) {
  return request.postRequest(url, data);
}

export function putCourse(url, data) {
  return request.putRequest(url, data);
}

export function deleteCourse(url) {
  return request.deleteRequest(url);
}

export function getTeacher(url, params) {
  return request.getRequest(url, params);
}

export function assignTeacher(url, data) {
  return request.postRequest(url, data);
}
