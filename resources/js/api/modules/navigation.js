import * as RequestApi from '../request';

export function postNavigation(url, data) {
  return RequestApi.postOne(url, data);
}
