import {
  validCourseName,
  validateString,
} from '@/utils/validate';

const VALIDATE = {
  NAME: 'VALIDATE_NAME',
};

export function validateAddCourse(course) {
  const validate = {
    status: false,
    message: [],
  };

  if (!validCourseName(course['name'])) {
    validate.message.push(VALIDATE.NAME);
  }

  if (validate.message.length > 0) {
    validate.status = false;
  } else {
    validate.status = true;
  }

  return validate;
}

export function validateUpdateCourse(course) {
  const validate = {
    status: false,
    message: [],
  };

  if (!validCourseName(course['name'])) {
    validate.message.push(VALIDATE.NAME);
  }

  if (validate.message.length > 0) {
    validate.status = false;
  } else {
    validate.status = true;
  }

  return validate;
}

export function validateUploadDocs(data) {
  if (!validateString(data['name'])) {
    return false;
  }

  if (!validateString(data['description'])) {
    return false;
  }

  if (!data['file']) {
    return false;
  }

  return true;
}
