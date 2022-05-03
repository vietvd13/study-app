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
  control_question: {
    step: 0,
    action: null,
  },
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
  SET_CONTROL_QUESTION: (state, control) => {
    state.control_question = control;
  },
};

const actions = {
  setChooseClass({ commit }, id) {
    commit('SET_CHOOSE_CLASS', id);
  },
  setChooseTest({ commit }, id) {
    commit('SET_CHOOSE_TEST', id);
  },
  setControlQuestion({ commit }, control) {
    commit('SET_CONTROL_QUESTION', control);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
