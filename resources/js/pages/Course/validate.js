import {
  validCourseName,
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
