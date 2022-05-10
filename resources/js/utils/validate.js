export function validEmail(email) {
  const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

  return re.test(email);
}

export function validPassword(password) {
  const re = /^\S{8,16}$/;

  return re.test(password);
}

export function validEmptyOrWhiteSpace(string) {
  const re = /^\s*$/;
  return re.test(string);
}

export function validAccountCode(string) {
  const re = /^[_A-z0-9]{1,}$/;
  return re.test(string);
}

export function validPhone(string) {
  const re = /^(?:(?:\(?(?:00|\+)([1-4]\d\d|[1-9]\d+)\)?)[\-\.\ \\\/]?)?((?:\(?\d{1,}\)?[\-\.\ \\\/]?){0,})(?:[\-\.\ \\\/]?(?:#|ext\.?|extension|x)[\-\.\ \\\/]?(\d+))?$/i;
  return re.test(string);
}

export function validateNumberMoreThanZero(number) {
  const re = /^[1-9][0-9]*$/;
  return re.test(number);
}

export function validateFullname(string) {
  const re = /\S/;
  return re.test(string);
}

export function validaClassesName(string) {
  const re = /\S/;
  return re.test(string);
}

export function validCourseName(string) {
  const re = /\S/;
  return re.test(string);
}

export function validateString(string) {
  const re = /\S/;
  return re.test(string);
}

export function validateFileCSV(string) {
  const re = /.*\.(csv)/;
  return re.test(string);
}
