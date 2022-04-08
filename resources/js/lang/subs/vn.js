export default {
  TOAST: {
    SUCCESS: 'Sucess',
    WARNING: 'Warning',
    DANGER: 'Error',
  },

  ROUTER: {
    LOGIN: 'Account Login',
    PAGE_NOT_FOUND: 'Page Not Found',
    DASHBOARD: 'Dashboard',
    ACCOUNT: 'Account Management',
    CLASSES: 'Classes Management',
    COURSE: 'Course Management',
    TEST: 'Test Management',
  },
  LOGIN: {
    TITLE: 'Account Login',
    PLACEHOLDER_ACCOUNT: 'Enter your account',
    PLACEHOLDER_PASSWORD: 'Enter your password',
    BUTTON_LOGIN: 'Login',
  },
  ACCOUNT: {
    LABEL_FILTER_FULLNAME: 'Fullname',
    LABEL_FILTER_ROLE: 'Role',
    LABEL_FILTER_TELEPHONE: 'Telephone',
    LABEL_FILTER_EMAIL: 'Email',

    PLACEHOLDER_FILTER_FULLNAME: 'Enter fullname',
    PLACEHOLDER_FILTER_ROLE: 'Please select',
    PLACEHOLDER_FILTER_TELEPHONE: 'Enter telephone',
    PLACEHOLDER_FILTER_EMAIL: 'Enter email',

    BUTTON_ADD_NEW: 'Add new',

    TABLE_TITLE_STUDENT_ID: 'Student ID',
    TABLE_TITLE_FULLNAME: 'Fullname',
    TABLE_TITLE_PHONE: 'Phone',
    TABLE_TITLE_EMAIL: 'Email',
    TABLE_TITLE_STATUS: 'Status',
    TABLE_TITLE_ACTIONS: 'Actions',

    TABLE_CONTENT_STATUS_ACTIVE: 'Active',
    TABLE_CONTENT_STATUS_INACTIVE: 'Inactive',
    TABLE_CONTENT_NO_DATA: 'No data',

    MODAL_TITLE_ADD: 'Add new account',
    MODAL_TITLE_UPDATE: 'Update an account',
    MODAL_TITLE_DELETE: 'Delete an account',
    CONTENT_MODAL_DELETE: 'You will delete an account from the system. Are you sure?',

    LABEL_FORM_FULLNAME: 'Fullname',
    LABEL_FORM_CODE: 'Code',
    LABEL_FORM_TELEPHONE: 'Telephone',
    LABEL_FORM_EMAIL: 'Email',
    LABEL_FORM_ROLE: 'Role',
    LABEL_FORM_PASSWORD: 'Password',
    LABEL_FORM_BLIND: 'Blind students',
    LABEL_FORM_STATUS: 'Status',

    PLACEHOLDER_FORM_FULLNAME: 'Enter fullname',
    PLACEHOLDER_FORM_CODE: 'Enter code',
    PLACEHOLDER_FORM_ROLE: 'Please select',
    PLACEHOLDER_FORM_TELEPHONE: 'Enter telephone',
    PLACEHOLDER_FORM_EMAIL: 'Enter email',
    PLACEHOLDER_FORM_PASSWORD: 'Enter password',

    BUTTON_SUBMIT: 'Submit',
    BUTTON_DELETE: 'Delete',
    BUTTON_CANCEL: 'Cancel',
  },
  CLASSES: {
    LABEL_FILTER_NAME: 'Name',
    LABEL_FILTER_LEVEL: 'Level',

    PLACEHOLDER_FILTER_NAME: 'Enter name',
    PLACEHOLDER_FILTER_LEVEL: 'Please select',

    BUTTON_ADD_NEW: 'Add new',

    TABLE_TITLE_NAME: 'Name',
    TABLE_TITLE_LEVEL: 'Level',
    TABLE_TITLE_ARRANGEMENT: 'Arrangement',
    TABLE_TITLE_ACTIONS: 'Actions',

    TABLE_CONTENT_NO_DATA: 'No data',

    MODAL_TITLE_ADD: 'Add new classes',
    MODAL_TITLE_UPDATE: 'Update an classes',
    MODAL_TITLE_DELETE: 'Delete an classes',
    MODAL_TITLE_ASSIGN_STUDENT: 'Arrange students',
    MODAL_TITLE_ASSIGN_COURSE: 'Arrange courses',
    CONTENT_MODAL_DELETE: 'You will delete an classes from the system. Are you sure?',

    LABEL_FORM_NAME: 'Name',
    LABEL_FORM_LEVEL: 'Level',

    TITLE_LIST_STUDENT_IN_CLASS: 'Class: {name}',
    TITLE_LIST_STUDENT_IN_SYSTEM: 'List student in system',
    TITLE_NUMBER_STUDENT: 'Total number of students: {total}',

    TITLE_LIST_COURSE_IN_CLASS: 'Class: {name}',
    TITLE_LIST_COURSE_IN_SYSTEM: 'List course in system',

    PLACEHOLDER_FORM_NAME: 'Enter name',
    PLACEHOLDER_FORM_LEVEL: 'Please select',
    PLACEHOLDER_SEARCH_USER_CODE: 'Seach by user code',

    BUTTON_ADD: 'Add',
    BUTTON_REMOVE: 'Remove',
    BUTTON_SUBMIT: 'Submit',
    BUTTON_DELETE: 'Delete',
    BUTTON_CANCEL: 'Cancel',

    TEXT_YES: 'Yes',
    TEXT_NO: 'No',
    TEXT_ACTIVE: 'Active',
    TEXT_INACTIVE: 'Inactive',

    LABLE_START: 'Start date',
    LABLE_END: 'End date',
  },
  COURSE: {
    LABEL_FILTER_NAME: 'Name',
    LABEL_FILTER_LEVEL: 'Level',

    PLACEHOLDER_FILTER_NAME: 'Enter name',
    PLACEHOLDER_FILTER_LEVEL: 'Please select',

    BUTTON_ADD_NEW: 'Add new',

    TABLE_TITLE_NAME: 'Name',
    TABLE_TITLE_LEVEL: 'Level',
    TABLE_TITLE_ACTIONS: 'Actions',

    TABLE_CONTENT_NO_DATA: 'No data',

    MODAL_TITLE_ADD: 'Add new course',
    MODAL_TITLE_UPDATE: 'Update an course',
    MODAL_TITLE_DELETE: 'Delete an course',
    CONTENT_MODAL_DELETE: 'You will delete an course from the system. Are you sure?',

    LABEL_FORM_NAME: 'Name',
    LABEL_FORM_LEVEL: 'Level',

    PLACEHOLDER_FORM_NAME: 'Enter name',
    PLACEHOLDER_FORM_LEVEL: 'Please select',

    BUTTON_SUBMIT: 'Submit',
    BUTTON_DELETE: 'Delete',
    BUTTON_CANCEL: 'Cancel',
  },
  PAGE_NOT_FOUND: {
    DETAIL: "We can't seem to find the page you're looking for",
    GO_TO_HOME: 'Go To Home Page',
  },
  NAVBAR: {
    LOGOUT: 'Logout',
    LANGUAGE: 'Language',
    ENGLISH: 'English',
    VIETNAMESE: 'Tiếng Việt',
    BLIND_MODE: 'Blind asist',
    ON: 'Turn on',
    OFF: 'Turn off',
    MESSAGE_ON_BLIND: 'You have enabled the blind assist mode',
    MESSAGE_OFF_BLIND: 'You have turned off assistive mode for the visually impaired',
    MESSAGE_CHANGE_BLIND_ERROR: 'You have failed to change the mode of support for the blind',
  },
  ROLE: {
    PLEASE_SELECT: 'Please select',
    ADMIN: 'Admin',
    TEACHER: 'Teacher',
    STUDENT: 'Student',
  },
  ACCOUNT_STATUS: {
    PLEASE_SELECT: 'Please select',
    ACTIVE: 'Active',
    INACTIVE: 'Inactive',
  },
  I18N: {
    CHANGE_LANGUAGE: {
      SUCCESS: 'Change language successfully',
      FAILED: 'Language change failed',
    },
  },
  LOADING_TEXT: 'Loading',
  NOTIFY: {
    SYSTEM: {
      EXCEPTION: 'An unexpected error occurred',
    },
    LOGIN: {
      LOGIN_SUCCESS: 'You have successfully logged into the system',

      VALIDATE_EMAIL: 'The email information you entered is not in the correct format',
      VALIDATE_PASSWORD: 'The password you entered is not in the correct format',
    },
    LOGOUT: {
      LOGOUT_SUCCESS: 'You have successfully logged out of the system',
    },
    ACCOUNT: {
      ADD_SUCCESS: 'You have successfully created an account {email}',
      ADD_FAILD: 'You have created failure',
      UPDATE_SUCCESS: 'You have updated your {email} account information',
      UPDATE_FAILED: 'You have failed to edit your account information',
      DELETE_SUCCESS: 'You have deleted an account from the system',
      DELETE_FAILED: 'You have failed to delete your account from the system',

      VALIDATE_NAME: 'Fullname entered is not correct',
      VALIDATE_PASSWORD: 'Password entered is not correct',
      VALIDATE_NEW_PASSWORD: 'New password entered is not correct',
      VALIDATE_USER_CODE: 'User code entered is not correct',
      VALIDATE_EMAIL: 'Email entered is not correct',
      VALIDATE_PHONE: 'Telephone entered is not correct',
      VALIDATE_STATUS: 'Status entered is not correct',
      VALIDATE_IS_BLIND: 'Blind entered is not correct',
      VALIDATE_ROLE: 'Role entered is not correct',
    },
    CLASSES: {
      ADD_SUCCESS: 'You have successfully created class {name}',
      ADD_FAILD: 'You have created failure',
      UPDATE_SUCCESS: 'You have successfully edited the class {name}',
      UPDATE_FAILED: 'You have failed to edit your classes information',
      DELETE_SUCCESS: 'You have deleted an classes from the system',
      DELETE_FAILED: 'You have failed to delete your classes from the system',

      VALIDATE_NAME: 'Class name is incorrect',

      ASSIGN_STUDENT_SUCCESS: 'You have successfully arranged your students for the class',
    },
    COURSE: {
      ADD_SUCCESS: 'You have successfully created course {name}',
      ADD_FAILD: 'You have created failure',
      UPDATE_SUCCESS: 'You have successfully edited the course {name}',
      UPDATE_FAILED: 'You have failed to edit your course information',
      DELETE_SUCCESS: 'You have deleted an course from the system',
      DELETE_FAILED: 'You have failed to delete your course from the system',

      VALIDATE_NAME: 'Course name is incorrect',
    },
  },
};
