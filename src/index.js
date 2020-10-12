Vue = window.Vue;
import 'element-ui/lib/theme-chalk/index.css';
import ElementUI from 'element-ui';
Vue.use(ElementUI);

import VueResource from 'vue-resource';
Vue.use(VueResource);

import VueGQL from '@mathsgod/vue-gql/src/index';
Vue.use(VueGQL);

import { WebAuthn } from '../vendor/mathsgod/r-webauthn/src/WebAuthn';

window.WebAuthn = WebAuthn;

import indexPage from './index.vue';

(new Vue(indexPage)).$mount("#app");

/* var scripts = document.getElementsByTagName("script"),
    src = scripts[scripts.length - 1].src;

import path from 'path';
//console.log(path.dirname(src));
//__webpack_public_path__=path.dirname(src)
__webpack_public_path__ = 'test'; */