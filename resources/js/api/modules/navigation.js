import request from '../config';

export function postNavigation(url, data) {
  return request.postRequest(url, data);
}
