<template>
  <div>
    <v-container fluid>
      <v-row>
        <v-col xs="12" sm="8" md="8">
          <v-autocomplete
            v-model="selectedEmployee"
            :items="items"
            :loading="isLoading"
            :search-input.sync="search"
            hide-no-data
            hide-selected
            item-text="fullNameExtended"
            item-value="id"
            label="Personeelslid"
            placeholder="Typ om te zoeken"
            prepend-icon="mdi-database-search"
            return-object
          ></v-autocomplete>
        </v-col>
        <!-- <v-col xs="12" sm="4" md="4"> -->
      </v-row>
      <v-speed-dial v-if="(!ro) && selectedEmployee" v-model="fab" absolute top right direction="bottom" >
        <template v-slot:activator>
          <v-btn v-model="fab" color="blue darken-2" dark fab>
            <v-icon v-if="fab">close</v-icon>
            <v-icon v-else>add</v-icon>
          </v-btn>
        </template>
        <v-btn fab dark small color="#c5f77e" @click="addFunctionData" title="Ambt toevoegen">
          <v-icon>work</v-icon>
        </v-btn>
        <v-btn fab dark small color="#f7dc6d" @click="addInterruption" title="Onderbreking toevoegen">
          <v-icon>work_off</v-icon>
        </v-btn>
      </v-speed-dial>

      <!-- </v-col> -->
    </v-container>
    <v-row>
      <v-col xs="12" sm="12" md="8">
        <v-card v-if="functiondata.length >0">
          <v-card-title>
            <v-toolbar
              color="#c5f77e"
            >Ambten voor {{ selectedEmployee.firstName + " " + selectedEmployee.lastName}}</v-toolbar>
          </v-card-title>
          <v-container fluid>
            <v-tabs v-model="functiondatatab">
              <v-tab :id="generateRef(fdata)" v-for="fdata in functiondata" v-bind:key="generateRef(fdata)" :ref="generateRef(fdata)">
                {{ fdata.educational_function.name }} 
                <v-icon v-if="fdata.isTadd == 1" color="golden">star</v-icon>
                <v-progress-circular title="Dagen effectief" class="mx-4" size="30" :color="progressColor(fdata.seniority_days,400)" :value="(fdata.seniority_days / 400.0) * 100.0">EF</v-progress-circular>
                <v-progress-circular title="Dagen totaal" class="mx-4" size="30" :color="progressColor(fdata.total_seniority_days,400)" :value="(fdata.seniority_days / 580.0) * 100.0">TO</v-progress-circular>
                <!-- [ {{ fdata.seniority_days }} dagen ] -->
                <!-- <v-icon small class="mx-4" @click="editFunctionData(fdata)" >edit</v-icon> -->
              </v-tab>
              <v-tabs-items v-model="functiondatatab">
              <v-tab-item v-for="fdata in functiondata" v-bind:key="generateRef(fdata)">
                <functiondatacomp
                  :functiondata="fdata"
                  :scholen="scholen"
                  @delete="deleteFunctionData(fdata)"
                  @fail="failSnack"
                  @success="successSnack"
                  @reloademployeedata="reloadEmployeeData"
                ></functiondatacomp>
              </v-tab-item>
              </v-tabs-items>
            </v-tabs>
          </v-container>
        </v-card>
      </v-col>
      <v-col xs="12" sm="12" md="4">
        <v-card v-if="interruptions.length > 0">
          <v-card-title>
            <v-toolbar color="#f7dc6d">Onderbrekingen</v-toolbar>
          </v-card-title>
          <v-container fluid>
            <v-data-table :items="interruptions" :headers="interruptionheaders">
              <template v-slot:item.beginDate="{ item }">{{ formatDateFromDB(item.beginDate)}}</template>
              <template v-slot:item.endDate="{ item }">{{ formatDateFromDB(item.endDate)}}</template>
              <template v-slot:item.interruption_type_id="{ item }">
                <v-icon
                  :color="item.interruption_type_id==1?'green':'red'"
                >{{item.interruption_type_id==1?'check':'not_interested'}}</v-icon>
              </template>
              <template v-if="!ro" v-slot:item.action="{ item }">
                <v-icon small class="mr-2" @click="editInterruption(item)" title="Aanstelling aanpassen">edit</v-icon>
                <v-icon small class="mr-2" @click="deleteInterruption(item)" title="Aanstelling verwijderen">delete</v-icon>
              </template>
            </v-data-table>
          </v-container>
        </v-card>
      </v-col>
    </v-row>
    <v-dialog v-if="!ro" v-model="functionDataDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitleFD }}</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="8" md="8">
                <v-select
                  v-model="editedItem.educational_function_id"
                  :items="ambten"
                  item-text="name"
                  item-value="id"
                  item-key="id"
                  label="Ambt"
                ></v-select>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="blue darken-1" text @click="closeFunctionData">Annuleren</v-btn>
          <v-btn color="blue darken-1" text @click="saveFunctionData">Opslaan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-if="!ro" v-model="interruptionDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitleInterruption }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <!-- <v-menu v-model="datepickerMenu3" :close-on-content-click="false" full-width max-width="290"> -->
                <!-- <template v-slot:activator="{ on }"> -->
                <v-text-field
                  v-model="editedItem.formattedBegin"
                  label="Begin"
                  hint="DD-MM-YYYY"
                  @blur="setBegin"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="editedItem.formattedEnd"
                  label="Einde"
                  hint="DD-MM-YYYY"
                  @blur="setEnd"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="8" md="8">
                <v-switch
                  v-model="editedItem.interruption_type_id"
                  :false-value="teltniet"
                  :true-value="teltwel"
                  label="Telt mee voor rechtenopbouw"
                ></v-switch>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="blue darken-1" text @click="closeInterruption">Annuleren</v-btn>
          <v-btn color="blue darken-1" text @click="saveInterruption">Opslaan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="snackbar" bottom :color="snack_color" :timeout="snack_timeout">
      {{ snack_text }}
      <v-btn dark text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
