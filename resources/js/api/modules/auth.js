import request from '../config';

export function getCSRF(url) {
  return request.getRequest(url);
}

export function postLogin(url, data) {
  return request.postRequest(url, data);
}

export function getUser(url) {
  return request.getRequest(url);
}
