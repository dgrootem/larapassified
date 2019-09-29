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
            item-text="fullname"
            item-value="id"
            label="Personeelslid"
            placeholder="Typ om te zoeken"
            prepend-icon="mdi-database-search"
            return-object
          ></v-autocomplete>
        </v-col>
        <!-- <v-col xs="12" sm="4" md="4"> -->
      </v-row>
      <v-speed-dial v-model="fab" absolute top right direction="bottom">
        <template v-slot:activator>
          <v-btn v-model="fab" color="blue darken-2" dark fab>
            <v-icon v-if="fab">close</v-icon>
            <v-icon v-else>add</v-icon>
          </v-btn>
        </template>
        <v-btn fab dark small color="green" @click="addFunctionData">
          <v-icon>work</v-icon>
        </v-btn>
        <v-btn fab dark small color="orange" @click="addInterruption">
          <v-icon>work_off</v-icon>
        </v-btn>
      </v-speed-dial>

      <!-- </v-col> -->
    </v-container>
    <v-container v-if="functiondata.length >0" fluid>
      <v-tabs v-model="functiondatatab">
        <v-tab v-for="fdata in functiondata" v-bind:key="fdata.id">
          {{ fdata.educational_function.name }}
          <v-icon small class="mx-4" @click="editFunctionData(fdata)">edit</v-icon>
        </v-tab>
        <v-tab-item v-for="fdata in functiondata" v-bind:key="fdata.id">
          <!-- <v-container>
            <v-row justify="end">
              <v-col cols="3" >
                <v-btn color="red" @click="deleteFunctionData(fdata)">
                  Verwijder ambt
                  <v-icon>delete</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-container>-->

          <functiondatacomp
            :functiondata="fdata"
            :scholen="scholen"
            
            @delete="deleteFunctionData(fdata)"
            @fail="failSnack(message)"
            @success="successSnack(message)"
          ></functiondatacomp>
        </v-tab-item>
      </v-tabs>
    </v-container>
    <v-container v-if="interruptions.length > 0">
      <v-data-table :items="interruptions" :headers="interruptionheaders"></v-data-table>
    </v-container>

    <v-dialog v-model="functionDataDialog" max-width="500px">
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

    <v-dialog v-model="interruptionDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitleFD }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <!-- <v-menu v-model="datepickerMenu3" :close-on-content-click="false" full-width max-width="290"> -->
                  <!-- <template v-slot:activator="{ on }"> -->
                    <v-text-field clearable v-model="formattedBegin" label="Begin" hint="DD-MM-YYYY"></v-text-field>
                  <!-- </template> -->
                  <!-- <v-date-picker v-model="formattedBegin" @change="datepickerMenu3 = false"></v-date-picker> -->
                <!-- </v-menu> -->
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field clearable v-model="formattedEnd" label="Einde" hint="DD-MM-YYYY"></v-text-field>
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
import moment from "moment";
import FuncionData from "./FunctionData.vue";

export default {
  components: {
    functiondatacomp: FuncionData
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
        employee_id: this.selectedEmployee
      },
      defaultInterruption: {
        beginDate: moment(),
        endDate: moment(),
        employee_id: this.selectedEmployee
      },
      //autocomplete stuff
      entries: [],
      isLoading: false,
      selectedEmployee: null, //model for autocomplete
      search: null,

      scholen: [],

      fab: false,
      //tab stuff
      functiondatatab: null,
      // interruptiontab: null,

      snack_color: null,
      snack_timeout: 2000,
      snackbar: false,
      snack_text: "",

      functionDataDialog: false,
      interruptionDialog: false,
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
    formTitleFD() {
      return this.editedIndex === -1 ? "Nieuwe functie" : "Bewerk functie";
    },
    formTitleInterruption() {
      return this.editedIndex === -1
        ? "Nieuwe onderbreking"
        : "Bewerk onderbrekeing";
    },

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
    },
    items() {
      //debugger;
      return this.entries.map(entry => {
        const Fullname =
          entry.fullname.length > this.descriptionLimit
            ? entry.fullname.slice(0, this.descriptionLimit) + "..."
            : entry.fullname;

        return Object.assign({}, entry, { Fullname });
      });
    }
  },
  methods: {
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
        .get("/api/v1/ambt/availableForEmployee/" + this.selectedEmployee.id)
        .then(function(resp) {
          app.ambten = resp.data;
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
      if (this.editedIndex == -1)
        axios
          .post("/api/v1/educationalFunctionData", this.editedItem)
          .then(function(resp) {
            //app.$router.push({ path: "/employees" });
            app.functiondata.push(resp.data);
            app.successSnack("Aanstelling toegevoegd");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij aanmaken aanstelling");
          });
      else {
        delete this.editedItem.educational_function; //remove this property before sending it to the server to prevent mixups
        axios
          .patch(
            "/api/v1/educationalFunctionData/" + this.editedItem.id,
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
          .delete("/api/v1/educationalFunctionData/" + fdata.id)
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
      this.editedItem = Object.assign({}, this.defaultInterruption);
      this.editedItem.employee_id = this.selectedEmployee.id;
      this.interruptionDialog = true;
      this.editedIndex = -1;
    },
    saveInterruption() {
      var app = this;
      axios
        .post("/api/v1/interruption", this.editedItem)
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });
          app.functiondata.push(resp.data);
          app.successSnack("Onderbreking toegevoegd");
        })
        .catch(function(resp) {
          console.log(resp);
          app.failSnack("Fout bij aanmaken onderbreking");
        });
    },
    closeInterruption() {},
    deleteInterruption() {}
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
        .get("/api/v1/employee/")
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
        var app = this;
        axios
          .get("/api/v1/employee/functiondata/" + val.id)
          .then(function(resp) {
            console.log("loaded data for employee");
            console.log(JSON.stringify(resp.data));
            app.functiondata = resp.data;
          })
          .then(app.setAvailableFunctions(val.id))
          .catch(function(resp) {
            console.log(resp);
            alert("Could not load functiondata");
          });
      } else {
        (this.functiondata = []),
          (this.employments = []),
          (this.interruptions = []);
      }
    }
  },
  created() {
    //load school and ambt data on creation
    var app = this;
    axios
      .get("/api/v1/ambt")
      .then(function(resp) {
        app.ambten = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load ambten");
      });

    axios
      .get("/api/v1/school")
      .then(function(resp) {
        app.scholen = resp.data.scholen;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load schools");
      });
  }
};
</script>

<style>
</style>