import FunctionData from "./FunctionData.vue";
import { compareAsc,parse, format } from "date-fns";
import * as DateUtil from "../DateUtil";

export default {
  components: {
    functiondatacomp: FunctionData
  },
  data: function() {
    return {
      //model entities
      functiondata: [],
      interruptions: [],
      ambten: [],
      //selection and editing
      editedItem: {},
      defaultFunctionData: {
        educational_function_id: -1,
        employee_id: this.selectedEmployee,
        seniority_days : 0,
        total_seniority_days : 0
      },
      defaultInterruption: {
        beginDate: new Date(),
        formattedBegin: null,
        endDate: new Date(),
        formattedEnd: null,
        employee_id: this.selectedEmployee,
        interruption_type_id: 1,
        isnew: 1
      },
      //autocomplete stuff
      entries: [],
      isLoading: false,
      selectedEmployee: null, //model for autocomplete
      search: null,

      scholen: [],

      fab: false,
      //tab stuff
      functiondatatab: 0,
      //tabs : null,
      loadingtabs: false,
      // interruptiontab: null,

      snack_color: null,
      snack_timeout: 2000,
      snackbar: false,
      snack_text: "",

      functionDataDialog: false,
      interruptionDialog: false,

      teltniet: 2,
      teltwel: 1,

      interruptionheaders: [
        { text: "Begin", align: "left", value: "beginDate" },
        { text: "Einde", align: "left", value: "endDate" },
        { text: "Dagen tellen mee", align: "left", value: "interruption_type_id" },
        { text: "", align: "center", value: "action" }
      ],
      descriptionLimit: 45
    };
  },
  computed: {
    formTitleFD() {
      return this.editedIndex === -1 ? "Nieuwe functie" : "Bewerk functie";
    },
    formTitleInterruption() {
      return this.editedIndex === -1
        ? "Nieuwe onderbreking"
        : "Bewerk onderbrekeing";
    },
    ro() { //shorthand for "read only"
      return window.u53r.readonly;
    },
    items() {
      return this.entries.map(entry => {
        //console.log(entry.fullNameExtended);
        const Fullname =
          entry.fullNameExtended.length > this.descriptionLimit
            ? entry.fullNameExtended.slice(0, this.descriptionLimit) + "..."
            : entry.fullNameExtended;

        return Object.assign({}, entry, { Fullname });
      });
    }
  },
  methods: {
    progressColor(value,max){
      let v = value / max * 100.0;
      if (v < 33) return 'red';
      else if (v < 66) return 'orange';
      else return 'green';
    },
    generateRef(fdata){
      return 'tab-'+fdata.employee_id+'-'+fdata.id;
    },
    parseDate(val) {
      return DateUtil.parseDate(val);
    },
    formatDateFromDB(date) {
      return DateUtil.formatDateFromDB(date);
    },
    formatDate(date) {
      return DateUtil.formatDate(date);
    },
    setBegin() {
      if (DateUtil.isDate(this.editedItem.formattedBegin)) {
        this.editedItem.beginDate = DateUtil.formatDateToDB(
          this.parseDate(this.editedItem.formattedBegin + " 12:00:00")
        );
        return true;
      } else {
        this.failSnack("Fout datumformaat");
        return false;
      }
    },
    setEnd() {
      if (DateUtil.isDate(this.editedItem.formattedEnd)) {
        this.editedItem.endDate = DateUtil.formatDateToDB(
          this.parseDate(this.editedItem.formattedEnd + " 12:00:00")
        );
      } else {
        this.failSnack("Fout datumformaat");
        return false;
      }
    },

    eenDagAfwezig() {
      this.editedItem.formattedEnd = this.editedItem.formattedBegin;
      this.setEnd();
      this.editedItem.interruption_type_id = 2;
    },
    successSnack(message) {
      this.snack_text = message;
      this.snack_color = "success";
      this.snackbar = true;
    },
    failSnack(message) {
      this.snack_text = message;
      this.snack_color = "error";
      this.snackbar = true;
    },

    setAvailableFunctions() {
      var app = this;
      axios
        .get("api/v1/ambt/availableForEmployee/" + this.selectedEmployee.id)
        .then(function(resp) {
          app.ambten = resp.data;
          //app.functiondatatab = 0;
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load functions for employee");
        });
    },
    addFunctionData() {
      this.editedIndex = -1;
      this.setAvailableFunctions();
      this.editedItem = Object.assign({}, this.defaultFunctionData);
      this.editedItem.employee_id = this.selectedEmployee.id;
      this.functionDataDialog = true;
    },
    editFunctionData(item) {
      this.editedIndex = this.functiondata.indexOf(item);
      this.setAvailableFunctions();
      this.editedItem = Object.assign({}, item);
      this.functionDataDialog = true;
    },
    saveFunctionData() {
      var app = this;
      if (this.editedIndex == -1) {
        axios
          .post("api/v1/educationalFunctionData", this.editedItem)
          .then(function(resp) {
            //app.$router.push({ path: "/employees" });
            app.functiondata.push(resp.data);
            app.successSnack("Aanstelling toegevoegd");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij aanmaken aanstelling");
          });
      } else {
        delete this.editedItem.educational_function; //remove this property before sending it to the server to prevent mixups
        axios
          .patch(
            "api/v1/educationalFunctionData/" + this.editedItem.id,
            this.editedItem
          )
          .then(function(resp) {
            //to keep reactivity
            Vue.set(
              app.functiondata,
              app.editedIndex,
              Object.assign({}, resp.data)
            );
            app.successSnack("Wijzigingen opgeslagen");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij opslaan wijzigingen");
          });
      }
      this.closeFunctionData();
    },
    closeFunctionData() {
      this.functionDataDialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultFunctionData);
        this.editedIndex = -1;
      }, 300);
    },
    deleteFunctionData(fdata) {
      let yes = confirm("Ambt verwijderen?");
      if (yes) {
        var app = this;
        var index = this.functiondata.indexOf(fdata);
        axios
          .delete("api/v1/educationalFunctionData/" + fdata.id)
          .then(function(resp) {
            app.functiondata.splice(index, 1);
            app.successSnack("Ambt verwijderd");
          })
          .catch(function(resp) {
            app.failSnack("Verwijderen mislukt");
          });
      }
    },
    addInterruption() {
      this.editedIndex = -1;
      this.editedItem = Object.assign({}, this.defaultInterruption);
      this.editedItem.employee_id = this.selectedEmployee.id;
      this.interruptionDialog = true;
    },
    editInterruption(item) {
      this.editedIndex = this.interruptions.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.formattedBegin = this.formatDateFromDB(
        this.editedItem.beginDate
      );
      this.editedItem.formattedEnd = this.formatDateFromDB(
        this.editedItem.endDate
      );
      this.interruptionDialog = true;
    },
    validateInterruptionData() {
      if (!this.editedItem.formattedBegin) {
        this.failSnack("Geen begindatum");
        return false;
      }
      //console.log("DEBUG: formattedBegin is not empty");
      if (!this.editedItem.formattedEnd) this.eenDagAfwezig(); //creëer een korte vervanging voor 1 dag
      if (
        !(
          DateUtil.isDate(this.editedItem.formattedBegin) &&
          DateUtil.isDate(this.editedItem.formattedEnd)
        )
      ) {
        this.failSnack("Verkeerd datumformaat... kan niet opslaan!");
        return false;
      }
      let compasc = compareAsc(DateUtil.parseDate(this.editedItem.formattedBegin),DateUtil.parseDate(this.editedItem.formattedEnd));
      if(compasc==1) {
        this.failSnack("Einddatum mag niet voor begindatum vallen!");
        return false;
      }
      return true;
    },
    saveInterruption() {
      var app = this;
      if (app.validateInterruptionData())
        if (this.editedIndex == -1) {
          axios
            .post("api/v1/employmentInterruption", this.editedItem)
            .then(function(resp) {
              //app.$router.push({ path: "/employees" });
              app.interruptions.push(resp.data);
              app.successSnack("Onderbreking toegevoegd");
              app.closeInterruption();
            })
            .catch(function(resp) {
              //console.log(resp);
              app.failSnack("Fout bij aanmaken onderbreking");
            });
        } else {
          delete this.editedItem.educational_function; //remove this property before sending it to the server to prevent mixups
          axios
            .patch(
              "api/v1/employmentInterruption/" + this.editedItem.id,
              this.editedItem
            )
            .then(function(resp) {
              //to keep reactivity
              Vue.set(
                app.interruptions,
                app.editedIndex,
                Object.assign({}, resp.data)
              );
              app.successSnack("Wijzigingen opgeslagen");
              app.closeInterruption();
            })
            .catch(function(resp) {
              //console.log(resp);
              app.failSnack("Fout bij opslaan wijzigingen");
            });
        }
    },
    updateAllSeniorityDays() {
      var app = this;
      axios
        .patch(
          "api/v1/taddCalculator/updateAllSeniorityDays/" +
            app.editedItem.employee_id
        )
        .then(function(resp) {
          app.reloadEmployeeData(app.editedItem.employee_id);
        })
        .catch(function(resp) {
          //console.log(resp);
          app.emitFail("Fout bij berekenen of updaten van dagen anciënniteit");
        });
    },
    closeInterruption() {
      this.interruptionDialog = false;
    },
    deleteInterruption(item) {
      let yes = confirm("Onderbreking verwijderen?");
      if (yes) {
        var app = this;
        var index = this.interruptions.indexOf(item);
        axios
          .delete("api/v1/employmentInterruption/" + item.id)
          .then(function(resp) {
            app.interruptions.splice(index, 1);
            app.successSnack("Onderbreking verwijderd");
          })
          .catch(function(resp) {
            app.failSnack("Verwijderen mislukt");
          });
      }
    },
    reloadEmployeeData2(evt,employee_id){
      debugger;
      this.reloadEmployeeData(employee_id);
    },
    reloadEmployeeData(employee_id,setFirstTab) {
      //console.log("employee_id="+employee_id);
      var app = this;
      
      axios
        .get("api/v1/employee/functiondata/" + employee_id)
        .then(function(resp) {
          //console.log("loaded function data for employee "+ employee_id);
          //console.log(JSON.stringify(resp.data));
          app.functiondata = resp.data;
          //app.functiondatatab = 0;
          //debugger;
          //if ((setFirstTab) && (app.functiondata.length>0)){
          //  let r = app.generateRef(app.functiondata[0]);
          //  this.$refs[r].click();
          //}
          if ((setFirstTab) && (app.functiondata.length>0)) app.functiondatatab = 0;
        })
        .then(app.setAvailableFunctions(employee_id))
        .catch(function(resp) {
          //console.log(resp);
          alert("Could not load functiondata");
          //app.functiondatatab = 0;
        });
      axios
        .get("api/v1/employee/interruptions/" + employee_id)
        .then(function(resp) {
          //console.log("loaded interruptions for employee "+ employee_id);
          //console.log(JSON.stringify(resp.data));
          app.interruptions = resp.data;
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load functiondata");
        });
    }
  },
  watch: {
    search(val) {
      // Items have already been loaded
      if (this.items.length > 0) return;

      // Items have already been requested
      if (this.isLoading) return;

      this.isLoading = true;
      var app = this;

      // Lazily load input items
      axios
        .get("api/v1/employee/activeOnly/1")
        .then(function(resp) {
          app.entries = resp.data;
        })
        .catch(err => {
          console.log(err);
        })
        .finally(() => (this.isLoading = false));
    },

    selectedEmployee(val) {
      if (val) {
        this.reloadEmployeeData(val.id,true);
      } else {
        (this.functiondata = []),
          (this.employments = []),
          (this.interruptions = []);
      }
    }
  },

  beforeRouteEnter(to, from, next) {
    next(vm => {
      // access to component instance via `vm`
      if (to.params.selectedEmployee) {
        vm.selectedEmployee = to.params.selectedEmployee;
        if (to.params.newEmployee) vm.addFunctionData();
      }
    });
  },
  beforeRouteUpdate(to, from, next) {
    // just use `this`
    if (to.params.selectedEmployee)
      this.selectedEmployee = to.params.selectedEmployee;
    next();
  },
  created() {
    //load school and ambt data on creation
    var app = this;
    
    axios
      .get("api/v1/ambt")
      .then(function(resp) {
        app.ambten = resp.data.filter(a => (a.isActive == 1));
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load ambten");
      });

    axios
      .get("api/v1/school")
      .then(function(resp) {
        
        app.scholen = resp.data.scholen.filter(s => (s.isActive == 1));

      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load schools");
      });
  }
};
</script>
