import store from '@/store';

export function hasRole(currentRole, listRoleCanAccess) {
  if (!Array.isArray(listRoleCanAccess)) {
    return false;
  }

  return listRoleCanAccess.includes(currentRole);
}

export function getCurrentRole() {
  const CURRENT_ROLE = store.getters.roles;

  if (!Array.isArray(CURRENT_ROLE)) {
    return '';
  }

  if (CURRENT_ROLE.length > 0) {
    return CURRENT_ROLE[0];
  }

  return '';
}
