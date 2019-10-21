<template>
  <div>
    <!-- <v-container fluid>
    <v-layout row>-->
    <v-container>
      <v-row justify="space-between">
        <v-col cols="4" xs="8" sm="5" md="4">
          <v-btn color="primary" @click="addEmployment">Aanstelling toevoegen</v-btn>
        </v-col>
        <v-col cols="4" xs="8" sm="5" md="4">
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
      <template v-slot:item.action="{ item }">
        <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
        <v-icon small @click="deleteItem(item)">delete</v-icon>
      </template>
    </v-data-table>
    <!-- </v-layout>
    </v-container>-->
    <v-dialog v-model="employmentDialog" max-width="500px">
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
                  :suffix="hourSuffix"
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
import { parse, format } from "date-fns";

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
        hours: 0,
        school_id: -1,
        edu_function_data_id: this.functiondata.id
      },
      employments: [],

      editedIndex: -1,
      editedItem: Object.assign({}, this.defaultEmployment),
      employmentDialog: false,

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
    parseDate(val) {
      if (val && val.length>=10){
        let d = val.substring(0,10);
        let pd = parse(d,"dd-MM-yyyy",new Date());
        return pd;
      } else return null;
    },
    formatDate(date) {
      if (date && date.length>=10) {
        let d = this.parseDate(date);
        let f = format(d,"dd-MM-yyyy");
        return f;
      } else return null;
    },
    formatDateFromDB(date) {
      if (date && date.length>=10) {
        let d = date.substring(0,10);
        console.log(d);
        return format(parse(d,"yyyy-MM-dd",new Date()),"dd-MM-yyyy");
        //let f = format(parse(date.substring(0,10), "yyyy-MM-dd", new Date()), "dd-MM-yyyy"); //   moment(date, "YYYY-MM-DD hh:mi:ss").format("DD-MM-YYYY");
        //return f;
      }else return null;
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
    setBegin() {
      this.editedItem.beginDate = this.parseDate(
        this.editedItem.formattedBegin + " 12:00:00"
      );
    },
    setEnd() {
      this.editedItem.endDate = this.parseDate(
        this.editedItem.formattedEnd + " 12:00:00"
      );
    },

    imgUrl: function(school) {
      return (
        "http://www.skbl.be/joomla/images/logo/logo-scholen/" +
        school.logo_filename
      );
    },

    emitFail(msg) {this.$emit('fail',msg);},
    emitSuccess(msg) {this.$emit('success',msg);},

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
    deleteItem(item) {
      if (confirm("Aanstelling echt verwijderen?")) {
        var app = this;
        var index = this.employments.indexOf(item);
        axios
          .delete("api/v1/employment/" + item.id)
          .then(function(resp) {
            app.employments.splice(index, 1);
            app.emitSuccess("Aanstelling verwijderd");
          })
          .catch(function(resp) {
            app.emitFail("Verwijderen mislukt");
          });
      }
    },

    addEmployment() {
      this.editedItem = Object.assign({}, this.defaultEmployment);
      this.employmentDialog = true;
    },
    saveEmployment() {
      var app = this;
      if (this.editedIndex == -1)
        axios //we should retrieve the whole object set again from the server... trust only server data! :) TODO
          .post("api/v1/employment", this.editedItem)
          .then(function(resp) {
            //app.$router.push({ path: "/employees" });
            app.employments.push(resp.data);
            app.emitSuccess("Aanstelling toegevoegd");
          })
          .catch(function(resp) {
            console.log(resp);
            app.emitFail("Fout bij aanmaken aanstelling");
          });
      else {
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
            app.emitSuccess("Wijzigingen opgeslagen");
          })
          .catch(function(resp) {
            console.log(resp);
            app.emitFail("Fout bij opslaan wijzigingen");
          });
      }
      this.closeEmployment();
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