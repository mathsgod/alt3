((typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] = (typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] || []).push([[7],{

/***/ "d7b4":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"ecbcdade-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-table-pagination.vue?vue&type=template&id=e8f4acae&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticStyle:{"white-space":"nowrap"}},[_c('el-tooltip',{attrs:{"content":"最前一頁","placement":"top"}},[_c('el-button',{attrs:{"disabled":_vm.firstPageDisabled},on:{"click":function($event){return _vm.$emit('input', 1)}}},[_c('i',{staticClass:"fa fa-fw fa-step-backward"})])],1),_c('el-tooltip',{staticClass:"m-0",attrs:{"content":"上一頁","placement":"top"}},[_c('el-button',{attrs:{"disabled":_vm.prevPageDisabled},on:{"click":function($event){$event.preventDefault();return _vm.$emit('input', _vm.value - 1)}}},[_c('i',{staticClass:"fa fa-fw fa-chevron-left"})])],1),_c('el-input',{staticStyle:{"width":"70px"},attrs:{"value":_vm.value,"min":1,"max":_vm.pageCount},on:{"change":function($event){return _vm.$emit('input', $event)}}}),_c('el-tooltip',{staticClass:"m-0",attrs:{"content":"下一頁","placement":"top"}},[_c('el-button',{attrs:{"disabled":_vm.nextPageDisabled},on:{"click":function($event){$event.preventDefault();return _vm.$emit('input', _vm.value + 1)}}},[_c('i',{staticClass:"fa fa-fw fa-chevron-right"})])],1),_c('el-tooltip',{staticClass:"m-0",attrs:{"content":"最後一頁","placement":"top"}},[_c('el-button',{attrs:{"disabled":_vm.lastPageDisabled},on:{"click":function($event){$event.preventDefault();return _vm.$emit('input', _vm.pageCount)}}},[_c('i',{staticClass:"fa fa-fw fa-step-forward"})])],1)],1)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/r-table-pagination.vue?vue&type=template&id=e8f4acae&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.number.constructor.js
var es_number_constructor = __webpack_require__("a9e3");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-table-pagination.vue?vue&type=script&lang=js&

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ var r_table_paginationvue_type_script_lang_js_ = ({
  props: {
    value: {
      type: Number,
      require: true
    },
    pageCount: {
      type: Number,
      default: 1
    }
  },
  computed: {
    firstPageDisabled: function firstPageDisabled() {
      return this.value <= 1;
    },
    prevPageDisabled: function prevPageDisabled() {
      return this.value <= 1;
    },
    nextPageDisabled: function nextPageDisabled() {
      return this.pageCount == this.value;
    },
    lastPageDisabled: function lastPageDisabled() {
      return this.pageCount == this.value;
    }
  }
});
// CONCATENATED MODULE: ./src/r-table-pagination.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_r_table_paginationvue_type_script_lang_js_ = (r_table_paginationvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/r-table-pagination.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  src_r_table_paginationvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var r_table_pagination = __webpack_exports__["default"] = (component.exports);

/***/ })

}]);
//# sourceMappingURL=alt-component.common.7.js.map