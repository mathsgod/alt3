import Vue from 'vue';
//window.Vue = Vue;
Vue.config.productionTip = false;

import $ from "jquery";
window.$ = $;
import "bootstrap/dist/js/bootstrap.bundle.js";
import "bootstrap/dist/css/bootstrap.css";

import '@fortawesome/fontawesome-free/css/all.css';

import 'sweetalert2/dist/sweetalert2.css';

import 'icheck-bootstrap/icheck-bootstrap.css';

import 'tippy.js/dist/tippy.css';

import path from "path";
window.path = path;

import Swal from "sweetalert2/dist/sweetalert2.js";
window.Swal = Swal;

import GQL from './vue-gql.js';
Vue.use(GQL);

import App from './App.vue';

import VueResource from 'vue-resource';
Vue.use(VueResource);

import VJstree from "vue-jstree";

/*
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



