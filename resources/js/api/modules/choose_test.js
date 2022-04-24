import request from '../config';

export function getAllTestByClass(url, params) {
  return request.getRequest(url, params);
}
