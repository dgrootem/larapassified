(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Dashboard.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _FunctionData_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FunctionData.vue */ "./resources/js/components/FunctionData.vue");
/* harmony import */ var date_fns__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! date-fns */ "./node_modules/date-fns/esm/index.js");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  components: {
    functiondatacomp: _FunctionData_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      //model entities
      functiondata: [],
      interruptions: [],
      ambten: [],
      //selection and editing
      editedItem: {},
      defaultFunctionData: {
        educational_function_id: -1,
        employee_id: this.selectedEmployee
      },
      defaultInterruption: {
        beginDate: new Date(),
        formattedBegin: null,
        endDate: new Date(),
        formattedEnd: null,
        employee_id: this.selectedEmployee,
        type: 1
      },
      //autocomplete stuff
      entries: [],
      isLoading: false,
      selectedEmployee: null,
      //model for autocomplete
      search: null,
      scholen: [],
      fab: false,
      //tab stuff
      functiondatatab: null,
      loadingtabs: false,
      // interruptiontab: null,
      snack_color: null,
      snack_timeout: 2000,
      snackbar: false,
      snack_text: "",
      functionDataDialog: false,
      interruptionDialog: false,
      interruptionheaders: [{
        text: "Begin",
        align: "left",
        value: "beginDate"
      }, {
        text: "Einde",
        align: "left",
        value: "endDate"
      }, {
        text: "Telt mee",
        align: "left",
        value: "type"
      }, {
        text: "",
        align: "center",
        value: "action"
      }],
      // employmentDialog: false,

      /*datepickerMenu1 : false,
      datepickerMenu2 : false,
      datepickerMenu3 : false,
      datepickerMenu4 : false,
      */
      descriptionLimit: 45
    };
  },
  computed: {
    formTitleFD: function formTitleFD() {
      return this.editedIndex === -1 ? "Nieuwe functie" : "Bewerk functie";
    },
    formTitleInterruption: function formTitleInterruption() {
      return this.editedIndex === -1 ? "Nieuwe onderbreking" : "Bewerk onderbrekeing";
    },

    /*
    formattedBegin: {
      get() {
        if (this.editedItem && this.editedItem.beginDate)
          return this.editedItem.beginDate.format("DD-MM-YYYY");
        else return "";
      },
      set(val) {
        this.editedItem.beginDate = moment(val, "DD-MM-YYYY");
      }
    },
    formattedEnd: {
      get() {
        if (this.editedItem && this.editedItem.endDate)
          return this.editedItem.endDate.format("DD-MM-YYYY");
        else return "";
      },
      set(val) {
        this.editedItem.endDate = moment(val, "DD-MM-YYYY");
      }
    },*/
    items: function items() {
      var _this = this;

      //debugger;
      return this.entries.map(function (entry) {
        var Fullname = entry.fullname.length > _this.descriptionLimit ? entry.fullname.slice(0, _this.descriptionLimit) + "..." : entry.fullname;
        return Object.assign({}, entry, {
          Fullname: Fullname
        });
      });
    }
  },
  methods: {
    parseDate: function parseDate(val) {
      if (val && val.length >= 10) {
        var d = val.substring(0, 10);
        var pd = Object(date_fns__WEBPACK_IMPORTED_MODULE_1__["parse"])(d, "dd-MM-yyyy", new Date());
        return pd;
      } else return null;
    },
    formatDate: function formatDate(date) {
      if (date && date.length >= 10) {
        var d = this.parseDate(date);
        var f = Object(date_fns__WEBPACK_IMPORTED_MODULE_1__["format"])(d, "dd-MM-yyyy");
        return f;
      } else return null;
    },
    formatDateFromDB: function formatDateFromDB(date) {
      if (date && date.length >= 10) {
        var d = date.substring(0, 10);
        console.log(d);
        return Object(date_fns__WEBPACK_IMPORTED_MODULE_1__["format"])(Object(date_fns__WEBPACK_IMPORTED_MODULE_1__["parse"])(d, "yyyy-MM-dd", new Date()), "dd-MM-yyyy"); //let f = format(parse(date.substring(0,10), "yyyy-MM-dd", new Date()), "dd-MM-yyyy"); //   moment(date, "YYYY-MM-DD hh:mi:ss").format("DD-MM-YYYY");
        //return f;
      } else return null;
    },
    setBegin: function setBegin() {
      this.editedItem.beginDate = this.parseDate(this.editedItem.formattedBegin + " 12:00:00");
    },
    setEnd: function setEnd() {
      this.editedItem.endDate = this.parseDate(this.editedItem.formattedEnd + " 12:00:00");
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
    setAvailableFunctions: function setAvailableFunctions() {
      var app = this;
      axios.get("/api/v1/ambt/availableForEmployee/" + this.selectedEmployee.id).then(function (resp) {
        app.ambten = resp.data;
        app.functiondatatab = 0;
      })["catch"](function (resp) {
        console.log(resp);
        alert("Could not load functions for employee");
      });
    },
    addFunctionData: function addFunctionData() {
      this.editedIndex = -1;
      this.setAvailableFunctions();
      this.editedItem = Object.assign({}, this.defaultFunctionData);
      this.editedItem.employee_id = this.selectedEmployee.id;
      this.functionDataDialog = true;
    },
    editFunctionData: function editFunctionData(item) {
      this.editedIndex = this.functiondata.indexOf(item);
      this.setAvailableFunctions();
      this.editedItem = Object.assign({}, item);
      this.functionDataDialog = true;
    },
    saveFunctionData: function saveFunctionData() {
      var app = this;
      if (this.editedIndex == -1) axios.post("/api/v1/educationalFunctionData", this.editedItem).then(function (resp) {
        //app.$router.push({ path: "/employees" });
        app.functiondata.push(resp.data);
        app.successSnack("Aanstelling toegevoegd");
      })["catch"](function (resp) {
        console.log(resp);
        app.failSnack("Fout bij aanmaken aanstelling");
      });else {
        delete this.editedItem.educational_function; //remove this property before sending it to the server to prevent mixups

        axios.patch("/api/v1/educationalFunctionData/" + this.editedItem.id, this.editedItem).then(function (resp) {
          //to keep reactivity
          Vue.set(app.functiondata, app.editedIndex, Object.assign({}, resp.data));
          app.successSnack("Wijzigingen opgeslagen");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
      }
      this.closeFunctionData();
    },
    closeFunctionData: function closeFunctionData() {
      var _this2 = this;

      this.functionDataDialog = false;
      setTimeout(function () {
        _this2.editedItem = Object.assign({}, _this2.defaultFunctionData);
        _this2.editedIndex = -1;
      }, 300);
    },
    deleteFunctionData: function deleteFunctionData(fdata) {
      var yes = confirm("Ambt verwijderen?");

      if (yes) {
        var app = this;
        var index = this.functiondata.indexOf(fdata);
        axios["delete"]("/api/v1/educationalFunctionData/" + fdata.id).then(function (resp) {
          app.functiondata.splice(index, 1);
          app.successSnack("Ambt verwijderd");
        })["catch"](function (resp) {
          app.failSnack("Verwijderen mislukt");
        });
      }
    },
    addInterruption: function addInterruption() {
      this.editedIndex = -1;
      this.editedItem = Object.assign({}, this.defaultInterruption);
      this.editedItem.employee_id = this.selectedEmployee.id;
      this.interruptionDialog = true;
    },
    editInterruption: function editInterruption(item) {
      this.editedIndex = this.interruptions.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.formattedBegin = this.formatDateFromDB(this.editedItem.beginDate);
      this.editedItem.formattedEnd = this.formatDateFromDB(this.editedItem.endDate);
      this.interruptionDialog = true;
    },
    saveInterruption: function saveInterruption() {
      var app = this;
      if (this.editedIndex == -1) axios.post("/api/v1/employmentInterruption", this.editedItem).then(function (resp) {
        //app.$router.push({ path: "/employees" });
        app.interruptions.push(resp.data);
        app.successSnack("Onderbreking toegevoegd");
      })["catch"](function (resp) {
        console.log(resp);
        app.failSnack("Fout bij aanmaken onderbreking");
      });else {
        delete this.editedItem.educational_function; //remove this property before sending it to the server to prevent mixups

        axios.patch("/api/v1/employmentInterruption/" + this.editedItem.id, this.editedItem).then(function (resp) {
          //to keep reactivity
          Vue.set(app.interruptions, app.editedIndex, Object.assign({}, resp.data));
          app.successSnack("Wijzigingen opgeslagen");
        })["catch"](function (resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
      }
      this.closeInterruption();
    },
    closeInterruption: function closeInterruption() {
      this.interruptionDialog = false;
    },
    deleteInterruption: function deleteInterruption(item) {
      var yes = confirm("Onderbreking verwijderen?");

      if (yes) {
        var app = this;
        var index = this.interruptions.indexOf(item);
        axios["delete"]("/api/v1/employmentInterruption/" + item.id).then(function (resp) {
          app.interruptions.splice(index, 1);
          app.successSnack("Onderbreking verwijderd");
        })["catch"](function (resp) {
          app.failSnack("Verwijderen mislukt");
        });
      }
    }
  },
  watch: {
    search: function search(val) {
      var _this3 = this;

      // Items have already been loaded
      if (this.items.length > 0) return; // Items have already been requested

      if (this.isLoading) return;
      this.isLoading = true;
      var app = this; // Lazily load input items

      axios.get("/api/v1/employee/").then(function (resp) {
        app.entries = resp.data;
      })["catch"](function (err) {
        console.log(err);
      })["finally"](function () {
        return _this3.isLoading = false;
      });
    },
    selectedEmployee: function selectedEmployee(val) {
      if (val) {
        var app = this;
        axios.get("/api/v1/employee/functiondata/" + val.id).then(function (resp) {
          console.log("loaded function data for employee");
          console.log(JSON.stringify(resp.data));
          app.functiondata = resp.data;
          app.functiondatatab = 0;
        }).then(app.setAvailableFunctions(val.id))["catch"](function (resp) {
          console.log(resp);
          alert("Could not load functiondata");
          app.functiondatatab = 0;
        });
        axios.get("/api/v1/employee/interruptions/" + val.id).then(function (resp) {
          console.log("loaded interruptions for employee");
          console.log(JSON.stringify(resp.data));
          app.interruptions = resp.data;
        })["catch"](function (resp) {
          console.log(resp);
          alert("Could not load functiondata");
        });
      } else {
        this.functiondata = [], this.employments = [], this.interruptions = [];
      }
    }
  },
  created: function created() {
    //load school and ambt data on creation
    var app = this;
    axios.get("/api/v1/ambt").then(function (resp) {
      app.ambten = resp.data;
    })["catch"](function (resp) {
      console.log(resp);
      alert("Could not load ambten");
    });
    axios.get("/api/v1/school").then(function (resp) {
      app.scholen = resp.data.scholen;
    })["catch"](function (resp) {
      console.log(resp);
      alert("Could not load schools");
    });
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FunctionData.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FunctionData.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************/
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
//
//import moment from "moment";

/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    functiondata: Object,
    scholen: Array // fulltimehours : Number,

  },
  data: function data() {
    return {
      defaultEmployment: {
        beginDate: new Date(),
        formattedBegin: "",
        //this.defaultEmployment.beginDate.format('MM-DD-YYYY'),
        endDate: new Date(),
        formattedEnd: "",
        //this.defaultEmployment.endDate.format('MM-DD-YYYY'),
        hours: 0,
        school_id: -1,
        edu_function_data_id: this.functiondata.id
      },
      employments: [],
      editedIndex: -1,
      editedItem: Object.assign({}, this.defaultEmployment),
      employmentDialog: false,
      headers: [{
        text: "Begin",
        align: "left",
        value: "beginDate"
      }, {
        text: "Einde",
        align: "left",
        value: "endDate"
      }, {
        text: "School",
        align: "left",
        value: "school_id"
      }, {
        text: "Uren",
        align: "left",
        value: "hours"
      }, {
        text: "",
        align: "center",
        value: "action"
      }]
    };
  },
  computed: {
    formTitleEmployment: function formTitleEmployment() {
      return this.editedIndex === -1 ? "Nieuwe aanstelling" : "Bewerk aanstelling";
    },
    hourSuffix: function hourSuffix() {
      if (this.functiondata.educational_function) return "/" + this.functiondata.educational_function.denominator;else return "";
    }
    /*employments() {
      return this.functiondata.employments;
    },
    */
    // formattedBegin: {
    //   get() {
    //     if (this.editedItem && this.editedItem.beginDate)
    //       return this.editedItem.beginDate.format("DD-MM-YYYY");
    //     else return "";
    //   }/*,
    //   set(val) {
    //     this.editedItem.beginDate = moment(val, "DD-MM-YYYY");
    //   }*/
    // },
    // formattedEnd: {
    //   get() {
    //     if (this.editedItem && this.editedItem.endDate)
    //       return this.editedItem.endDate.format("DD-MM-YYYY");
    //     else return "";
    //   }/*,
    //   set(val) {
    //     this.editedItem.endDate = moment(val, "DD-MM-YYYY");
    //   }*/
    // }

  },
  methods: {
    // beginToday() {
    //   this.formattedBegin = moment();
    // },
    // stopToday() {
    //   this.formattedEnd = moment();
    // },
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
    // parseDate(val){
    //   let v = moment(val,"DD-MM-YYYY hh:mi:ss");
    //   return v;
    // },
    // formatDate(date){
    //   let f = this.parseDate(date).format("DD-MM-YYYY");
    //   return f;
    // },
    // formatDateFromDB(date){
    //   let f = moment(date,'YYYY-MM-DD hh:mi:ss').format("DD-MM-YYYY");
    //   return f;
    // },
    setBegin: function setBegin() {
      this.editedItem.beginDate = this.parseDate(this.editedItem.formattedBegin + " 12:00:00");
    },
    setEnd: function setEnd() {
      this.editedItem.endDate = this.parseDate(this.editedItem.formattedEnd + " 12:00:00");
    },
    imgUrl: function imgUrl(school) {
      return "http://www.skbl.be/joomla/images/logo/logo-scholen/" + school.logo_filename;
    },
    emitFail: function emitFail(msg) {
      this.$emit('fail', msg);
    },
    emitSuccess: function emitSuccess(msg) {
      this.$emit('success', msg);
    },
    editItem: function editItem(item) {
      this.editedIndex = this.employments.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.formattedBegin = this.formatDateFromDB(this.editedItem.beginDate);
      this.editedItem.formattedEnd = this.formatDateFromDB(this.editedItem.endDate);
      this.employmentDialog = true;
    },
    deleteItem: function deleteItem(item) {
      if (confirm("School echt verwijderen?")) {
        var app = this;
        var index = this.employments.indexOf(item);
        axios["delete"]("/api/v1/employment/" + item.id).then(function (resp) {
          app.employments.splice(index, 1);
          app.emitSuccess("Aanstelling verwijderd");
        })["catch"](function (resp) {
          app.emitFail("Verwijderen mislukt");
        });
      }
    },
    addEmployment: function addEmployment() {
      this.editedItem = Object.assign({}, this.defaultEmployment);
      this.employmentDialog = true;
    },
    saveEmployment: function saveEmployment() {
      var app = this;
      if (this.editedIndex == -1) axios //we should retrieve the whole object set again from the server... trust only server data! :) TODO
      .post("/api/v1/employment", this.editedItem).then(function (resp) {
        //app.$router.push({ path: "/employees" });
        app.employments.push(resp.data);
        app.emitSuccess("Aanstelling toegevoegd");
      })["catch"](function (resp) {
        console.log(resp);
        app.emitFail("Fout bij aanmaken aanstelling");
      });else {
        delete this.editedItem.school; //remove this property before sending it to the server to prevent mixups

        axios.patch("/api/v1/employment/" + this.editedItem.id, this.editedItem).then(function (resp) {
          //to keep reactivity
          //console.log("updating functiondata with index "+app.editedIndex);
          Vue.set(app.employments, app.editedIndex, Object.assign({}, resp.data));
          app.emitSuccess("Wijzigingen opgeslagen");
        })["catch"](function (resp) {
          console.log(resp);
          app.emitFail("Fout bij opslaan wijzigingen");
        });
      }
      this.closeEmployment();
    },
    deleteEmployment: function deleteEmployment() {},
    closeEmployment: function closeEmployment() {
      this.employmentDialog = false;
    },
    deleteFunctionData: function deleteFunctionData() {
      this.$emit("delete", this.functiondata);
    }
  },
  mounted: function mounted() {
    this.employments = this.functiondata.employments;
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&":
/*!************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9& ***!
  \************************************************************************************************************************************************************************************************************/
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
        "v-container",
        { attrs: { fluid: "" } },
        [
          _c(
            "v-row",
            [
              _c(
                "v-col",
                { attrs: { xs: "12", sm: "8", md: "8" } },
                [
                  _c("v-autocomplete", {
                    attrs: {
                      items: _vm.items,
                      loading: _vm.isLoading,
                      "search-input": _vm.search,
                      "hide-no-data": "",
                      "hide-selected": "",
                      "item-text": "fullname",
                      "item-value": "id",
                      label: "Personeelslid",
                      placeholder: "Typ om te zoeken",
                      "prepend-icon": "mdi-database-search",
                      "return-object": ""
                    },
                    on: {
                      "update:searchInput": function($event) {
                        _vm.search = $event
                      },
                      "update:search-input": function($event) {
                        _vm.search = $event
                      }
                    },
                    model: {
                      value: _vm.selectedEmployee,
                      callback: function($$v) {
                        _vm.selectedEmployee = $$v
                      },
                      expression: "selectedEmployee"
                    }
                  })
                ],
                1
              )
            ],
            1
          ),
          _vm._v(" "),
          _vm.selectedEmployee
            ? _c(
                "v-speed-dial",
                {
                  attrs: {
                    absolute: "",
                    top: "",
                    right: "",
                    direction: "bottom"
                  },
                  scopedSlots: _vm._u(
                    [
                      {
                        key: "activator",
                        fn: function() {
                          return [
                            _c(
                              "v-btn",
                              {
                                attrs: {
                                  color: "blue darken-2",
                                  dark: "",
                                  fab: ""
                                },
                                model: {
                                  value: _vm.fab,
                                  callback: function($$v) {
                                    _vm.fab = $$v
                                  },
                                  expression: "fab"
                                }
                              },
                              [
                                _vm.fab
                                  ? _c("v-icon", [_vm._v("close")])
                                  : _c("v-icon", [_vm._v("add")])
                              ],
                              1
                            )
                          ]
                        },
                        proxy: true
                      }
                    ],
                    null,
                    false,
                    2591573340
                  ),
                  model: {
                    value: _vm.fab,
                    callback: function($$v) {
                      _vm.fab = $$v
                    },
                    expression: "fab"
                  }
                },
                [
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { fab: "", dark: "", small: "", color: "#c5f77e" },
                      on: { click: _vm.addFunctionData }
                    },
                    [_c("v-icon", [_vm._v("work")])],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { fab: "", dark: "", small: "", color: "#f7dc6d" },
                      on: { click: _vm.addInterruption }
                    },
                    [_c("v-icon", [_vm._v("work_off")])],
                    1
                  )
                ],
                1
              )
            : _vm._e()
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-row",
        [
          _c(
            "v-col",
            { attrs: { xs: "12", sm: "12", md: "8" } },
            [
              _vm.functiondata.length > 0
                ? _c(
                    "v-card",
                    [
                      _c(
                        "v-card-title",
                        [
                          _c("v-toolbar", { attrs: { color: "#c5f77e" } }, [
                            _vm._v("Ambten")
                          ])
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-container",
                        { attrs: { fluid: "" } },
                        [
                          _c(
                            "v-tabs",
                            {
                              model: {
                                value: _vm.functiondatatab,
                                callback: function($$v) {
                                  _vm.functiondatatab = $$v
                                },
                                expression: "functiondatatab"
                              }
                            },
                            [
                              _vm._l(_vm.functiondata, function(fdata) {
                                return _c(
                                  "v-tab",
                                  { key: fdata.id },
                                  [
                                    _vm._v(
                                      "\n              " +
                                        _vm._s(
                                          fdata.educational_function.name
                                        ) +
                                        "\n              "
                                    ),
                                    _c(
                                      "v-icon",
                                      {
                                        staticClass: "mx-4",
                                        attrs: { small: "" },
                                        on: {
                                          click: function($event) {
                                            return _vm.editFunctionData(fdata)
                                          }
                                        }
                                      },
                                      [_vm._v("edit")]
                                    )
                                  ],
                                  1
                                )
                              }),
                              _vm._v(" "),
                              _vm._l(_vm.functiondata, function(fdata) {
                                return _c(
                                  "v-tab-item",
                                  { key: fdata.id },
                                  [
                                    _c("functiondatacomp", {
                                      attrs: {
                                        functiondata: fdata,
                                        scholen: _vm.scholen
                                      },
                                      on: {
                                        delete: function($event) {
                                          return _vm.deleteFunctionData(fdata)
                                        },
                                        fail: _vm.failSnack,
                                        success: _vm.successSnack
                                      }
                                    })
                                  ],
                                  1
                                )
                              })
                            ],
                            2
                          )
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e()
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "v-col",
            { attrs: { xs: "12", sm: "12", md: "4" } },
            [
              _vm.interruptions.length > 0
                ? _c(
                    "v-card",
                    [
                      _c(
                        "v-card-title",
                        [
                          _c("v-toolbar", { attrs: { color: "#f7dc6d" } }, [
                            _vm._v("Onderbrekingen")
                          ])
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c(
                        "v-container",
                        { attrs: { fluid: "" } },
                        [
                          _c("v-data-table", {
                            attrs: {
                              items: _vm.interruptions,
                              headers: _vm.interruptionheaders
                            },
                            scopedSlots: _vm._u(
                              [
                                {
                                  key: "item.beginDate",
                                  fn: function(ref) {
                                    var item = ref.item
                                    return [
                                      _vm._v(
                                        _vm._s(
                                          _vm.formatDateFromDB(item.beginDate)
                                        )
                                      )
                                    ]
                                  }
                                },
                                {
                                  key: "item.endDate",
                                  fn: function(ref) {
                                    var item = ref.item
                                    return [
                                      _vm._v(
                                        _vm._s(
                                          _vm.formatDateFromDB(item.endDate)
                                        )
                                      )
                                    ]
                                  }
                                },
                                {
                                  key: "item.type",
                                  fn: function(ref) {
                                    var item = ref.item
                                    return [
                                      _c(
                                        "v-icon",
                                        {
                                          attrs: {
                                            color:
                                              item.type == 1 ? "green" : "red"
                                          }
                                        },
                                        [
                                          _vm._v(
                                            _vm._s(
                                              item.type == 1
                                                ? "check"
                                                : "not_interested"
                                            )
                                          )
                                        ]
                                      )
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
                                              return _vm.editInterruption(item)
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
                                              return _vm.deleteInterruption(
                                                item
                                              )
                                            }
                                          }
                                        },
                                        [_vm._v("delete")]
                                      )
                                    ]
                                  }
                                }
                              ],
                              null,
                              false,
                              2109429079
                            )
                          })
                        ],
                        1
                      )
                    ],
                    1
                  )
                : _vm._e()
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
            value: _vm.functionDataDialog,
            callback: function($$v) {
              _vm.functionDataDialog = $$v
            },
            expression: "functionDataDialog"
          }
        },
        [
          _c(
            "v-card",
            [
              _c("v-card-title", [
                _c("span", { staticClass: "headline" }, [
                  _vm._v(_vm._s(_vm.formTitleFD))
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
                              _c("v-select", {
                                attrs: {
                                  items: _vm.ambten,
                                  "item-text": "name",
                                  "item-value": "id",
                                  "item-key": "id",
                                  label: "Ambt"
                                },
                                model: {
                                  value: _vm.editedItem.educational_function_id,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "educational_function_id",
                                      $$v
                                    )
                                  },
                                  expression:
                                    "editedItem.educational_function_id"
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
                      on: { click: _vm.closeFunctionData }
                    },
                    [_vm._v("Annuleren")]
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", text: "" },
                      on: { click: _vm.saveFunctionData }
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
        "v-dialog",
        {
          attrs: { "max-width": "500px" },
          model: {
            value: _vm.interruptionDialog,
            callback: function($$v) {
              _vm.interruptionDialog = $$v
            },
            expression: "interruptionDialog"
          }
        },
        [
          _c(
            "v-card",
            [
              _c("v-card-title", [
                _c("span", { staticClass: "headline" }, [
                  _vm._v(_vm._s(_vm.formTitleInterruption))
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
                            { attrs: { cols: "12", sm: "6", md: "6" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Begin", hint: "DD-MM-YYYY" },
                                on: { blur: _vm.setBegin },
                                model: {
                                  value: _vm.editedItem.formattedBegin,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "formattedBegin",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.formattedBegin"
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
                                attrs: { label: "Einde", hint: "DD-MM-YYYY" },
                                on: { blur: _vm.setEnd },
                                model: {
                                  value: _vm.editedItem.formattedEnd,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "formattedEnd",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.formattedEnd"
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
                              _c("v-switch", {
                                attrs: { label: "Telt mee voor rechtenopbouw" },
                                model: {
                                  value: _vm.editedItem.type,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "type", $$v)
                                  },
                                  expression: "editedItem.type"
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
                      on: { click: _vm.closeInterruption }
                    },
                    [_vm._v("Annuleren")]
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", text: "" },
                      on: { click: _vm.saveInterruption }
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FunctionData.vue?vue&type=template&id=4432fe8d&":
/*!***************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/FunctionData.vue?vue&type=template&id=4432fe8d& ***!
  \***************************************************************************************************************************************************************************************************************/
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
        "v-container",
        [
          _c(
            "v-row",
            { attrs: { justify: "space-between" } },
            [
              _c(
                "v-col",
                { attrs: { cols: "4", xs: "8", sm: "5", md: "4" } },
                [
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "primary" },
                      on: { click: _vm.addEmployment }
                    },
                    [_vm._v("Aanstelling toevoegen")]
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "v-col",
                { attrs: { cols: "4", xs: "8", sm: "5", md: "4" } },
                [
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "red", width: "100%" },
                      on: { click: _vm.deleteFunctionData }
                    },
                    [
                      _vm._v("\n          Ambt verwijderen\n          "),
                      _c("v-icon", [_vm._v("delete")])
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
      _vm.employments.length > 0
        ? _c("v-data-table", {
            attrs: { items: _vm.employments, headers: _vm.headers },
            scopedSlots: _vm._u(
              [
                {
                  key: "item.beginDate",
                  fn: function(ref) {
                    var item = ref.item
                    return [
                      _vm._v(_vm._s(_vm.formatDateFromDB(item.beginDate)))
                    ]
                  }
                },
                {
                  key: "item.endDate",
                  fn: function(ref) {
                    var item = ref.item
                    return [_vm._v(_vm._s(_vm.formatDateFromDB(item.endDate)))]
                  }
                },
                {
                  key: "item.school_id",
                  fn: function(ref) {
                    var item = ref.item
                    return [
                      item.school.logo_filename != "nologo"
                        ? _c("img", {
                            attrs: {
                              src: _vm.imgUrl(item.school),
                              height: "25px",
                              width: "25px"
                            }
                          })
                        : _vm._e(),
                      _vm._v(" "),
                      _vm._v(
                        "\n      " +
                          _vm._s(item.school.name) +
                          " [" +
                          _vm._s(item.school.abbreviation) +
                          "]\n      "
                      )
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
              ],
              null,
              false,
              3931262463
            )
          })
        : _vm._e(),
      _vm._v(" "),
      _c(
        "v-dialog",
        {
          attrs: { "max-width": "500px" },
          model: {
            value: _vm.employmentDialog,
            callback: function($$v) {
              _vm.employmentDialog = $$v
            },
            expression: "employmentDialog"
          }
        },
        [
          _c(
            "v-card",
            [
              _c("v-card-title", [
                _c("span", { staticClass: "headline" }, [
                  _vm._v(_vm._s(_vm.formTitleEmployment))
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
                            { attrs: { cols: "12", sm: "6", md: "6" } },
                            [
                              _c("v-text-field", {
                                attrs: { label: "Begin", hint: "DD-MM-YYYY" },
                                on: { blur: _vm.setBegin },
                                model: {
                                  value: _vm.editedItem.formattedBegin,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "formattedBegin",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.formattedBegin"
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
                                attrs: { label: "Einde", hint: "DD-MM-YYYY" },
                                on: { blur: _vm.setEnd },
                                model: {
                                  value: _vm.editedItem.formattedEnd,
                                  callback: function($$v) {
                                    _vm.$set(
                                      _vm.editedItem,
                                      "formattedEnd",
                                      $$v
                                    )
                                  },
                                  expression: "editedItem.formattedEnd"
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
                              _c("v-select", {
                                attrs: {
                                  items: _vm.scholen,
                                  "item-text": "abbreviation",
                                  "item-value": "id",
                                  "item-key": "id",
                                  label: "School"
                                },
                                model: {
                                  value: _vm.editedItem.school_id,
                                  callback: function($$v) {
                                    _vm.$set(_vm.editedItem, "school_id", $$v)
                                  },
                                  expression: "editedItem.school_id"
                                }
                              })
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "v-col",
                            { attrs: { cols: "12", sm: "4", md: "4" } },
                            [
                              _vm.editedItem
                                ? _c("v-text-field", {
                                    attrs: {
                                      label: "Uren",
                                      suffix: _vm.hourSuffix
                                    },
                                    model: {
                                      value: _vm.editedItem.hours,
                                      callback: function($$v) {
                                        _vm.$set(_vm.editedItem, "hours", $$v)
                                      },
                                      expression: "editedItem.hours"
                                    }
                                  })
                                : _vm._e()
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
                      on: { click: _vm.closeEmployment }
                    },
                    [_vm._v("Annuleren")]
                  ),
                  _vm._v(" "),
                  _c(
                    "v-btn",
                    {
                      attrs: { color: "blue darken-1", text: "" },
                      on: { click: _vm.saveEmployment }
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
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/Dashboard.vue":
/*!***********************************************!*\
  !*** ./resources/js/components/Dashboard.vue ***!
  \***********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=template&id=040e2ab9& */ "./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&");
/* harmony import */ var _Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Dashboard.vue?vue&type=script&lang=js& */ "./resources/js/components/Dashboard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["render"],
  _Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Dashboard.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Dashboard.vue?vue&type=script&lang=js&":
/*!************************************************************************!*\
  !*** ./resources/js/components/Dashboard.vue?vue&type=script&lang=js& ***!
  \************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&":
/*!******************************************************************************!*\
  !*** ./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9& ***!
  \******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./Dashboard.vue?vue&type=template&id=040e2ab9& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Dashboard.vue?vue&type=template&id=040e2ab9&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_Dashboard_vue_vue_type_template_id_040e2ab9___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/components/FunctionData.vue":
/*!**************************************************!*\
  !*** ./resources/js/components/FunctionData.vue ***!
  \**************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _FunctionData_vue_vue_type_template_id_4432fe8d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FunctionData.vue?vue&type=template&id=4432fe8d& */ "./resources/js/components/FunctionData.vue?vue&type=template&id=4432fe8d&");
/* harmony import */ var _FunctionData_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./FunctionData.vue?vue&type=script&lang=js& */ "./resources/js/components/FunctionData.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _FunctionData_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _FunctionData_vue_vue_type_template_id_4432fe8d___WEBPACK_IMPORTED_MODULE_0__["render"],
  _FunctionData_vue_vue_type_template_id_4432fe8d___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/FunctionData.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/FunctionData.vue?vue&type=script&lang=js&":
/*!***************************************************************************!*\
  !*** ./resources/js/components/FunctionData.vue?vue&type=script&lang=js& ***!
  \***************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FunctionData_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./FunctionData.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FunctionData.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FunctionData_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/FunctionData.vue?vue&type=template&id=4432fe8d&":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/FunctionData.vue?vue&type=template&id=4432fe8d& ***!
  \*********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FunctionData_vue_vue_type_template_id_4432fe8d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./FunctionData.vue?vue&type=template&id=4432fe8d& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/FunctionData.vue?vue&type=template&id=4432fe8d&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FunctionData_vue_vue_type_template_id_4432fe8d___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FunctionData_vue_vue_type_template_id_4432fe8d___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);