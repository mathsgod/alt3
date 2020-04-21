import '@fortawesome/fontawesome-free/css/all.css';

import 'sweetalert2/dist/sweetalert2.css';

import 'icheck-bootstrap/icheck-bootstrap.css';

import path from "path";
window.path = path;

import Swal from "sweetalert2/dist/sweetalert2.js";
window.Swal = Swal;

import GQL from './vue-gql.js';
window.Vue.use(GQL);

import App from './App.vue';
/*
import Vue from 'vue';


import VueResource from 'vue-resource';
window.Vue = Vue;
window.VueResource = VueResource;


//Vue.config.productionTip = false;
//Vue.use(VueResource);


Vue.prototype.$gql = new GQL(Vue);
*/
window.fileman = App;
export default App;

/*
new Vue({
  render: h => h(App),
}).$mount('#app');*/



