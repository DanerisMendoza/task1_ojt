import Vue from 'vue'
import App from './App.vue'
import router from './router'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap'
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.js';

import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import mitt from 'mitt'
import vuetify from './plugins/vuetify'

window.Pusher = Pusher

Vue.prototype.$Echo = new Echo({
  broadcaster: 'pusher',
  key: process.env.VUE_APP_WEBSOCKETS_KEY,
  wsHost: process.env.VUE_APP_WEBSOCKETS_SERVER,
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
  cluster: 'mt1'
})

const emitter = mitt()

Vue.prototype.$emitter = emitter

new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
