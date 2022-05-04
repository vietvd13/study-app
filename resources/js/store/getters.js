const getters = {
  language: state => state.app.language,
  id: state => state.auth.id,
  name: state => state.auth.name,
  userCode: state => state.auth.userCode,
  email: state => state.auth.email,
  phone: state => state.auth.phone,
  status: state => state.auth.status,
  isBlind: state => state.auth.isBlind,
  roles: state => state.auth.roles,
  token: state => state.auth.token,
  blind: state => state.blind.blind,
  permissionRoutes: state => state.permissions.routes,
  addRoutes: state => state.permissions.addRoutes,
  choose_class: state => state.studentTest.choose_class,
  choose_test: state => state.studentTest.choose_test,
  controlQuestion: state => state.studentTest.control_question,
};

export default getters;
