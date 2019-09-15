<template>
  <div>
    <!-- <v-container fluid>
    <v-layout row>-->
    <v-container>
      <v-row justify="space-between">
        <v-col cols="3">
          <v-btn color="green" @click="addEmployment">Aanstelling toevoegen</v-btn>
        </v-col>
        <v-col cols="3">
          <v-btn color="red" @click="deleteFunctionData">
            Verwijder ambt
            <v-icon>delete</v-icon>
          </v-btn>
        </v-col>
      </v-row>
    </v-container>

    <!-- </v-layout>
    <v-layout row>-->
    <v-data-table :items="functiondata.employments"></v-data-table>
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
                <v-text-field v-model="formattedBegin" label="Begin" hint="DD-MM-YYYY"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field v-model="formattedEnd" label="Einde" hint="DD-MM-YYYY"></v-text-field>
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
{{functiondata}}
              <v-col cols="12" sm="4" md="4">
                
                  <v-text-field v-if="editedItem" v-model="editedItem.hours" label="Uren"
                  :suffix="hourSuffix">

                  </v-text-field>
                  
                
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
import moment from "moment";
export default {
  props: {
    functiondata: Object,
    scholen: Array
    // fulltimehours : Number,
  },
  data: function() {
    return {
      defaultEmployment: {
        beginDate: moment(),
        endDate: moment(),
        hours: 0,
        school_id: -1
      },

      editedIndex: -1,
      editedItem: {},
      employmentDialog: false
    };
  },
  computed: {
    formTitleEmployment() {
      return this.editedIndex === -1
        ? "Nieuwe aanstelling"
        : "Bewerk aanstelling";
    },
    hourSuffix(){
      if (this.functiondata.educational_function) return '/' + this.functiondata.educational_function.denominator;
      else return '';
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
    }
  },
  methods: {
    beginToday() {
      this.formattedBegin = moment();
    },
    stopToday() {
      this.formattedEnd = moment();
    },

    emitFail() {},
    emitSuccess() {},

    addEmployment() {
      this.editedItem = Object.assign({}, this.defaultEmployment);
      this.employmentDialog = true;
    },
    saveEmployment() {},
    deleteEmployment() {},
    closeEmployment() {
      this.employmentDialog = false;
    },

    deleteFunctionData() {
      this.$emit("delete", this.functiondata);
    }
  }
};
</script>

<style>
</style>