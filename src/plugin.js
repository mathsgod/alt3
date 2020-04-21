import httpVueLoader from "http-vue-loader";
window.httpVueLoader = httpVueLoader;
import "x-html-bs4/dist/x-html-bs4.umd.js";


var Vue = window.Vue;
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);

import Element from 'element-ui';
Vue.use(Element, { size: "small" });

import enLocale from 'element-ui/lib/locale/lang/en';
import zhLocale from 'element-ui/lib/locale/lang/zh-TW';


Vue.config.lang = "en";
Vue.locale("en", enLocale);
Vue.locale("zh-tw", zhLocale);







