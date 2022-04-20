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

export function validateAssingCourse(list) {
  let idx = 0;
  let count = 0;
  const len = list.length;

  while (idx < len) {
    if (list[idx]['start_date'] && list[idx]['end_date']) {
      const END_DATE = new Date(list[idx]['end_date']);
      const START_DATE = new Date(list[idx]['start_date']);

      if (END_DATE.getTime() > START_DATE.getTime() && list['teacher_id'] !== null) {
        count = count + 1;
      }
    }

    idx++;
  }

  return count === list.length;
}
