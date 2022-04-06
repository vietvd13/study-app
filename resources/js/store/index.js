import Vue from 'vue';
import Vuex from 'vuex';

import app from './modules/app';
import auth from './modules/auth';
import permissions from './modules/permissions';
import blind from './modules/blind';

import getters from './getters';

Vue.use(Vuex);

const modules = {
  app,
  auth,
  permissions,
  blind,
};

const store = new Vuex.Store({
  modules,
  getters,
});

export default store;
