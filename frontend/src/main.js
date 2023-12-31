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
import Swal from 'sweetalert2';
import store from './store';

window.Pusher = Pusher

Vue.prototype.$Echo = new Echo({
  broadcaster: 'pusher',
  key: 'local',
  wsHost: '127.0.0.1',
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
  cluster: 'mt1'
})

const emitter = mitt()

Vue.prototype.$emitter = emitter
Vue.prototype.$swal = Swal;

new Vue({
  router,
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
