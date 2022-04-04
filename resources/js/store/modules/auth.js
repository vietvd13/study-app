import { postLogin, getUser } from '@/api/modules/auth';
import { isLogged, setLogged, removeToken } from '@/utils/auth';
import { resetRouter } from '@/router';

const state = {
  id: '',
  name: '',
  userCode: '',
  email: '',
  phone: '',
  status: '',
  isBlind: '',
  roles: [],
  token: isLogged(),
};

const mutations = {
  SET_ID: (state, id) => {
    state.id = id;
  },
  SET_NAME: (state, name) => {
    state.name = name;
  },
  SET_USER_CODE: (state, userCode) => {
    state.userCode = userCode;
  },
  SET_EMAIL: (state, email) => {
    state.email = email;
  },
  SET_PHONE: (state, phone) => {
    state.phone = phone;
  },
  SET_STATUS: (state, status) => {
    state.status = status;
  },
  SET_BLIND: (state, isBlind) => {
    state.isBlind = isBlind;
  },
  SET_ROLES: (state, roles) => {
    state.roles = roles;
  },
};

const actions = {
  doLogin({ commit }, Account) {
    return new Promise((resolve, reject) => {
      const URL = `/auth/login`;

      postLogin(URL, Account)
        .then((res) => {
          if (res['status'] !== 200) {
            reject();
          }

          const DATA = res['data'];

          commit('SET_ID', DATA['profile']['id']);
          commit('SET_NAME', DATA['profile']['name']);
          commit('SET_USER_CODE', DATA['profile']['user_code']);
          commit('SET_EMAIL', DATA['profile']['email']);
          commit('SET_PHONE', DATA['profile']['phone']);
          commit('SET_STATUS', DATA['profile']['status']);
          commit('SET_BLIND', DATA['profile']['isBlind']);
          commit('SET_ROLES', DATA['roles']);

          setLogged('1');
          resolve();
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  getUserInfo({ commit }) {
    return new Promise((resolve, reject) => {
      const URL = `/auth/user`;

      getUser(URL)
        .then((res) => {
          if (res['status'] !== 200) {
            reject();
          }

          const DATA = res['data'];

          commit('SET_ID', DATA['profile']['id']);
          commit('SET_NAME', DATA['profile']['name']);
          commit('SET_USER_CODE', DATA['profile']['user_code']);
          commit('SET_EMAIL', DATA['profile']['email']);
          commit('SET_PHONE', DATA['profile']['phone']);
          commit('SET_STATUS', DATA['profile']['status']);
          commit('SET_BLIND', DATA['profile']['isBlind']);
          commit('SET_ROLES', DATA['roles']);

          resolve(DATA);
        })
        .catch((err) => {
          reject(err);
        });
    });
  },
  doLogout({ commit }) {
    commit('SET_ID', '');
    commit('SET_NAME', '');
    commit('SET_USER_CODE', '');
    commit('SET_EMAIL', '');
    commit('SET_PHONE', '');
    commit('SET_STATUS', '');
    commit('SET_BLIND', '');
    commit('SET_ROLES', []);

    removeToken();
    resetRouter();
  },
  resetToken({ commit }) {
    return new Promise(resolve => {
      commit('SET_ROLES', []);

      removeToken();
      resolve();
    });
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
