import VueRegisterElement from "vue-register-element/dist/main.js";

import Card from "./Card.vue";
import CardBody from "./CardBody.vue";
import CardHeader from "./CardHeader.vue";
import CardFooter from "./CardFooter.vue";

VueRegisterElement("card", Card);
VueRegisterElement("card-body", CardBody);
VueRegisterElement("card-header", CardHeader);
VueRegisterElement("card-footer", CardFooter);


import RT2 from "./RT2.vue";
VueRegisterElement("rt2", RT2);

import Table from "./Table";
VueRegisterElement("alt-table", Table);

import daterangepicker from "./daterangepicker";
VueRegisterElement("daterangepicker", daterangepicker);

import date from './Date';
VueRegisterElement("date", date);

import select2 from 'vue-select2/src/Select2';
VueRegisterElement("select2", select2);