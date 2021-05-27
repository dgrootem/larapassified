<template>
  <div>
    <!-- <v-container fluid>
    <v-layout row>-->
    <v-container>
      <v-row>
        <v-label>Reeds opgebouwde dagen: {{functiondata.total_seniority_days}} TOT / {{functiondata.seniority_days}} EFF</v-label>
      </v-row>
      <v-row justify="space-between">
        <v-col cols="4" xs="9" sm="9" md="9">
          <v-btn v-if="!ro" color="primary" @click="addEmployment">Aanstelling toevoegen</v-btn>
          <v-btn v-if="!ro" color="warning" @click="addRating">
            Beoordeling toevoegen
            <v-icon dark v-if="functiondata.comment && !ro" class="mx-4 small" color="grey" >message</v-icon>
          </v-btn>
          <v-btn v-if="!ro" color="secondary" @click="setProperties">
            Eigenschappen
            <v-icon dark v-if="functiondata.comment && !ro" class="mx-4 small" color="grey" >message</v-icon>
          </v-btn>
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
    <v-data-table :items="employments" :headers="headers" v-if="employments.length > 0" :items-per-page="aantalRijenPerPagina" :item-class="itemRowBackground">
      <template v-slot:item.beginDate="{ item }">
        <span
          :style="item.school && !item.school.useForCalculations ? 'text-decoration: line-through !important;':''"
        >{{ formatDateFromDB(item.beginDate)}}</span>
      </template>
      <template v-slot:item.endDate="{ item }">
        <span v-if="item.my_type=='EMP'"
          :style="!item.school.useForCalculations ? 'text-decoration: line-through !important;':''"
        >{{ formatDateFromDB(item.endDate)}}</span>
      </template>
      <template v-slot:item.school_id="{ item }">
        <img 
          v-if="item.my_type=='EMP' && item.school && ( item.school.logo_filename != 'nologo')"
          :src="imgUrl(item.school)"
          height="25px"
          width="25px"
        />
        <!-- <v-label> -->
        <span v-if="item.my_type=='EMP'" :style="!item.school.useForCalculations ? 'text-decoration: line-through !important;':''">{{item.school.name}} [{{item.school.abbreviation}}]</span>
        <span v-if="item.my_type=='RAT'">{{ratingOmschrijving(item)}}</span>
        <!-- </v-label> -->
      </template>
      <template v-slot:item.hours="{ item }">
        <span v-if="item.my_type=='EMP'" :style="!item.school.useForCalculations ? 'text-decoration: line-through !important;':''">{{item.hours}}</span>
      </template>
      <template v-if="!ro" v-slot:item.action="{ item }">
        <v-icon v-if="item.my_type=='EMP'" small class="mr-2" title="Aanstelling aanpassen" @click="editEmployment(item)">edit</v-icon>
        <v-icon v-if="item.my_type=='RAT'" small class="mr-2" title="Beoordeling aanpassen" @click="editRating(item)">edit</v-icon>
        <v-icon v-if="item.my_type=='EMP'" small title="Aanstelling verwijderen" @click="deleteEmployment(item)">delete</v-icon>
        <v-icon v-if="item.my_type=='RAT'" small title="Beoordeling verwijderen" @click="deleteRating(item)">delete</v-icon>
      </template>
    </v-data-table>
    <!-- </v-layout>
    </v-container>-->
    <v-dialog v-if="!ro" v-model="propertiesDialog" max-width="700px">
      <v-card>
        <v-card-title>
          <span class="headline">Eigenschappen bewerken</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col>
                <v-text-field
                  v-model="functiondata.startwaarde_tot"
                  label="Startwaarde totaal aantal dagen in dit ambt"
                  type="number"
                ></v-text-field>
              </v-col><v-col>
                <v-text-field
                  v-model="functiondata.startwaarde_int"
                  label="Startwaarde dagen onderbreking in dit ambt"
                  type="number"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row dense>
              <v-col md="4" sm="12">
                <h3 class="mt-4">Toestand</h3>
                <!-- We disablen de checkboxen zodat je via deze weg niet iemand kan forceren naar TADD of benoemd -->
                <v-checkbox :disabled="!functiondata.isTadd" v-model="functiondata.isTadd" hide-details label="TADD"></v-checkbox>
                <v-checkbox :disabled="!functiondata.isBenoemd" v-model="functiondata.isBenoemd" hide-details label="Benoemd"></v-checkbox>
                <h3 class="mt-4">Archivering</h3>
                <v-checkbox :color="functiondata.archived_final?'warning':'info'" v-model="functiondata.archived_final" hide-details label="Permanent" @change="updateArchiveTemp"></v-checkbox>
                <v-checkbox :color="functiondata.archived_temporary?'warning':'info'" v-model="functiondata.archived_temporary" hide-details label="Tijdelijk" @change="updateArchiveFinal"></v-checkbox>
               
                <v-checkbox :append_icon="has_auto_archive ? 'help':'' " readonly :color="functiondata.archived_auto?'warning':'info'" v-model="functiondata.archived_auto" hide-details>
                  <template v-slot:label>
                    Automatisch<v-icon v-if="has_auto_archive" class="ml-6" @click="auto_archive_help = !auto_archive_help">help</v-icon>
                  </template>
                </v-checkbox>
                
                
                
              </v-col>
              <v-col><v-textarea vif="!ro" v-model="functiondata.comment" outlined label="Opmerking" rows="6"></v-textarea></v-col>
            </v-row>
            <v-row v-if="has_auto_archive && auto_archive_help">
              <v-list dense>Automatisch gearchiveerd wegens:</v-list>
              <v-list-item v-for="(item,i) in auto_reasons" :key="i">{{item}}</v-list-item>
              </v-row>

          </v-container>
        </v-card-text>
        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="blue darken-1" text @click="closeStartwaarde">Annuleren</v-btn>
          <v-btn color="blue darken-1" text @click="saveProperties">Opslaan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-dialog v-if="!ro" v-model="ratingDialog" max-width="500px">
      <beoordelingencomp 
        :functiondata="functiondata" 
        :rating="editedRating" 
        :ratingTypes="ratingTypes"
        v-on:saverating="handleSaveRating"
        v-on:cancelrating="handleCancelRating"
        v-on:fail="emitFail"
      ></beoordelingencomp>
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
import Beoordelingen from "./Beoordelingen.vue";

