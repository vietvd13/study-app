import request from '../config';

export function getAllClasses(url, params) {
  return request.getRequest(url, params);
}

export function getOneClasses(url) {
  return request.getRequest(url);
}

export function postClasses(url, data) {
  return request.postRequest(url, data);
}

export function putClasses(url, data) {
  return request.putRequest(url, data);
}

export function deleteClasses(url) {
  return request.deleteRequest(url);
}

export function getStudent(url, params) {
  return request.getRequest(url, params);
}

export function assignStudent(url, data) {
  return request.postRequest(url, data);
}

export function assignCourse(url, data) {
  return request.postRequest(url, data);
}

export function postActivity(url, data) {
  return request.postRequest(url, data);
}

export function getAllActivity(url, params) {
  return request.getRequest(url, params);
}

export function postHandinActivity(url, data) {
  return request.postRequest(url, data);
}

export function getAllHandinActivity(url, params) {
  return request.getRequest(url, params);
}

export function postSubmitGrade(url, data) {
  return request.postRequest(url, data);
}

export function getAllClassTeacher(url, params) {
  return request.getRequest(url, params);
}

export function getAllClassStudent(url, params) {
  return request.getRequest(url, params);
}

export function getActionDetail(url, params) {
  return request.getRequest(url, params);
}
