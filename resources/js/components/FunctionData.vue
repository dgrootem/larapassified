<template>
  <div>
    <!-- <v-container fluid>
    <v-layout row>-->
    <v-container>
      <v-row>
        <v-label>Reeds opgebouwde dagen: {{functiondata.total_seniority_days}} TOT / {{functiondata.seniority_days}} EFF</v-label>
      </v-row>
      <v-row justify="space-between">
        <v-col cols="4" xs="9" sm="9" md="6">
          <v-btn v-if="!ro" color="primary" @click="addEmployment">Aanstelling toevoegen</v-btn>
          <v-btn v-if="!ro" color="secondary" @click="setStartwaarde">Startwaarde invullen</v-btn>
        </v-col>
        <v-col v-if="!ro" cols="4" xs="3" sm="3" md="3">
          <v-btn color="red" @click="deleteFunctionData" width="100%">
            Ambt verwijderen
            <v-icon>delete</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-container>
    <!-- {{employments}} -->
    <!-- </v-layout>
    <v-layout row>-->
    <v-data-table :items="employments" :headers="headers" v-if="employments.length > 0">
      <template v-slot:item.beginDate="{ item }">{{ formatDateFromDB(item.beginDate)}}</template>
      <template v-slot:item.endDate="{ item }">{{ formatDateFromDB(item.endDate)}}</template>
      <template v-slot:item.school_id="{ item }">
        <img
          v-if="(item.school.logo_filename != 'nologo')"
          :src="imgUrl(item.school)"
          height="25px"
          width="25px"
        />
        <!-- <v-label> -->
        {{item.school.name}} [{{item.school.abbreviation}}]
        <!-- </v-label> -->
      </template>
      <template v-if="!ro" v-slot:item.action="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
        <v-icon small @click="deleteItem(item)">delete</v-icon>
      </template>
    </v-data-table>
    <!-- </v-layout>
    </v-container>-->
    <v-dialog v-if="!ro" v-model="startWaardeDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Startwaarde invullen</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="functiondata.startwaarde_tot"
                  label="Startwaarde totaal aantal dagen in dit ambt"
                  type="number"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>
        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="blue darken-1" text @click="closeStartwaarde">Annuleren</v-btn>
          <v-btn color="blue darken-1" text @click="saveStartWaarde">Opslaan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-if="!ro" v-model="employmentDialog" max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitleEmployment }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="editedItem.formattedBegin"
                  label="Begin"
                  v-mask="mask"
                  hint="DD-MM-YYYY"
                  @blur="setBegin"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field
                  v-model="editedItem.formattedEnd"
                  label="Einde"
                  v-mask="mask"
                  hint="DD-MM-YYYY"
                  @blur="setEnd"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="12">
                <v-select
                  v-model="editedItem.school_id"
                  :items="scholen"
                  item-text="abbreviation"
                  item-value="id"
                  item-key="id"
                  label="School"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="4" md="4">
                <v-text-field
                  v-if="editedItem"
                  v-model="editedItem.hours"
                  label="Uren"
                  type="number"
                  :suffix="hourSuffix"
                  :disabled="!editedItem.formattedEnd"
                ></v-text-field>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="blue darken-1" text @click="closeEmployment">Annuleren</v-btn>
          <v-btn color="blue darken-1" text @click="saveEmployment">Opslaan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
//import moment from "moment";

import { compareAsc } from "date-fns";
import * as DateUtil from "../DateUtil";

