import request from '../config';

export function getAllClassStudent(url, params) {
  return request.getRequest(url, params);
}
