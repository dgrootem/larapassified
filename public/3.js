(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[3],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Employee.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Employee.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/index.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//import moment from "moment";

/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      message: "Some Message",
      employees: [],
      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        firstName: "",
        lastName: "",
        registrationNumber: "",
        birthDate: null,
        isActive: true,
        startwaarde: 0
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,
      search: "" // registrationNumberProxy : null

    };
  },
  methods: {
    calcBdate: function calcBdate() {
      if (this.editedItem.registrationNumber.length == 11) {
        if (!isNaN(this.editedItem.registrationNumber)) {
          //only do something when it is a number
          var bd = this.editedItem.registrationNumber.substring(1, 7);
          var year = bd.substring(0, 2);
          if (parseInt(year) > 40) year = "19" + year;else year = "20" + year;
          this.editedItem.birthDate = Object(date_fns__WEBPACK_IMPORTED_MODULE_0__["parse"])(bd.substring(4, 6) + "-" + bd.substring(2, 4) + "-" + year, 'dd-MM-YYYY');
        }
      }
    },
    parseDate: function parseDate(val) {
      if (val && val.length >= 10) {
        var d = val.substring(0, 10);
        var pd = Object(date_fns__WEBPACK_IMPORTED_MODULE_0__["parse"])(d, "dd-MM-yyyy", new Date());
        return pd;
      } else return null;
    },
    formatDate: function formatDate(date) {
      if (date && date.length >= 10) {
        var d = this.parseDate(date);
        var f = Object(date_fns__WEBPACK_IMPORTED_MODULE_0__["format"])(d, "dd-MM-yyyy");
        return f;
      } else return null;
    },
    formatDateFromDB: function formatDateFromDB(date) {
      if (date && date.length >= 10) {
        var d = date.substring(0, 10);
        console.log(d);
        return Object(date_fns__WEBPACK_IMPORTED_MODULE_0__["format"])(Object(date_fns__WEBPACK_IMPORTED_MODULE_0__["parse"])(d, "yyyy-MM-dd", new Date()), "dd-MM-yyyy"); //let f = format(parse(date.substring(0,10), "yyyy-MM-dd", new Date()), "dd-MM-yyyy"); //   moment(date, "YYYY-MM-DD hh:mi:ss").format("DD-MM-YYYY");
        //return f;
      } else return null;
    },
    // formatDate: function(value) {
    //   if (value) {
    //     return moment(String(value)).format("MM-DD-YYYY");
    //   }
    // },
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
      this.editedIndex = this.employees.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem: function deleteItem(item) {
      if (confirm("Personeelslid echt verwijderen?")) {
        var app = this;
        var index = this.employees.indexOf(item);
        axios["delete"]("/api/v1/employee/" + item.id).then(function (resp) {
          app.employees.splice(index, 1);
          app.successSnack("Personeelslid verwijderd");
        })["catch"](function (resp) {
          app.failSnack("Verwijderen mislukt");
        });
      }
    },
    toggleVisibility: function toggleVisibility(item) {
      var app = this; //first copy to editItem, which we will send to the server for processing
      //and we only update the model when server successfully processes data

      this.editedItem = Object.assign({}, item);
      this.editedItem.isActive = !this.editedItem.isActive;
      this.editedIndex = this.employees.indexOf(item);
      axios.patch("/api/v1/employee/" + this.editedItem.id, this.editedItem).then(function (resp) {
        //app.$router.push({ path: "/employees" });
        Object.assign(app.employees[app.editedIndex], resp.data);
        app.successSnack("Wijzigingen opgeslagen");
      })["catch"](function (resp) {
        console.log(resp);
        app.failSnack("Fout bij opslaan wijzigingen");
      });
    },
    save: function save() {
      var app = this;

      if (this.editedIndex > -1) {
        axios.patch("/api/v1/employee/" + this.editedItem.id, this.editedItem).then(function (resp) {
          Object.assign(app.employees[app.editedIndex], resp.data);
          app.successSnack("Wijzigingen opgeslagen");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
      } else {
        axios.post("/api/v1/employee", this.editedItem).then(function (resp) {
          app.employees.push(resp.data);
          app.successSnack("Personeelslid toegevoegd");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij aanmaken personeelslid");
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
      return this.editedIndex === -1 ? "Nieuw personeelslid toevoegen" : "Bewerk gegevens";
    }
  },
  created: function created() {
    var app = this;
    axios.get("/api/v1/employee").then(function (resp) {
      app.employees = resp.data;
    })["catch"](function (resp) {
      console.log(resp);
      alert("Could not load personeelsleden");
    });
    this.headers = [{
      text: "Achternaam",
      align: "left",
      value: "lastName"
    }, {
      text: "Voornaam",
      align: "left",
      value: "firstName"
    }, {
      text: "Stamboeknummer",
      align: "left",
      value: "registrationNumber"
    }, {
      text: "Geboortedatum",
      align: "left",
      value: "birthDate"
    }, {
      text: "Startwaarde",
      align: "right",
      value: "startwaarde"
    }, {
      text: "",
      align: "center",
      value: "action"
    }];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component personeelslid.vue created.");
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Employee.vue?vue&type=template&id=99aa15ce&":
/*!***********************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Employee.vue?vue&type=template&id=99aa15ce& ***!
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
                  _vm._v("\n        Personeelsleden\n        "),
                  _c("div", { staticClass: "flex-grow-1" }),
                  _vm._v(" "),
                  _c("v-text-field", {
                    attrs: {
                      "append-icon": "search",
                      label: "Zoeken",
                      "single-line": "",
                      "hide-details": ""
                    },
                    model: {
                      value: _vm.search,
                      callback: function($$v) {
                        _vm.search = $$v
                      },
                      expression: "search"
                    }
                  }),
                  _vm._v(" "),
                  _c("div", { staticClass: "flex-grow-1" }),
                  _vm._v(" "),
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
                    attrs: {
                      items: _vm.employees,
                      headers: _vm.headers,
                      search: _vm.search,
                      "multi-sort": ""
                    },
                    scopedSlots: _vm._u([
                      {
                        key: "item.birthDate",
                        fn: function(ref) {
                          var item = ref.item
                          return [
                            _vm._v(_vm._s(_vm.formatDateFromDB(item.birthDate)))
                          ]
                        }
                      },
                      {
                        key: "item.registrationNumber",
                        fn: function(ref) {
                          var item = ref.item
                          return [
                            _vm._v(
                              "\n            " +
                                _vm._s(
                                  item.registrationNumber
                                    ? item.registrationNumber
                                    : ""
                                ) +
                                "\n            "
                            ),
                            !item.registrationNumber
                              ? _c(
                                  "span",
                                  [
                                    _vm._v("Geen Stamboeknummer"),
                                    _c(
                                      "v-icon",
                                      { attrs: { color: "warning" } },
                                      [_vm._v("warning")]
                                    )
                                  ],
                                  1
                                )
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
                                staticClass: "mr-2",
                                attrs: { small: "" },
                                on: {
                                  click: function($event) {
                                    return _vm.deleteItem(item)
                                  }
                                }
                              },
                              [_vm._v("delete")]
                            ),
                            _vm._v(" "),
                            _c(
                              "v-icon",
                              {
                                staticClass: "mr-2",
                                attrs: { small: "" },
                                on: {
                                  click: function($event) {
                                    return _vm.toggleVisibility(item)
                                  }
                                }
                              },
                              [
                                _vm._v(
                                  _vm._s(
                                    item.isActive
                                      ? "visibility"
                                      : "visibility_off"
                                  )
                                )
                              ]
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
                            { attrs: { cols: "12", sm: "6", md: "7" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Achternaam" },
                                model: {
                                  value: _vm.editedItem.lastName,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "lastName", $$v)
                                  },
                                  expression: "editedItem.lastName"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "6", md: "5" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Voornaam" },
                                model: {
                                  value: _vm.editedItem.firstName,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "firstName", $$v)
                                  },
                                  expression: "editedItem.firstName"
                                }
                              })
                            ],
                            1
                          )
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-row",
                        [
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "12", md: "7" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Stamboeknummer" },
                                on: { blur: _vm.calcBdate },
                                model: {
                                  value: _vm.editedItem.registrationNumber,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "registrationNumber",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.registrationNumber"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "6", md: "5" } },
                            [
                              _c("v-text-field", {
                                attrs: {
                                  disabled:
                                    _vm.editedItem.registrationNumber != "",
                                  label: "Geboortedatum"
                                },
                                model: {
                                  value: _vm.editedItem.birthDate,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "birthDate", $$v)
                                  },
                                  expression: "editedItem.birthDate"
                                }
                              })
                            ],
                            1
                          )
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-row",
                        [
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "12", md: "6" } },
                            [
                              _c("v-text-field", {
                                attrs: {
                                  label: "Startwaarde",
                                  type: "number",
                                  min: "0"
                                },
                                model: {
                                  value: _vm.editedItem.startwaarde,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "startwaarde", $$v)
                                  },
                                  expression: "editedItem.startwaarde"
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

/***/ "./resources/js/components/Employee.vue":
/*!**********************************************!*\
  !*** ./resources/js/components/Employee.vue ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Employee_vue_vue_type_template_id_99aa15ce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Employee.vue?vue&type=template&id=99aa15ce& */ "./resources/js/components/Employee.vue?vue&type=template&id=99aa15ce&");
/* harmony import */ var _Employee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Employee.vue?vue&type=script&lang=js& */ "./resources/js/components/Employee.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Employee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Employee_vue_vue_type_template_id_99aa15ce___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Employee_vue_vue_type_template_id_99aa15ce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Employee.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Employee.vue?vue&type=script&lang=js&":
/*!***********************************************************************!*\
  !*** ./resources/js/components/Employee.vue?vue&type=script&lang=js& ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Employee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Employee.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Employee.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Employee_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Employee.vue?vue&type=template&id=99aa15ce&":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/Employee.vue?vue&type=template&id=99aa15ce& ***!
  \*****************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Employee_vue_vue_type_template_id_99aa15ce___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Employee.vue?vue&type=template&id=99aa15ce& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Employee.vue?vue&type=template&id=99aa15ce&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Employee_vue_vue_type_template_id_99aa15ce___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Employee_vue_vue_type_template_id_99aa15ce___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);