import request from '../config';

export function getAllAccount(url, params) {
  return request.getRequest(url, params);
}

export function getOneAccount(url, params) {
  return request.getRequest(url, params);
}

export function postAccount(url, data) {
  return request.postRequest(url, data);
}

export function putAccount(url, data) {
  return request.putRequest(url, data);
}

export function deleteAccount(url, data) {
  return request.deleteRequest(url, data);
}
