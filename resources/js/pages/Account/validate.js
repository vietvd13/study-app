import {
  validateFullname,
  validPassword,
  validAccountCode,
  validEmail,
  validPhone,
  validateNumberMoreThanZero,
} from '@/utils/validate';

const VALIDATE = {
  NAME: 'VALIDATE_NAME',
  NEW_PASSWORD: 'VALIDATE_NEW_PASSWORD',
  PASSWORD: 'VALIDATE_PASSWORD',
  USER_CODE: 'VALIDATE_USER_CODE',
  EMAIL: 'VALIDATE_EMAIL',
  PHONE: 'VALIDATE_PHONE',
  STATUS: 'VALIDATE_STATUS',
  IS_BLIND: 'VALIDATE_IS_BLIND',
  ROLE: 'VALIDATE_ROLE',
};

export function validateAddAccount(account) {
  const validate = {
    status: false,
    message: [],
  };

  if (!validateFullname(account['name'])) {
    validate.message.push(VALIDATE.NAME);
  }

  if (!validAccountCode(account['user_code'])) {
    validate.message.push(VALIDATE.USER_CODE);
  }

  if (!validPhone(account['phone'])) {
    validate.message.push(VALIDATE.PHONE);
  }

  if (!validEmail(account['email'])) {
    validate.message.push(VALIDATE.EMAIL);
  }

  if (!validateNumberMoreThanZero(account['role'])) {
    validate.message.push(VALIDATE.ROLE);
  }

  if (!validPassword(account['password'])) {
    validate.message.push(VALIDATE.PASSWORD);
  }

  if (![0, 1].includes(account['status'])) {
    validate.message.push(VALIDATE.STATUS);
  }

  if (![0, 1].includes(account['isBlind'])) {
    validate.message.push(VALIDATE.IS_BLIND);
  }

  if (validate.message.length > 0) {
    validate.status = false;
  } else {
    validate.status = true;
  }

  return validate;
}

export function validateUpdateAccount(account) {
  const validate = {
    status: false,
    message: [],
  };

  if (!validateFullname(account['name'])) {
    validate.message.push(VALIDATE.NAME);
  }

  if (!validAccountCode(account['user_code'])) {
    validate.message.push(VALIDATE.USER_CODE);
  }

  if (!validPhone(account['phone'])) {
    validate.message.push(VALIDATE.PHONE);
  }

  if (!validateNumberMoreThanZero(account['role'])) {
    validate.message.push(VALIDATE.ROLE);
  }

  if (!validPassword(account['new_password'])) {
    validate.message.push(VALIDATE.NEW_PASSWORD);
  }

  if (![0, 1].includes(account['status'])) {
    validate.message.push(VALIDATE.STATUS);
  }

  if (![0, 1].includes(account['isBlind'])) {
    validate.message.push(VALIDATE.IS_BLIND);
  }

  if (validate.message.length > 0) {
    validate.status = false;
  } else {
    validate.status = true;
  }

  return validate;
}
