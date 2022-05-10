import request from '../config';

export function getOneTest(url, params) {
  return request.getRequest(url, params);
}

export function postAnswerTest(url, body) {
  return request.postRequest(url, body);
}

export function getResultTest(url, params) {
  return request.getRequest(url, params);
}