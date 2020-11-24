import Vue from 'vue';

import VueI18n from 'vue-i18n'
import Element from 'element-ui';
import VueResource from 'vue-resource';


Vue.use(VueResource);

import VueGQL from '@mathsgod/vue-gql/src/index.js';
Vue.use(VueGQL);

import "element-ui/lib/theme-chalk/index.css";
import enLocale from 'element-ui/lib/locale/lang/en';
import zhLocale from 'element-ui/lib/locale/lang/zh-TW';

Vue.use(VueI18n);

const messages = {
    en: {
        ...enLocale // Or use `Object.assign({ message: 'hello' }, enLocale)`
    },
    "zh-hk": {
        ...zhLocale // Or use `Object.assign({ message: '你好' }, zhLocale)`
    }
}
// Create VueI18n instance with options
window.i18n = new VueI18n({
    locale: 'en', // set locale
    messages, // set locale messages
});

Vue.use(Element, {
    size: "small",
    i18n: (key, value) => window.i18n.t(key, value)
});



import DatePicker from "./DatePicker.vue";
Vue.component("date-picker", DatePicker);

import InputNumber from "./InputNumber.vue";
Vue.component("input-number", InputNumber);

import select3 from "./Select3";
Vue.component("select3", select3);



//var Vue = window.Vue;
import VueRegisterElement from "vue-register-element/dist/main.js";

import grid from "./Grid";
VueRegisterElement("grid", grid);

import grid_section from "./GridSection";
VueRegisterElement("grid-section", grid_section);


import Card from "./Card.vue";
import CardBody from "./CardBody.vue";
import CardHeader from "./CardHeader.vue";
import CardFooter from "./CardFooter.vue";

Vue.component("card", Card);
Vue.component("card-body", CardBody);
Vue.component("card-header", CardHeader);
Vue.component("card-footer", CardFooter);


import RT2 from "./RT2.vue";
window.RT2 = RT2;
Vue.component("rt2", RT2);
//VueRegisterElement("rt2", RT2);

import Table from "./Table";
VueRegisterElement("alt-table", Table);

import daterangepicker from "./daterangepicker";
VueRegisterElement("daterangepicker", daterangepicker);

import date from './Date';
VueRegisterElement("date", date);

import select2 from 'vue-select2/src/Select2';
VueRegisterElement("select2", select2);

import ace from "./Ace";
VueRegisterElement("ace", ace);

import Datatables from "./Datatables";
window.VueDataTables = Datatables;
VueRegisterElement("datatables", Datatables);

import datetime from "./Datetime";
VueRegisterElement("alt-datetime", datetime);

import ckeditor from "./ckeditor";
Vue.component("ckeditor", ckeditor);
//VueRegisterElement("ckeditor", ckeditor);

import altButton from "./Button";
VueRegisterElement("alt-button", altButton);

import fileman from "./fileman";
VueRegisterElement("fileman", fileman);

import TinyMCEVue from "./TinyMCE";

Vue.component("tinymce", TinyMCEVue);

import TwoStepVerificationCard from "./TwoStepVerificationCard";
Vue.component("two-step-verification-card", TwoStepVerificationCard);

import NavItem from './NavItem';
Vue.component("nav-item", NavItem);


import App from './class/App';
Vue.use(App);

import API from './class/API';
Vue.use(API);




Vue.component("r-form", () => import("./r-form"));
Vue.component("r-date", () => import("./r-date"));


import RTable from "./r-table";
import RTableColumn from "./r-table-column";
import RTableDropdownItem from "./r-table-dropdown-item";
Vue.component("r-table", RTable);
Vue.component("r-table-column", RTableColumn);
Vue.component("r-table-dropdown-item", RTableDropdownItem);

//Vue.component("r-table", () => import("./r-table"));
//Vue.component("r-table-column", () => import("./r-table-column"));
//Vue.component("r-table-dropdown-item", () => import("./r-table-dropdown-item"));
