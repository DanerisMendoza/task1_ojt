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

const emitter = mitt()


// Initialize WebSocket
const serverAddress = 'wss://cloud-linen-climb.glitch.me/?user-agent=Mozilla';
const ws = new WebSocket(serverAddress);

ws.addEventListener('open', () => {
  console.log('WebSocket connection opened');
});


Vue.prototype.$emitter = emitter
Vue.prototype.$swal = Swal;
Vue.prototype.$ws = ws;

// Vue instance
new Vue({
  router,
  vuetify,
  store,
  render: h => h(App)
}).$mount('#app')
