module.exports =
/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "8e4d");
/******/ })
/************************************************************************/
/******/ ({

/***/ "527c":
/***/ (function(module, exports) {

// document.currentScript polyfill by Adam Miller

// MIT license

(function(document){
  var currentScript = "currentScript",
      scripts = document.getElementsByTagName('script'); // Live NodeList collection

  // If browser needs currentScript polyfill, add get currentScript() to the document object
  if (!(currentScript in document)) {
    Object.defineProperty(document, currentScript, {
      get: function(){

        // IE 6-10 supports script readyState
        // IE 10+ support stack trace
        try { throw new Error(); }
        catch (err) {

          // Find the second match for the "at" string to get file src url from stack.
          // Specifically works with the format of stack traces in IE.
          var i, res = ((/.*at [^\(]*\((.*):.+:.+\)$/ig).exec(err.stack) || [false])[1];

          // For all scripts on the page, if src matches or if ready state is interactive, return the script tag
          for(i in scripts){
            if(scripts[i].src == res || scripts[i].readyState == "interactive"){
              return scripts[i];
            }
          }

          // If no match, return null
          return null;
        }
      }
    });
  }
})(document);


/***/ }),

/***/ "8e4d":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);

// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  if (true) {
    __webpack_require__("527c")
  }

  var i
  if ((i = window.document.currentScript) && (i = i.src.match(/(.+\/)[^/]+\.js(\?.*)?$/))) {
    __webpack_require__.p = i[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// EXTERNAL MODULE: ./node_modules/vue-register-element/dist/main.js
var main = __webpack_require__("dfce");
var main_default = /*#__PURE__*/__webpack_require__.n(main);

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"0cda3038-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Card.vue?vue&type=template&id=7f2f6af8&
var render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"card"},[_vm._t("default")],2)}
var staticRenderFns = []


// CONCATENATED MODULE: ./src/Card.vue?vue&type=template&id=7f2f6af8&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/Card.vue?vue&type=script&lang=js&
//
//
//
//
//
//
/* harmony default export */ var Cardvue_type_script_lang_js_ = ({
  name: "card",
  mounted: function mounted() {
    console.log("card");
  }
});
// CONCATENATED MODULE: ./src/Card.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_Cardvue_type_script_lang_js_ = (Cardvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./node_modules/vue-loader/lib/runtime/componentNormalizer.js
/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file (except for modules).
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

function normalizeComponent (
  scriptExports,
  render,
  staticRenderFns,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier, /* server only */
  shadowMode /* vue-cli only */
) {
  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (render) {
    options.render = render
    options.staticRenderFns = staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = 'data-v-' + scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = shadowMode
      ? function () { injectStyles.call(this, this.$root.$options.shadowRoot) }
      : injectStyles
  }

  if (hook) {
    if (options.functional) {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      var originalRender = options.render
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return originalRender(h, context)
      }
    } else {
      // inject component registration as beforeCreate hook
      var existing = options.beforeCreate
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    }
  }

  return {
    exports: scriptExports,
    options: options
  }
}

// CONCATENATED MODULE: ./src/Card.vue





/* normalize component */

var component = normalizeComponent(
  src_Cardvue_type_script_lang_js_,
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var Card = (component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"0cda3038-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/CardBody.vue?vue&type=template&id=7d6a8482&
var CardBodyvue_type_template_id_7d6a8482_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"card-body"},[_vm._t("default")],2)}
var CardBodyvue_type_template_id_7d6a8482_staticRenderFns = []


// CONCATENATED MODULE: ./src/CardBody.vue?vue&type=template&id=7d6a8482&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/CardBody.vue?vue&type=script&lang=js&
//
//
//
//
//
//
/* harmony default export */ var CardBodyvue_type_script_lang_js_ = ({
  name: "card-body"
});
// CONCATENATED MODULE: ./src/CardBody.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_CardBodyvue_type_script_lang_js_ = (CardBodyvue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/CardBody.vue





/* normalize component */

var CardBody_component = normalizeComponent(
  src_CardBodyvue_type_script_lang_js_,
  CardBodyvue_type_template_id_7d6a8482_render,
  CardBodyvue_type_template_id_7d6a8482_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var CardBody = (CardBody_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"0cda3038-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/CardHeader.vue?vue&type=template&id=e9ac99d2&
var CardHeadervue_type_template_id_e9ac99d2_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"card-header"},[_c('h3',{staticClass:"card-title"},[_vm._t("default")],2)])}
var CardHeadervue_type_template_id_e9ac99d2_staticRenderFns = []


// CONCATENATED MODULE: ./src/CardHeader.vue?vue&type=template&id=e9ac99d2&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/CardHeader.vue?vue&type=script&lang=js&
//
//
//
//
//
//
//
//
/* harmony default export */ var CardHeadervue_type_script_lang_js_ = ({
  name: "card-header"
});
// CONCATENATED MODULE: ./src/CardHeader.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_CardHeadervue_type_script_lang_js_ = (CardHeadervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/CardHeader.vue





/* normalize component */