export default {
  components:{
    beoordelingencomp : Beoordelingen
  },
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
      default_rating :{
        id: null,
        datum : null,
        formattedDatum : DateUtil.todayString(),
        edu_function_data_id : this.functiondata.id,
        rating_type_id : null,
        my_type : 'RAT',
        my_class : 'rat_unknown',
        beginDate : null,
        endDate : null,
        hours : null

      },
      editedRating: null,
      ratingTypes: [],
      employments: [],

      editEmploymentIndex: -1,
      editedRatingIndex: -1,
      editedItem: Object.assign({}, this.defaultEmployment),
      employmentDialog: false,
      propertiesDialog: false,
      ratingDialog:false,
      auto_archive_help : false,
      //startwaarde_tot: 0, //functiondata.startwaarde_tot,

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
      return this.editEmploymentIndex === -1
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
    },
    aantalRijenPerPagina: function(){
      return this.$root.settings.aantalRijenPerLijst.value;
    },
    auto_reasons: function(){
      
      if (this.functiondata){
        console.log('Auto-reason='+this.functiondata.auto_reason);
        return this.functiondata.auto_reason.split(',');
      }
      else return null;
    },
    has_auto_archive: function(){
      return this.functiondata.archived_auto;// && !!this.functiondata.auto_reason;
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
    updateArchiveTemp(){
      if (this.functiondata.archived_final == true ) this.functiondata.archived_temporary = false;
    },
    updateArchiveFinal(){
      if (this.functiondata.archived_temporary == true ) this.functiondata.archived_final = false;
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
    itemRowBackground: function(item){
      return item.my_class;
    },

    emitFail(msg) {
      this.$emit("fail", msg);
    },
    emitSuccess(msg) {
      this.$emit("success", msg);
    },
    setProperties() {
      this.propertiesDialog = true;
    },
    findItemIndexInEmployments(item){
      for(let index in this.employments){
        if ((this.employments[index].my_type == item.my_type) && (this.employments[index].id == item.id)) return index;
      }
      return -1;
    },
    editRating(item){
      this.editedRatingIndex = this.findItemIndexInEmployments(item);// this.employments.indexOf(item);
      this.editedRating = Object.assign({}, item);
      this.editedRating.formattedDatum = this.formatDateFromDB(
        this.editedRating.datum
      );
      this.ratingDialog = true;
    },
    addRating(){
      this.editedRatingIndex = -1;
      this.editedRating = Object.assign({}, this.default_rating);
      this.ratingDialog = true;
    },
    handleSaveRating(){
      var app = this;
      if (this.editedRatingIndex == -1) {
        console.log("adding rating");
        axios //we should retrieve the whole object set again from the server... trust only server data! :) TODO
          .post("api/v1/ratings", this.editedRating)
          .then(function(resp) {
            //app.$router.push({ path: "/employees" });
            let rating = resp.data;
            let fake_emp = app.processRating(rating);
            app.employments.push(fake_emp);
            app.emitSuccess('Beoordeling toegevoegd');
            app.closeRatingDialog();
          })
          .catch(function(resp) {
            console.log(resp);
            app.emitFail("Fout bij aanmaken beoordeling");
          });
      } else {
        console.log("editing rating " + this.editedRating.id);
        delete this.editedRating.rating_type; //remove this property before sending it to the server to prevent mixups
        axios
          .patch("api/v1/ratings/" + this.editedRating.id, this.editedRating)
          .then(function(resp) {
            //to keep reactivity
            //console.log("updating functiondata with index "+app.editEmploymentIndex);
            let rating = resp.data;
            let fake_emp = app.processRating(rating);
            
            Vue.set(
              app.employments,
              app.editedRatingIndex,
              Object.assign({}, fake_emp)
            );
            app.emitSuccess('Wijzigingen opgeslagen');
            //app.updateSeniorityDays("Wijzigingen opgeslagen");

            app.closeRatingDialog();
          })
          .catch(function(resp) {
            console.log(resp);
            app.emitFail("Fout bij opslaan wijzigingen");
          });
      }
    },
    deleteRating(rating) {
      if (confirm("Beoordeling echt verwijderen?")) {
        var app = this;
        var index = this.employments.indexOf(rating);
        axios
          .delete("api/v1/ratings/" + rating.id)
          .then(function(resp) {
            app.employments.splice(index, 1);
            //console.log(JSON.stringify(app.employments));
            //app.updateSeniorityDays("");  //TODO: bij beoordeling met werkpunten moet er wel terug berekend worden, TODO: veld toevoegen voor senioritydays since last evaluation
            app.emitSuccess("Beoordeling verwijderd");
          })
          .catch(function(resp) {
            app.emitFail("Verwijderen mislukt");
          });
      }
    },
    handleCancelRating(){
      this.closeRatingDialog();
    },
    closeRatingDialog(){
      this.ratingDialog = false;
    },
    closeStartwaarde() {
      this.propertiesDialog = false;
    },
    saveProperties() {
      let app = this;
      axios
        .patch(
          "api/v1/educationalFunctionData/" +
            this.functiondata.id +
            "/setproperties",
          { 
            startwaarde_tot: this.functiondata.startwaarde_tot, 
            startwaarde_int: this.functiondata.startwaarde_int,
            isTadd : this.functiondata.isTadd,
            isBenoemd : this.functiondata.isBenoemd,
            comment : this.functiondata.comment,
            archived_final : this.functiondata.archived_final,
            archived_temporary : this.functiondata.archived_temporary
            }
        )
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });

          app.updateSeniorityDays("Startwaarde bewaard");
          app.emitSuccess("Eigenschappen bijgewerkt");
          app.closeStartwaarde();
        })
        .catch(function(resp) {
          console.log(resp);
          app.emitFail("Fout bij bijwerken van de eigenschappen");
        });
    },

    editEmployment(item) {
      this.editEmploymentIndex = this.findItemIndexInEmployments(item); //this.employments.indexOf(item);

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
    deleteEmployment(item) {
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
      this.editEmploymentIndex = -1;
      this.employmentDialog = true;
    },
    saveEmployment() {
      var app = this;
      if (this.validateFunctionData())
        if (this.editEmploymentIndex == -1) {
          console.log("adding employment");
          axios //we should retrieve the whole object set again from the server... trust only server data! :) TODO
            .post("api/v1/employment", this.editedItem)
            .then(function(resp) {
              //app.$router.push({ path: "/employees" });
              let emp = resp.data;
              emp.my_type = 'EMP';
              app.employments.push(emp);
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
              //console.log("updating functiondata with index "+app.editEmploymentIndex);
              let emp = resp.data;
              emp.my_type = 'EMP';
              Vue.set(
                app.employments,
                app.editEmploymentIndex,
                Object.assign({}, emp)
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
            app.editEmploymentIndex,
            Object.assign({}, resp.data)
          );
          app.emitSuccess(successmsg);*/
        })
        .catch(function(resp) {
          console.log(resp);
          app.emitFail("Fout bij berekenen of updaten van dagen anciënniteit");
        });
    },
    closeEmployment() {
      this.employmentDialog = false;
    },

    deleteFunctionData() {
      this.$emit("delete", this.functiondata);
    },
    ratingOmschrijving(rating){
      return rating.ratingtype.omschrijving + ': '+ (rating.positief == 1 ? rating.ratingtype.omschrijving_pos : rating.ratingtype.omschrijving_neg);

    },
    processRating(rating){
      let result;
      let my_class;
      if (rating.positief == 1) 
      {
        result = '+' ;
        my_class = 'rat_pos';
      }
      else if (rating.positief == 0) 
      {
        result = '-';
        my_class = 'rat_neg';
      }
      else
      {
        result = '?';
        my_class = 'rat_unknown';
      }
      let processed_rating = Object.assign({
        my_type : 'RAT',
        my_class : my_class,
        beginDate : null,
        endDate : null,
        hours : null,
      }, rating);
      processed_rating.beginDate = processed_rating.datum;
      return processed_rating;
    },
  },
  mounted() {
    let app = this;
    axios
      .get("api/v1/ratingtypes")
      .then(function(resp) {
          app.ratingTypes = resp.data.ratingTypes;
      })
      .catch(function(resp) {
          console.log(resp);
          alert("Could not load ratingTypes");
      });
    for(let employment in this.functiondata.employments) this.functiondata.employments[employment].my_type = 'EMP';
    for(let rating in this.functiondata.ratings){
      
      let fake_emp = this.processRating(this.functiondata.ratings[rating]);
      
      this.functiondata.employments.push(fake_emp);
    }
    this.employments = this.functiondata.employments;

    
  }
};
</script>