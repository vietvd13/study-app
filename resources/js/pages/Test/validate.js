import {
  validateString,
  validateNumberMoreThanZero,
  validateFileCSV,
} from '@/utils/validate';
import { isAvailable } from '@/utils/isAvailable';

export function validatePostTest(test) {
  if (!validateString(test['test_name'])) {
    return false;
  }

  if (!validateNumberMoreThanZero(test['class_id'])) {
    return false;
  }

  if (!validateNumberMoreThanZero(test['course_id'])) {
    return false;
  }

  if (!(test['limit_time'] >= 0)) {
    return false;
  }

  if (![true, false].includes(test['blind_support'])) {
    return false;
  }

  if (!isAvailable(test, 'file.name')) {
    return false;
  } else {
    if (!validateFileCSV(test['file']['name'])) {
      return false;
    }
  }

  if (!isAvailable(test, 'file.type')) {
    return false;
  } else {
    if (!(test['file']['type']) === 'text/csv') {
      return false;
    }
  }

  return true;
}

export function validatePutTest(test) {
  if (!validateNumberMoreThanZero(test['test_id'])) {
    return false;
  }

  if (!validatePostTest(test)) {
    return false;
  }

  return true;
}
