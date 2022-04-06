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
