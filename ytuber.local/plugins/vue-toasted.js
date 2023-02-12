import Toasted from 'vue-toasted';
import Vue from 'vue';
Vue.component('Toasted', Toasted);
Vue.use(Toasted, {
  duration: 7000, iconPack: 'custom-class', theme: 'bubble'
});
