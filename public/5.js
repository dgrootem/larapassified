(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/Users.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/Users.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      message: "Some Message",
      users: [],
      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        name: "",
        password: null,
        confirm: null,
        isadmin: false,
        isactive: false
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,
      passRules: [this.passLength],
      confirmRules: [this.comparePassFields],
      show1: false
    };
  },
  methods: {
    comparePassFields: function comparePassFields(field1) {
      if (this.editedItem.password == this.editedItem.confirm) return true;else return "wachtwoorden komen niet overeen";
    },
    passLength: function passLength(field1) {
      if (this.editedItem && this.editedItem.password) {
        if (this.editedItem.password == undefined) return true;
        if (this.editedItem.password.length >= 8) return true;else return "wachtwoord moet minstens 8 tekens lang zijn";
      }

      return true;
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
      this.editedIndex = this.users.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem: function deleteItem(item) {
      if (confirm("Gebruiker echt verwijderen?")) {
        var app = this;
        var index = this.users.indexOf(item);
        axios["delete"]("/api/v1/user/" + item.id).then(function (resp) {
          app.users.splice(index, 1);
          app.successSnack("Gebruiker verwijderd");
        })["catch"](function (resp) {
          app.failSnack("Verwijderen mislukt");
        });
      }
    },
    save: function save() {
      var app = this;

      if (this.editedIndex > -1) {
        Object.assign(this.users[this.editedIndex], this.editedItem);
        axios.patch("/api/v1/user/" + this.editedItem.id, this.editedItem).then(function (resp) {
          //app.$router.push({ path: "/scholen" });
          app.successSnack("Wijzigingen opgeslagen");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
      } else {
        axios.post("/api/v1/user", this.editedItem).then(function (resp) {
          //app.$router.push({ path: "/scholen" });
          app.users.push(resp.data);
          app.successSnack("Gebruiker toegevoegd");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij aanmaken user");
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
      return this.editedIndex === -1 ? "Nieuwe gebruiker toevoegen" : "Bewerk gebruiker";
    }
  },
  created: function created() {
    var app = this;
    axios.get("/api/v1/user").then(function (resp) {
      app.users = resp.data;
    })["catch"](function (resp) {
      console.log(resp);
      alert("Could not load users");
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/Users.vue?vue&type=template&id=fa2043a6&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/Users.vue?vue&type=template&id=fa2043a6& ***!
  \**************************************************************************************************************************************************************************************************************/
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
                  _vm._v("\n        Gebruikers overzicht\n        "),
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
                    attrs: { items: _vm.users, headers: _vm.headers },
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
                            { attrs: { cols: "12", sm: "8", md: "8" } },
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
                            { attrs: { cols: "12", sm: "8", md: "8" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "email" },
                                model: {
                                  value: _vm.editedItem.email,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "email", $$v)
                                  },
                                  expression: "editedItem.email"
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
                                attrs: {
                                  type: _vm.show1 ? "text" : "password",
                                  label: "nieuw wachtwoord",
                                  rules: _vm.passRules,
                                  "append-icon": _vm.show1
                                    ? "visibility"
                                    : "visibility_off",
                                  counter: ""
                                },
                                on: {
                                  "click:append": function($event) {
                                    _vm.show1 = !_vm.show1
                                  }
                                },
                                model: {
                                  value: _vm.editedItem.password,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "password", $$v)
                                  },
                                  expression: "editedItem.password"
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
                                attrs: {
                                  type: _vm.show1 ? "text" : "password",
                                  label: "bevestig",
                                  rules: _vm.confirmRules,
                                  "append-icon": _vm.show1
                                    ? "visibility"
                                    : "visibility_off"
                                },
                                on: {
                                  "click:append": function($event) {
                                    _vm.show1 = !_vm.show1
                                  }
                                },
                                model: {
                                  value: _vm.editedItem.confirm,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "confirm", $$v)
                                  },
                                  expression: "editedItem.confirm"
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
                              _c("v-checkbox", {
                                attrs: { label: "Beheerder" },
                                model: {
                                  value: _vm.editedItem.isadmin,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "isadmin", $$v)
                                  },
                                  expression: "editedItem.isadmin"
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
                              _c("v-checkbox", {
                                attrs: { label: "Actief" },
                                model: {
                                  value: _vm.editedItem.isactive,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "isactive", $$v)
                                  },
                                  expression: "editedItem.isactive"
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

/***/ "./resources/js/components/admin/Users.vue":
/*!*************************************************!*\
  !*** ./resources/js/components/admin/Users.vue ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Users_vue_vue_type_template_id_fa2043a6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Users.vue?vue&type=template&id=fa2043a6& */ "./resources/js/components/admin/Users.vue?vue&type=template&id=fa2043a6&");
/* harmony import */ var _Users_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Users.vue?vue&type=script&lang=js& */ "./resources/js/components/admin/Users.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Users_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Users_vue_vue_type_template_id_fa2043a6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Users_vue_vue_type_template_id_fa2043a6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/admin/Users.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/admin/Users.vue?vue&type=script&lang=js&":
/*!**************************************************************************!*\
  !*** ./resources/js/components/admin/Users.vue?vue&type=script&lang=js& ***!
  \**************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Users_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./Users.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/Users.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Users_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/admin/Users.vue?vue&type=template&id=fa2043a6&":
/*!********************************************************************************!*\
  !*** ./resources/js/components/admin/Users.vue?vue&type=template&id=fa2043a6& ***!
  \********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Users_vue_vue_type_template_id_fa2043a6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./Users.vue?vue&type=template&id=fa2043a6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/Users.vue?vue&type=template&id=fa2043a6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Users_vue_vue_type_template_id_fa2043a6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Users_vue_vue_type_template_id_fa2043a6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);