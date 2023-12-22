import 'core-js';
import 'regenerator-runtime';

import Vue from 'vue'
import App from './App.vue'

import 'leaflet/dist/leaflet.css';

new Vue({
  el: '#app',
  render: h => h(App)
})
