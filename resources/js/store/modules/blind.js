import Cookies from 'js-cookie';

const LIST_STATUS_BLIND = ['0', '1'];

function getBlind() {
  const BLIND = Cookies.get('MODE_BLIND');

  if (LIST_STATUS_BLIND.includes(BLIND)) {
    return BLIND;
  }

  return '0';
}

const state = {
  blind: getBlind(),
};

const mutations = {
  SET_BLIND: (state, blind) => {
    if (LIST_STATUS_BLIND.includes(blind)) {
      state.blind = blind;
      Cookies.set('MODE_BLIND', blind);
    }
  },
};

const actions = {
  setBlind({ commit }, blind) {
    commit('SET_BLIND', blind);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
