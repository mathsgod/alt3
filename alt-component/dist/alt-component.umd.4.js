((typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] = (typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] || []).push([[4],{

/***/ "8e4e":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"ecbcdade-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-form-table.vue?vue&type=template&id=dc37eaa8&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('el-table',{attrs:{"data":_vm.data}},[_c('el-table-column',{attrs:{"width":"46px"},scopedSlots:_vm._u([{key:"default",fn:function(scope){return [(scope.row[_vm.dataKey])?_c('button',{staticClass:"btn btn-xs btn-danger",on:{"click":function($event){$event.preventDefault();return _vm.removeRow(scope.$index, scope.row)}}},[_c('i',{staticClass:"fa fa-fw fa-minus"})]):_c('button',{staticClass:"btn btn-xs btn-warning",on:{"click":function($event){$event.preventDefault();return _vm.removeRow(scope.$index)}}},[_c('i',{staticClass:"fa fa-fw fa-minus"})]),_c('input',{attrs:{"type":"hidden","name":(_vm.dataName + "[" + (scope.$index) + "][" + _vm.dataKey + "]")},domProps:{"value":scope.row[_vm.dataKey]}})]}}])},[_c('template',{slot:"header"},[_c('button',{staticClass:"btn btn-xs btn-primary",on:{"click":function($event){$event.preventDefault();return _vm.addRow()}}},[_c('i',{staticClass:"fa fa-fw fa-plus"})])])],2),_vm._t("default"),_vm._l((_vm.deletedId),function(i,index){return _c('input',{key:index,attrs:{"type":"hidden","name":(_vm.dataName + "[" + (_vm.data.length + index) + "][__del__]")},domProps:{"value":i}})})],2)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/r-form-table.vue?vue&type=template&id=dc37eaa8&

// EXTERNAL MODULE: ./node_modules/core-js/modules/es.array.splice.js
var es_array_splice = __webpack_require__("a434");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-form-table.vue?vue&type=script&lang=js&

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
/* harmony default export */ var r_form_tablevue_type_script_lang_js_ = ({
  props: {
    data: Array,
    dataKey: String,
    dataName: {
      type: String,
      default: "data"
    }
  },
  data: function data() {
    return {
      deletedId: []
    };
  },
  methods: {
    removeRow: function removeRow(index, data) {
      if (data) {
        this.deletedId.push(data[this.dataKey]);
      }

      this.data.splice(index, 1);
    },
    addRow: function addRow() {
      this.data.unshift({});
    }
  }
});
// CONCATENATED MODULE: ./src/r-form-table.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_r_form_tablevue_type_script_lang_js_ = (r_form_tablevue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/r-form-table.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  src_r_form_tablevue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var r_form_table = __webpack_exports__["default"] = (component.exports);

/***/ })

}]);
//# sourceMappingURL=alt-component.umd.4.js.map