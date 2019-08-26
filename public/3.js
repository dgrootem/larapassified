(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[3],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Function.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Function.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      message: "Some Message",
      ambten: [],
      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        name: "",
        denominator: ''
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000
    };
  },
  methods: {
    successSnack: function successSnack(message) {
      this.snack_text = message;
      this.snack_color = "success";
      this.snackbar = true;
    },
    failSnack: function failSnack(message) {
      this.snack_text = message;
      this.snack_color = "error";
      this.snackbar = true;
    },
    editItem: function editItem(item) {
      this.editedIndex = this.ambten.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem: function deleteItem(item) {
      if (confirm("Ambt echt verwijderen?")) {
        var app = this;
        var index = this.ambten.indexOf(item);
        axios["delete"]('/api/v1/ambt/' + item.id).then(function (resp) {
          app.ambten.splice(index, 1);
          app.successSnack("School verwijderd");
        })["catch"](function (resp) {
          app.failSnack("Verwijderen mislukt");
        });
      }
    },
    save: function save() {
      var app = this;

      if (this.editedIndex > -1) {
        Object.assign(this.ambten[this.editedIndex], this.editedItem);
        axios.patch("/api/v1/ambt/" + this.editedItem.id, this.editedItem).then(function (resp) {
          //app.$router.push({ path: "/scholen" });
          app.successSnack("Wijzigingen opgeslagen");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
      } else {
        axios.post("/api/v1/ambt", this.editedItem).then(function (resp) {
          //app.$router.push({ path: "/scholen" });
          app.ambten.push(resp.data);
          app.successSnack("Ambt toegevoegd");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij aanmaken ambt");
        });
      }

      this.close();
    },
    close: function close() {
      var _this = this;

      this.dialog = false;
      setTimeout(function () {
        _this.editedItem = Object.assign({}, _this.defaultItem);
        _this.editedIndex = -1;
      }, 300);
    }
  },
  computed: {
    formTitle: function formTitle() {
      return this.editedIndex === -1 ? "Nieuw ambt toevoegen" : "Bewerk ambt";
    }
  },
  created: function created() {
    var app = this;
    axios.get("/api/v1/ambt").then(function (resp) {
      app.ambten = resp.data;
    })["catch"](function (resp) {
      console.log(resp);
      alert("Could not load ambten");
    });
    this.headers = [{
      text: "Naam",
      align: "left",
      value: "name"
    }, {
      text: "Noemer",
      align: "left",
      value: "denominator"
    }, {
      text: "",
      align: "center",
      value: "action"
    }];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component Function.vue created.");
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Function.vue?vue&type=template&id=0d98dd43&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Function.vue?vue&type=template&id=0d98dd43& ***!
  \***********************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    [
      _c(
        "v-card",
        { attrs: { width: "100%" } },
        [
          _c(
            "v-container",
            { attrs: { fluid: "" } },
            [
              _c(
                "v-card-title",
                [
                  _vm._v("\n        Ambten overzicht\n        "),
                  _c(
                    "v-btn",
                    {
                      attrs: { fab: "", right: "", absolute: "" },
                      on: {
                        click: function($event) {
                          _vm.dialog = !_vm.dialog
                        }
                      }
                    },
                    [_c("v-icon", [_vm._v("add")])],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-card-text",
                [
                  _c("v-data-table", {
                    attrs: { items: _vm.ambten, headers: _vm.headers },
                    scopedSlots: _vm._u([
                      {
                        key: "item.action",
                        fn: function(ref) {
                          var item = ref.item
                          return [
                            _c(
                              "v-icon",
                              {
                                staticClass: "mr-2",
                                attrs: { small: "" },
                                on: {
                                  click: function($event) {
                                    return _vm.editItem(item)
                                  }
                                }
                              },
                              [_vm._v("edit")]
                            ),
                            _vm._v(" "),
                            _c(
                              "v-icon",
                              {
                                attrs: { small: "" },
                                on: {
                                  click: function($event) {
                                    return _vm.deleteItem(item)
                                  }
                                }
                              },
                              [_vm._v("delete")]
                            )
                          ]
                        }
                      }
                    ])
                  })
                ],
                1
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-dialog",
        {
          attrs: { "max-width": "500px" },
          model: {
            value: _vm.dialog,
            callback: function($$v) {
              _vm.dialog = $$v
            },
            expression: "dialog"
          }
        },
        [
          _c(
            "v-card",
            [
              _c("v-card-title", [
                _c("span", { staticClass: "headline" }, [
                  _vm._v(_vm._s(_vm.formTitle))
                ])
              ]),
              _vm._v(" "),
              _c(
                "v-card-text",
                [
                  _c(
                    "v-container",
                    [
                      _c(
                        "v-row",
                        [
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "6", md: "8" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Naam" },
                                model: {
                                  value: _vm.editedItem.name,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "name", $$v)
                                  },
                                  expression: "editedItem.name"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "12", md: "12" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Noemer" },
                                model: {
                                  value: _vm.editedItem.denominator,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "denominator", $$v)
                                  },
                                  expression: "editedItem.denominator"
                                }
                              })
                            ],
                            1
                          )
                        ],
                        1
                      )
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-card-actions",
                [
                  _c("div", { staticClass: "flex-grow-1" }),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", text: "" },
                      on: { click: _vm.close }
                    },
                    [_vm._v("Annuleren")]
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", text: "" },
                      on: { click: _vm.save }
                    },
                    [_vm._v("Opslaan")]
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-snackbar",
        {
          attrs: {
            bottom: "",
            color: _vm.snack_color,
            timeout: _vm.snack_timeout
          },
          model: {
            value: _vm.snackbar,
            callback: function($$v) {
              _vm.snackbar = $$v
            },
            expression: "snackbar"
          }
        },
        [
          _vm._v("\n    " + _vm._s(_vm.snack_text) + "\n    "),
          _c(
            "v-btn",
            {
              attrs: { dark: "", text: "" },
              on: {
                click: function($event) {
                  _vm.snackbar = false
                }
              }
            },
            [_vm._v("Close")]
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/Function.vue":
/*!**********************************************!*\
  !*** ./resources/js/components/Function.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Function_vue_vue_type_template_id_0d98dd43___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Function.vue?vue&type=template&id=0d98dd43& */ "./resources/js/components/Function.vue?vue&type=template&id=0d98dd43&");
/* harmony import */ var _Function_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Function.vue?vue&type=script&lang=js& */ "./resources/js/components/Function.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Function_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Function_vue_vue_type_template_id_0d98dd43___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Function_vue_vue_type_template_id_0d98dd43___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Function.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Function.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/components/Function.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Function_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Function.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Function.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Function_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Function.vue?vue&type=template&id=0d98dd43&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/Function.vue?vue&type=template&id=0d98dd43& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Function_vue_vue_type_template_id_0d98dd43___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Function.vue?vue&type=template&id=0d98dd43& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Function.vue?vue&type=template&id=0d98dd43&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Function_vue_vue_type_template_id_0d98dd43___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Function_vue_vue_type_template_id_0d98dd43___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);