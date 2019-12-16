import VueRegisterElement from "vue-register-element/dist/main.js";

import grid from "./Grid";
VueRegisterElement("grid", grid);

import grid_section from "./GridSection";
VueRegisterElement("grid-section", grid_section);


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

import ace from "./Ace";
VueRegisterElement("ace", ace);

import Datatables from "./Datatables";
VueRegisterElement("alt-datatables", Datatables);

import datetime from "./Datetime";
VueRegisterElement("alt-datetime", datetime);

import ckeditor from "./ckeditor";
VueRegisterElement("ckeditor", ckeditor);