export default {
  props: {
    functiondata: Object,
    scholen: Array
    // fulltimehours : Number,
  },
  data: function() {
    return {
      defaultEmployment: {
        beginDate: new Date(),
        formattedBegin: "", //this.defaultEmployment.beginDate.format('MM-DD-YYYY'),
        endDate: new Date(),
        formattedEnd: "", //this.defaultEmployment.endDate.format('MM-DD-YYYY'),
        hours: "",
        school_id: -1,
        edu_function_data_id: this.functiondata.id
      },
      employments: [],

      editedIndex: -1,
      editedItem: Object.assign({}, this.defaultEmployment),
      employmentDialog: false,
      startWaardeDialog: false,
      startwaarde_tot: 0,//functiondata.startwaarde_tot,

      mask: "##-##-####",

      headers: [
        { text: "Begin", align: "left", value: "beginDate" },
        { text: "Einde", align: "left", value: "endDate" },
        { text: "School", align: "left", value: "school_id" },
        { text: "Uren", align: "left", value: "hours" },
        { text: "", align: "center", value: "action" }
      ]
    };
  },
  computed: {
    formTitleEmployment() {
      return this.editedIndex === -1
        ? "Nieuwe aanstelling"
        : "Bewerk aanstelling";
    },
    hourSuffix() {
      if (this.functiondata.educational_function)
        return "/" + this.functiondata.educational_function.denominator;
      else return "";
    },
    ro() {
      //shorthand for "read only"
      return window.u53r.readonly;
    }
  },
  methods: {
    korteVervanging1Dag() {
      this.editedItem.formattedEnd = this.editedItem.formattedBegin;
      this.editedItem.hours = this.functiondata.educational_function.denominator;
      this.setEnd();
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
        this.emitFail("Fout datumformaat");
        return false;
      }
    },
    setEnd() {
      if (DateUtil.isDate(this.editedItem.formattedEnd)) {
        this.editedItem.endDate = DateUtil.formatDateToDB(
          this.parseDate(this.editedItem.formattedEnd + " 12:00:00")
        );
      } else {
        this.emitFail("Fout datumformaat");
        return false;
      }
    },

    imgUrl: function(school) {
      return (
        "http://www.skbl.be/joomla/images/logo/logo-scholen/" +
        school.logo_filename
      );
    },

    emitFail(msg) {
      this.$emit("fail", msg);
    },
    emitSuccess(msg) {
      this.$emit("success", msg);
    },
    setStartwaarde() {
      this.startWaardeDialog = true;
    },
    closeStartwaarde() {
      this.startWaardeDialog = false;
    },
    saveStartWaarde() {
      let app = this;
      axios
        .patch("api/v1/educationalFunctionData/"+this.functiondata.id+"/setstartwaarde", {'startwaarde_tot' : this.functiondata.startwaarde_tot})
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });

          app.updateSeniorityDays("Startwaarde bewaard");
          app.closeStartwaarde();
        })
        .catch(function(resp) {
          console.log(resp);
          app.emitFail("Fout bij opslaan startwaarde");
        });
    },

    editItem(item) {
      this.editedIndex = this.employments.indexOf(item);

      this.editedItem = Object.assign({}, item);
      this.editedItem.formattedBegin = this.formatDateFromDB(
        this.editedItem.beginDate
      );
      this.editedItem.formattedEnd = this.formatDateFromDB(
        this.editedItem.endDate
      );
      this.employmentDialog = true;
    },
    validateFunctionData() {
      if (!this.editedItem.formattedBegin) {
        this.emitFail("Geen begindatum");
        return false;
      }
      if (!this.editedItem.school_id || this.editedItem.school_id == -1) {
        this.emitFail("Geen school gekozen");
        return false;
      }
      if (!this.editedItem.formattedEnd) this.korteVervanging1Dag(); //creëer een korte vervanging voor 1 dag

      if (
        !(
          DateUtil.isDate(this.editedItem.formattedBegin) &&
          DateUtil.isDate(this.editedItem.formattedEnd)
        )
      ) {
        this.emitFail("Verkeerd datumformaat... kan niet opslaan!");
        return false;
      }
      let compasc = compareAsc(
        DateUtil.parseDate(this.editedItem.formattedBegin),
        DateUtil.parseDate(this.editedItem.formattedEnd)
      );
      if (compasc == 1) {
        this.emitFail("Einddatum mag niet voor begindatum vallen!");
        return false;
      }
      return true;
    },
    deleteItem(item) {
      if (confirm("Aanstelling echt verwijderen?")) {
        var app = this;
        var index = this.employments.indexOf(item);
        axios
          .delete("api/v1/employment/" + item.id)
          .then(function(resp) {
            app.employments.splice(index, 1);
            console.log(JSON.stringify(app.employments));
            app.updateSeniorityDays("");
            app.emitSuccess("Aanstelling verwijderd");
          })
          .catch(function(resp) {
            app.emitFail("Verwijderen mislukt");
          });
      }
    },

    addEmployment() {
      this.editedItem = Object.assign({}, this.defaultEmployment);
      this.editedIndex = -1;
      this.employmentDialog = true;
    },
    saveEmployment() {
      var app = this;
      if (this.validateFunctionData())
        if (this.editedIndex == -1) {
          console.log("adding employment");
          axios //we should retrieve the whole object set again from the server... trust only server data! :) TODO
            .post("api/v1/employment", this.editedItem)
            .then(function(resp) {
              //app.$router.push({ path: "/employees" });
              app.employments.push(resp.data);
              app.updateSeniorityDays("Aanstelling toegevoegd");
              app.closeEmployment();
            })
            .catch(function(resp) {
              console.log(resp);
              app.emitFail("Fout bij aanmaken aanstelling");
            });
        } else {
          console.log("editing employment " + this.editedItem.id);
          delete this.editedItem.school; //remove this property before sending it to the server to prevent mixups
          axios
            .patch("api/v1/employment/" + this.editedItem.id, this.editedItem)
            .then(function(resp) {
              //to keep reactivity
              //console.log("updating functiondata with index "+app.editedIndex);
              Vue.set(
                app.employments,
                app.editedIndex,
                Object.assign({}, resp.data)
              );
              app.updateSeniorityDays("Wijzigingen opgeslagen");

              app.closeEmployment();
            })
            .catch(function(resp) {
              console.log(resp);
              app.emitFail("Fout bij opslaan wijzigingen");
            });
        }
    },
    updateSeniorityDays(successmsg) {
      var app = this;

      axios
        .patch(
          "api/v1/taddCalculator/updateSeniorityDays/" + this.functiondata.id
        )
        .then(function(resp) {
          app.$emit("reloademployeedata", resp.data.employee_id);
          /*
          Vue.set(
            app.employments,
            app.editedIndex,
            Object.assign({}, resp.data)
          );
          app.emitSuccess(successmsg);*/
        })
        .catch(function(resp) {
          console.log(resp);
          app.emitFail("Fout bij berekenen of updaten van dagen anciënniteit");
        });
    },
    deleteEmployment() {},
    closeEmployment() {
      this.employmentDialog = false;
    },

    deleteFunctionData() {
      this.$emit("delete", this.functiondata);
    }
  },
  mounted() {
    this.employments = this.functiondata.employments;
  }
};
</script>