import request from '../config';

export function getOneTest(url, params) {
  return request.getRequest(url, params);
}
