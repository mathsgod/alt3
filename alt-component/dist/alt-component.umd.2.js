((typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] = (typeof self !== 'undefined' ? self : this)["webpackJsonpalt_component"] || []).push([[2],{

/***/ "ff35":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"ecbcdade-vue-loader-template"}!./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-a.vue?vue&type=template&id=70ca2138&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('a',{class:_vm.getClass,attrs:{"href":_vm.href},on:{"click":function($event){$event.preventDefault();return _vm.click($event)}}},[(_vm.icon)?[_c('i',{class:_vm.icon})]:_vm._e(),_vm._t("default")],2)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/r-a.vue?vue&type=template&id=70ca2138&

// EXTERNAL MODULE: ./node_modules/regenerator-runtime/runtime.js
var runtime = __webpack_require__("96cf");

// EXTERNAL MODULE: ./node_modules/@babel/runtime/helpers/esm/asyncToGenerator.js
var asyncToGenerator = __webpack_require__("1da1");

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/r-a.vue?vue&type=script&lang=js&


//
//
//
//
//
//
/* harmony default export */ var r_avue_type_script_lang_js_ = ({
  props: {
    type: {
      type: String,
      default: "default"
    },
    size: String,
    icon: String,
    block: Boolean,
    confirm: Boolean,
    confirmMessage: {
      type: String,
      default: "Are you sure?"
    },
    href: String
  },
  computed: {
    getClass: function getClass() {
      var c = ["btn"];

      if (this.type) {
        c.push("btn-".concat(this.type));
      }

      if (this.size) {
        c.push("btn-".concat(this.size));
      }

      if (this.block) {
        c.push("btn-block");
      }

      return c;
    }
  },
  methods: {
    click: function click() {
      var _this = this;

      return Object(asyncToGenerator["a" /* default */])( /*#__PURE__*/regeneratorRuntime.mark(function _callee() {
        return regeneratorRuntime.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                if (!_this.confirm) {
                  _context.next = 12;
                  break;
                }

                _context.prev = 1;
                _context.next = 4;
                return _this.$confirm(_this.confirmMessage);

              case 4:
                window.self.location = _this.href;
                _context.next = 10;
                break;

              case 7:
                _context.prev = 7;
                _context.t0 = _context["catch"](1);
                console.log('cancel');

              case 10:
                _context.next = 13;
                break;

              case 12:
                window.self.location = _this.href;

              case 13:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, null, [[1, 7]]);
      }))();
    }
  }
});
// CONCATENATED MODULE: ./src/r-a.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_r_avue_type_script_lang_js_ = (r_avue_type_script_lang_js_); 
// EXTERNAL MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
var componentNormalizer = __webpack_require__("2877");

// CONCATENATED MODULE: ./src/r-a.vue





/* normalize component */

var component = Object(componentNormalizer["a" /* default */])(
  src_r_avue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var r_a = __webpack_exports__["default"] = (component.exports);

/***/ })

}]);
//# sourceMappingURL=alt-component.umd.2.js.map