import httpVueLoader from "http-vue-loader";
window.httpVueLoader = httpVueLoader;
import "x-html-bs4/dist/x-html-bs4.umd.js";


var Vue = window.Vue;
import Element from 'element-ui';

import 'element-ui/lib/theme-chalk/index.css';
Vue.use(Element, { size: "small" });
