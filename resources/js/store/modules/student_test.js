import Cookies from 'js-cookie';
import ConstCookie from '@/const/cookie';

function getStateInCookie(key) {
  const VALUE = Cookies.get(key);

  if (VALUE) {
    return parseInt(VALUE);
  }

  return '';
}

const state = {
  choose_class: getStateInCookie(ConstCookie['CHOOSE_CLASS']),
  choose_test: getStateInCookie(ConstCookie['CHOOSE_TEST']),
};

const mutations = {
  SET_CHOOSE_CLASS: (state, id) => {
    state.choose_class = id;
    Cookies.set(ConstCookie['CHOOSE_CLASS'], id);
  },
  SET_CHOOSE_TEST: (state, id) => {
    state.choose_test = id;
    Cookies.set(ConstCookie['CHOOSE_TEST'], id);
  },
};

const actions = {
  setChooseClass({ commit }, id) {
    commit('SET_CHOOSE_CLASS', id);
  },
  setChooseTest({ commit }, id) {
    commit('SET_CHOOSE_TEST', id);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
