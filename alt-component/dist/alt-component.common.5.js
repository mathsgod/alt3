((typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] = (typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] || []).push([[5],{

/***/ "f160":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"ecbcdade-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-form.vue?vue&type=template&id=70d2f4b0&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('card',{attrs:{"type":_vm.type,"outline":_vm.outline}},[_c('card-body',[_c('el-form',{ref:"form1",attrs:{"action":_vm.action,"model":_vm.form,"label-width":"auto","method":_vm.method,"enctype":_vm.enctype}},[_vm._t("default",null,{"form":_vm.form})],2)],1),_c('card-footer',[_c('el-button',{attrs:{"icon":"el-icon-check","type":"success"},on:{"click":function($event){return _vm.onSubmit()}}},[_vm._v("Submit")]),_c('el-button',{attrs:{"type":"warning"},on:{"click":function($event){return _vm.onBack()}}},[_vm._v("Back")])],1)],1)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/r-form.vue?vue&type=template&id=70d2f4b0&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-form.vue?vue&type=script&lang=js&
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
/* harmony default export */ var r_formvue_type_script_lang_js_ = ({
  props: {
    type: String,
    action: String,
    outline: Boolean,
    method: String,
    enctype: String,
    data: {
      type: Object,
      default: function _default() {
        return {};
      }
    }
  },
  data: function data() {
    return {
      form: this.data
    };
  },
  provide: {
    rForm: undefined
  },
  mounted: function mounted() {},
  methods: {
    onSubmit: function onSubmit() {
      var _this = this;

      this.$refs.form1.validate(function (valid) {
        if (valid) {
          _this.$refs.form1.$el.submit();
        }
      });
    },
    onBack: function onBack() {
      window.history.go(-1);
    }
  }
});
// CONCATENATED MODULE: ./src/r-form.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_r_formvue_type_script_lang_js_ = (r_formvue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/r-form.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  src_r_formvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var r_form = __webpack_exports__["default"] = (component.exports);

/***/ })

}]);
//# sourceMappingURL=alt-component.common.5.js.map