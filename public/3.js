(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[3],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/School.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/School.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************/
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
      scholen: [],
      schooltypes: [],
      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        name: "",
        schooltype: "",
        adres: "",
        abbreviation: "",
        logo_filename: "",
        postcode: -1
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000
    };
  },
  methods: {
    imgUrl: function imgUrl(item) {
      return "http://www.skbl.be/joomla/images/logo/logo-scholen/" + item.logo_filename;
    },
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
      this.editedIndex = this.scholen.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem: function deleteItem(item) {
      if (confirm("School echt verwijderen?")) {
        var app = this;
        var index = this.scholen.indexOf(item);
        axios["delete"]('/api/v1/school/' + item.id).then(function (resp) {
          app.scholen.splice(index, 1);
          app.successSnack("School verwijderd");
        })["catch"](function (resp) {
          app.failSnack("Verwijderen mislukt");
        });
      }
    },
    save: function save() {
      var app = this;

      if (this.editedIndex > -1) {
        Object.assign(this.scholen[this.editedIndex], this.editedItem);
        axios.patch("/api/v1/school/" + this.editedItem.id, this.editedItem).then(function (resp) {
          //app.$router.push({ path: "/scholen" });
          app.successSnack("Wijzigingen opgeslagen");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
      } else {
        this.scholen.push(this.editedItem);
        axios.post("/api/v1/school", this.editedItem).then(function (resp) {
          //app.$router.push({ path: "/scholen" });
          app.successSnack("School toegevoegd");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij aanmaken school");
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
      return this.editedIndex === -1 ? "Nieuwe school toevoegen" : "Bewerk schoolgegevens";
    }
  },
  created: function created() {
    var app = this;
    axios.get("/api/v1/school").then(function (resp) {
      app.scholen = resp.data.scholen;
      app.schooltypes = resp.data.schooltypes;
    })["catch"](function (resp) {
      console.log(resp);
      alert("Could not load schools");
    });
    this.headers = [{
      text: "",
      align: "center",
      value: "logo",
      width: "30px"
    }, {
      text: "Naam",
      align: "left",
      value: "name"
    }, {
      text: "Afkorting",
      align: "left",
      value: "abbreviation"
    }, {
      text: "",
      align: "center",
      value: "action"
    }];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component School.vue created.");
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/School.vue?vue&type=template&id=74c62f9f&":
/*!*********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/School.vue?vue&type=template&id=74c62f9f& ***!
  \*********************************************************************************************************************************************************************************************************/
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
                  _vm._v("\n        Scholen overzicht\n        "),
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
                    attrs: { items: _vm.scholen, headers: _vm.headers },
                    scopedSlots: _vm._u([
                      {
                        key: "item.logo",
                        fn: function(ref) {
                          var item = ref.item
                          return [
                            item.logo_filename != "nologo"
                              ? _c("img", {
                                  attrs: {
                                    src: _vm.imgUrl(item),
                                    height: "25px",
                                    width: "25px"
                                  }
                                })
                              : _vm._e()
                          ]
                        }
                      },
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
                            { attrs: { cols: "12", sm: "6", md: "4" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Afkorting" },
                                model: {
                                  value: _vm.editedItem.abbreviation,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "abbreviation",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.abbreviation"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
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
                                attrs: { label: "Adres" },
                                model: {
                                  value: _vm.editedItem.adres,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "adres", $$v)
                                  },
                                  expression: "editedItem.adres"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "6", md: "4" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Postcode" },
                                model: {
                                  value: _vm.editedItem.postcode,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "postcode", $$v)
                                  },
                                  expression: "editedItem.postcode"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "8", md: "8" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Gemeente" },
                                model: {
                                  value: _vm.editedItem.gemeente,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "gemeente", $$v)
                                  },
                                  expression: "editedItem.gemeente"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "6", md: "6" } },
                            [
                              _c("v-select", {
                                attrs: {
                                  items: _vm.schooltypes,
                                  "item-text": "naam",
                                  "item-value": "id",
                                  "item-key": "id",
                                  label: "Type"
                                },
                                model: {
                                  value: _vm.editedItem.school_type_id,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "school_type_id",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.school_type_id"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "6", md: "6" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Logo" },
                                model: {
                                  value: _vm.editedItem.logo_filename,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "logo_filename",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.logo_filename"
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
                    [_vm._v("Cancel")]
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", text: "" },
                      on: { click: _vm.save }
                    },
                    [_vm._v("Save")]
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

/***/ "./resources/js/components/School.vue":
/*!********************************************!*\
  !*** ./resources/js/components/School.vue ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _School_vue_vue_type_template_id_74c62f9f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./School.vue?vue&type=template&id=74c62f9f& */ "./resources/js/components/School.vue?vue&type=template&id=74c62f9f&");
/* harmony import */ var _School_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./School.vue?vue&type=script&lang=js& */ "./resources/js/components/School.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _School_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _School_vue_vue_type_template_id_74c62f9f___WEBPACK_IMPORTED_MODULE_0__["render"],
  _School_vue_vue_type_template_id_74c62f9f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/School.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/School.vue?vue&type=script&lang=js&":
/*!*********************************************************************!*\
  !*** ./resources/js/components/School.vue?vue&type=script&lang=js& ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_School_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./School.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/School.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_School_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/School.vue?vue&type=template&id=74c62f9f&":
/*!***************************************************************************!*\
  !*** ./resources/js/components/School.vue?vue&type=template&id=74c62f9f& ***!
  \***************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_School_vue_vue_type_template_id_74c62f9f___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./School.vue?vue&type=template&id=74c62f9f& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/School.vue?vue&type=template&id=74c62f9f&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_School_vue_vue_type_template_id_74c62f9f___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_School_vue_vue_type_template_id_74c62f9f___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);