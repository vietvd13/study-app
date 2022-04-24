import Cookies from 'js-cookie';
import ConstCookie from '@/const/cookie';

const state = {
  choose_class: '',
};

const mutations = {
  SET_CHOOSE_CLASS: (state, id) => {
    state.choose_class = id;
    Cookies.set(ConstCookie['CHOOSE_CLASS'], id);
  },
};

const actions = {
  setChooseClass({ commit }, id) {
    commit('SET_CHOOSE_CLASS', id);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
