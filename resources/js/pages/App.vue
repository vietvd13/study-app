<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
import { removeCookiesOld } from '@/utils/removeCookiesOld';

export default {
  name: 'App',
  created() {
    console.log(
      '%cStudy App',
      'font-size: 20px; padding: 5px 10px 5px 10px; border-radius: .25rem; color: #04aa6d; background-color: #282a35; text-align: center;'
    );

    removeCookiesOld();
  },
};
</script>
