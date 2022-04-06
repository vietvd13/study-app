import {
  validaClassesName,
} from '@/utils/validate';

const VALIDATE = {
  NAME: 'VALIDATE_NAME',
};

export function validateAddClasses(classes) {
  const validate = {
    status: false,
    message: [],
  };

  if (!validaClassesName(classes['name'])) {
    validate.message.push(VALIDATE.NAME);
  }

  if (validate.message.length > 0) {
    validate.status = false;
  } else {
    validate.status = true;
  }

  return validate;
}

export function validateUpdateClasses(classes) {
  const validate = {
    status: false,
    message: [],
  };

  if (!validaClassesName(classes['name'])) {
    validate.message.push(VALIDATE.NAME);
  }

  if (validate.message.length > 0) {
    validate.status = false;
  } else {
    validate.status = true;
  }

  return validate;
}