var CardHeader_component = normalizeComponent(
  src_CardHeadervue_type_script_lang_js_,
  CardHeadervue_type_template_id_e9ac99d2_render,
  CardHeadervue_type_template_id_e9ac99d2_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var CardHeader = (CardHeader_component.exports);
// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js?{"cacheDirectory":"node_modules/.cache/vue-loader","cacheIdentifier":"0cda3038-vue-loader-template"}!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/CardFooter.vue?vue&type=template&id=5365b4aa&
var CardFootervue_type_template_id_5365b4aa_render = function () {var _vm=this;var _h=_vm.$createElement;var _c=_vm._self._c||_h;return _c('div',{staticClass:"card-footer"},[_vm._t("default")],2)}
var CardFootervue_type_template_id_5365b4aa_staticRenderFns = []


// CONCATENATED MODULE: ./src/CardFooter.vue?vue&type=template&id=5365b4aa&

// CONCATENATED MODULE: ./node_modules/cache-loader/dist/cjs.js??ref--12-0!./node_modules/thread-loader/dist/cjs.js!C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-plugin-babel/node_modules/babel-loader/lib!./node_modules/cache-loader/dist/cjs.js??ref--0-0!./node_modules/vue-loader/lib??vue-loader-options!./src/CardFooter.vue?vue&type=script&lang=js&
//
//
//
//
//
//
/* harmony default export */ var CardFootervue_type_script_lang_js_ = ({
  name: "card-footer"
});
// CONCATENATED MODULE: ./src/CardFooter.vue?vue&type=script&lang=js&
 /* harmony default export */ var src_CardFootervue_type_script_lang_js_ = (CardFootervue_type_script_lang_js_); 
// CONCATENATED MODULE: ./src/CardFooter.vue





/* normalize component */

var CardFooter_component = normalizeComponent(
  src_CardFootervue_type_script_lang_js_,
  CardFootervue_type_template_id_5365b4aa_render,
  CardFootervue_type_template_id_5365b4aa_staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* harmony default export */ var CardFooter = (CardFooter_component.exports);
// CONCATENATED MODULE: ./src/main.js





main_default()("card", Card);
main_default()("card-body", CardBody);
main_default()("card-header", CardHeader);
main_default()("card-footer", CardFooter);
// CONCATENATED MODULE: C:/Users/maths/AppData/Roaming/npm/node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ }),

/***/ "dfce":
/***/ (function(module, exports, __webpack_require__) {

!function(e,t){ true?module.exports=t():undefined}(window,function(){return function(e){var t={};function r(n){if(t[n])return t[n].exports;var o=t[n]={i:n,l:!1,exports:{}};return e[n].call(o.exports,o,o.exports,r),o.l=!0,o.exports}return r.m=e,r.c=t,r.d=function(e,t,n){r.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:n})},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},r.t=function(e,t){if(1&t&&(e=r(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var n=Object.create(null);if(r.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var o in e)r.d(n,o,function(t){return e[t]}.bind(null,o));return n},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,"a",t),t},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.p="",r(r.s=0)}([function(e,t,r){"use strict";r.r(t);t.default=function(e,t){Vue=window.Vue,Vue.component(e,t),Vue.rootVueComponents=[],document.addEventListener("DOMContentLoaded",function(){new MutationObserver(function(t){var r=!0,n=!1,o=void 0;try{for(var u,l=t[Symbol.iterator]();!(r=(u=l.next()).done);r=!0){var i=u.value;if("childList"==i.type){var a=!0,f=!1,d=void 0;try{for(var y,c=i.addedNodes[Symbol.iterator]();!(a=(y=c.next()).done);a=!0){var s=y.value;if(s.nodeName.toLowerCase()==e&&new Vue({el:s}),1==s.nodeType){s.getAttribute("is")==e&&Vue.rootVueComponents.push(new Vue({el:s}));var p=!0,v=!1,b=void 0;try{for(var m,V=s.querySelectorAll("[is='"+e+"']")[Symbol.iterator]();!(p=(m=V.next()).done);p=!0){var h=m.value;Vue.rootVueComponents.push(new Vue({el:h}))}}catch(e){v=!0,b=e}finally{try{p||null==V.return||V.return()}finally{if(v)throw b}}var w=!0,x=!1,S=void 0;try{for(var g,j=s.querySelectorAll(e)[Symbol.iterator]();!(w=(g=j.next()).done);w=!0){var C=g.value;Vue.rootVueComponents.push(new Vue({el:C}))}}catch(e){x=!0,S=e}finally{try{w||null==j.return||j.return()}finally{if(x)throw S}}}}}catch(e){f=!0,d=e}finally{try{a||null==c.return||c.return()}finally{if(f)throw d}}}else"attributes"==i.type&&"is"==i.attributeName&&i.target.getAttribute("is")==e&&Vue.rootVueComponents.push(new Vue({el:i.target}))}}catch(e){n=!0,o=e}finally{try{r||null==l.return||l.return()}finally{if(n)throw o}}}).observe(document.body,{attributes:!0,childList:!0,subtree:!0});var t=!0,r=!1,n=void 0;try{for(var o,u=document.body.querySelectorAll(e)[Symbol.iterator]();!(t=(o=u.next()).done);t=!0){var l=o.value;Vue.rootVueComponents.push(new Vue({el:l}))}}catch(e){r=!0,n=e}finally{try{t||null==u.return||u.return()}finally{if(r)throw n}}var i=!0,a=!1,f=void 0;try{for(var d,y=document.body.querySelectorAll("[is='"+e+"']")[Symbol.iterator]();!(i=(d=y.next()).done);i=!0){var c=d.value;Vue.rootVueComponents.push(new Vue({el:c}))}}catch(e){a=!0,f=e}finally{try{i||null==y.return||y.return()}finally{if(a)throw f}}})}}])});

/***/ })

/******/ });
//# sourceMappingURL=alt-component.common.js.map