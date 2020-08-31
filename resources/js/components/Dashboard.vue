<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-row>
          <v-col xs="12" sm="8" md="8">
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Zoeken"
              single-line
              hide-details
            ></v-text-field>
          </v-col>
          <v-col>
            <schoolFilter @selectedSchoolChanged="selectedSchoolChanged" :initialSelection="selectedSchool"></schoolFilter>
          </v-col>
          <v-btn fab right absolute>
            <img align="center" src="https://www.skbl.be/pdf2.png" width="48" height="48"  @click="showPDF()">
          </v-btn>
        </v-row>
        
        <v-row>
          <v-col xs="12" sm="12" md="12" lg="12">
            <v-tabs v-model="dashtab">
              <v-tab key="Volgend jaar gerechtigd">Volgend jaar gerechtigd</v-tab>
              <v-tab key="TADD gerechtigd">TADD gerechtigd</v-tab>
              <v-tab key="TADD">TADD</v-tab>
            </v-tabs>
            <v-tabs-items v-model="dashtab">
              <v-tab-item key="Volgend jaar gerechtigd">
                <v-data-table
                  :items="nextYearTADD"
                  :headers="headersVolgendJaarTADD"
                  :sort-by="sortby"
                  :search="search"
                  :items-per-page="aantalRijenPerPagina"
                  hide-default-footer
                >
                  <template v-slot:item.archived="{ item }">
                    <v-icon color="lightgray" class="mx-4" small v-if="item.archived">mdi-archive</v-icon>
                  </template>
                  <template v-slot:item.seniority_days_perc="{ item }">
                    <v-progress-linear
                      dark
                      height="20"
                      background-opacity="0.5"
                      :value="Number(item.seniority_days_perc)"
                    >{{item.seniority_days}}</v-progress-linear>
                  </template>
                  <template v-slot:item.total_seniority_days_perc="{ item }">
                    <v-progress-linear
                      dark
                      height="20"
                      background-opacity="0.5"
                      :value="Number(item.total_seniority_days_perc)"
                    >{{item.total_seniority_days}}</v-progress-linear>
                  </template>
                </v-data-table>
              </v-tab-item>
              <v-tab-item key="TADD gerechtigd">
                <v-data-table
                  :items="thisYearTADD"
                  :headers="headersVoldoende"
                  :sort-by="sortby"
                  :search="search"
                  :items-per-page="aantalRijenPerPagina"
                  hide-default-footer
                >
                  <template v-slot:item.seniority_days_perc="{ item }">
                    <!-- <v-progress-linear :value="item.seniority_days"></v-progress-linear> -->
                    {{item.seniority_days}}
                  </template>
                  <template v-slot:item.total_seniority_days_perc="{ item }">
                    <!-- <v-progress-linear :value="item.total_seniority_days"></v-progress-linear> -->
                    {{item.total_seniority_days}}
                  </template>
                  <template v-slot:item.werkpunt="{ item }">
                    <v-icon
                      :title="ro?'Heeft werkpunten':'Verwijder werkpunten van '+item.werkpunt"
                      color="red"
                      v-if="!!item.werkpunt && (item.oudsysteem==0)"
                      :class="ro?'nopointer':''"
                      @click="zetWerkpunten(item,false)"
                    >thumb_down</v-icon>
                    <v-icon
                      :title="ro?'Heeft geen werkpunten':'Geef werkpunten'"
                      color="green"
                      :class="ro?'nopointer':''"
                      v-if="!item.werkpunt && (item.oudsysteem==0)"
                      @click="zetWerkpunten(item,true)"
                    >thumb_up</v-icon>
                  </template>
                  <template v-if="!ro" v-slot:item.istadd="{ item }">
                    <v-icon
                      title="maak TADD"
                      color="gray"
                      v-if="!item.werkpunt"
                      @click="zetTADD(item,true)"
                    >star</v-icon>
                  </template>
                </v-data-table>
              </v-tab-item>
              <v-tab-item key="TADD">
                <v-data-table
                  :items="alreadyTADD"
                  :headers="headersTADD"
                  :sort-by="sortby"
                  :search="search"
                  :items-per-page="aantalRijenPerPagina"
                  hide-default-footer
                >
                  <template v-slot:item.seniority_days="{ item }">
                    <v-progress-linear :value="item.seniority_days_perc"></v-progress-linear>
                  </template>
                  <template v-slot:item.total_seniority_days="{ item }">
                    <v-progress-linear :value="item.total_seniority_days_perc"></v-progress-linear>
                  </template>
                  <template v-if="!ro" v-slot:item.istadd="{ item }">
                    <v-icon
                      title="verwijder TADD status"
                      color="yellow"
                      @click="zetTADD(item,false)"
                    >star</v-icon>
                  </template>
                  <template v-if="!ro" v-slot:item.benoemd="{ item }">
                    <v-icon title="benoemd of weg" color="gray" @click="zetBenoemd(item,true)">star</v-icon>
                  </template>
                </v-data-table>
              </v-tab-item>
            </v-tabs-items>
          </v-col>
        </v-row>
      </v-container>
    </v-card>

    <v-snackbar v-model="snackbar" bottom :color="snack_color" :timeout="snack_timeout">
      {{ snack_text }}
      <v-btn dark text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
    <v-overlay :value="overlay">
      {{ overlay_message }}
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>
import { compareAsc } from "date-fns";
import * as DateUtil from "../DateUtil";
import SchoolFilter from "./SchoolFilter";

export default {
  components: {
    schoolFilter: SchoolFilter
  },
  data: function() {
    return {
      message: "Some Message",
      alreadyTADD: [],
      thisYearTADD: [],
      nextYearTADD: [],
      headers: [],
      grenswaarden: [],

      selectedSchool: null,

      taddNeededTotal: 580,
      taddNeededEffective: 400,

      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,

      dashtab: null,

      search: null,

      sortby: ["fullname"],

      overlay : false,
      overlay_message : '',

      fullList : 1
    };
  },

  methods: {
    showPDF(){
      let app = this;
      //debugger;
      app.overlay = true;
      app.overlay_message = 'Bezig met samenstellen PDF document...';
      let listtype;
      switch(this.dashtab){
        case 0 : listtype = 'nextyear'; break;
        case 1 : listtype = 'thisyear'; break;
        case 2 : listtype = 'alreadytadd'; break;
        default : listtype = null;
      }
      axios({
        url: this.buildURL(listtype,'pdf',this.fullList,this.selectedSchool),
        responseType: 'blob', // important
      }).then((response) => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'overzicht.pdf'); //or any other extension
        document.body.appendChild(link);
        app.overlay = false;
        link.click();
      }).catch((reponse) => {
        app.overlay_message = 'Oops! Er ging iets mis bij het genereren van het PDF document...';
        setTimeout(function(){
          app.overlay = false;
        },2000);
      });
      //window.open('api/v1/employee/pdf/'+selectedItem.id,'_blank');
    },
    progressColor(value, max) {
      let v = (value / max) * 100.0;
      if (v < 33) return "red";
      else if (v < 66) return "orange";
      else return "green";
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
    editItem(item) {
      this.editedIndex = this.scholen.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    zetWerkpunten(item, state) {
      if (this.ro) return;
      var app = this;
      var id = item.id;
      if (state)
        axios
          .post("api/v1/educationalFunctionData/" + item.id + "/werkpunt")
          .then(function(resp) {
            item.werkpunt = new Date();
            app.successSnack("Werkpunt toegevoegd");
          })
          .catch(function(resp) {
            app.failSnack("Werkpunt toevoegen mislukt");
          });
      else
        axios
          .delete("api/v1/educationalFunctionData/" + item.id + "/werkpunt")
          .then(function(resp) {
            item.werkpunt = null;
            app.successSnack("Werkpunt verwijderd");
          })
          .catch(function(resp) {
            app.failSnack("Werkpunt verwijderen mislukt");
          });
    },
    zetTADD(item, state) {
      if (this.ro) return;
      var app = this;
      var id = item.id;
      if (state)
        axios
          .post("api/v1/educationalFunctionData/" + item.id + "/tadd")
          .then(function(resp) {
            item.istadd = true;
            app.successSnack("TADD toegevoegd");
            app.alreadyTADD.push(item);
            let index = app.thisYearTADD.findIndex(a => a.id == item.id);
            app.thisYearTADD.splice(index, 1);
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("TADD toevoegen mislukt");
          });
      else
        axios
          .delete("api/v1/educationalFunctionData/" + item.id + "/tadd")
          .then(function(resp) {
            item.istadd = false;
            app.successSnack("TADD verwijderd");
            app.thisYearTADD.push(item);
            let index = app.alreadyTADD.findIndex(a => a.id == item.id);
            app.alreadyTADD.splice(index, 1);
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("TADD verwijderen mislukt");
          });
    },
    zetBenoemd(item, state) {
      if (this.ro) return;
      var app = this;
      var id = item.id;
      if (state)
        axios
          .post("api/v1/educationalFunctionData/" + item.id + "/benoemd")
          .then(function(resp) {
            item.isbenoemd = true;
            app.successSnack("Benoeming toegevoegd");
            //app.alreadyTADD.push(item);
            let index = app.alreadyTADD.findIndex(a => a.id == item.id);
            app.alreadyTADD.splice(index, 1);
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Benoeming toevoegen mislukt");
          });
      else
        axios
          .delete("api/v1/educationalFunctionData/" + item.id + "/benoemd")
          .then(function(resp) {
            item.istadd = false;
            app.successSnack("Benoeming verwijderd");
            app.thisYearTADD.push(item);
            let index = app.alreadyTADD.findIndex(a => a.id == item.id);
            app.alreadyTADD.splice(index, 1);
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Benoeming verwijderen mislukt");
          });
    },
    grens(name) {
      let result = -100;
      let grenzen = [];
      if (this.grenswaarden.length > 0) {
        grenzen = this.grenswaarden.filter(function(f) {
          //console.log('====================================');
          console.log(f.name + "[" + f.van + "->" + f.tot + "]");
          let compasc1 = compareAsc(
            DateUtil.parseDateFromDB(f.van),
            new Date()
          );
          let compasc2 = compareAsc(
            DateUtil.parseDateFromDB(f.tot),
            new Date()
          );
          // console.log('van is voor nu ?' + (compasc1 < 1));
          // console.log('tot is na nu ?' + (compasc2 > -1));
          return f.name == name && compasc1 < 1 && compasc2 > -1;
        });
        console.log("#grenzen=" + grenzen.length);
        if (grenzen.length == 0) {
          this.failSnack("Fout bij bepalen grenswaarden");
          result = 100;
        }
        if (grenzen.length == 1) result = grenzen[0].value;
        else {
          let grens = grenzen[0].value;
          let i = 1;
          for (i = 1; i != grenzen.length; i++)
            if (grenzen[i].value > grens) grens = grenzen[i].value;
          result = grens;
        }
      }
      console.log(name + "=" + result);

      return result;
    },
    laadAmbten() {},
    reloadTabs(){
      let app = this;
      axios
      .get(this.buildURL('nextyear','html',this.fullList,this.selectedSchool))
      .then(function(resp) {
        console.log("loaded nextYearTADD");
        app.nextYearTADD = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load data");
      });
      axios
        .get(this.buildURL('thisyear','html',this.fullList,this.selectedSchool))
        .then(function(resp) {
          app.thisYearTADD = resp.data;
          console.log("loaded thisYearTADD");
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load data");
        });
      axios
        .get(this.buildURL('alreadytadd','html',this.fullList,this.selectedSchool))
        .then(function(resp) {
          app.alreadyTADD = resp.data;
          console.log("loaded alreadyTADD");
        })
        .catch(function(resp) {
          console.log(resp);
          alert("Could not load data");
        });

    },
    selectedSchoolChanged(newSelectedSchool){
      console.log('selectedSchool changed to'+newSelectedSchool);
      this.selectedSchool = newSelectedSchool;
      this.reloadTabs();
    },
    buildURL(listtype,output,fullList,selectedSchool){
      return "api/v1/educationalFunctionData/tadd/" + listtype +"/"+output+"/"+fullList+"/bySchool/"+(this.selectedSchool?this.selectedSchool:'-1');
    }
  },

  computed: {
    ro() {
      //shorthand for "read only"
      return window.u53r.readonly;
    },
    formTitle() {
      return this.editedIndex === -1
        ? "Nieuwe school toevoegen"
        : "Bewerk schoolgegevens";
    },
    volgendJaarTADD() {
      var app = this;
      return this.nextYearTADD;
    },
    ambtenVoldoende() {
      return this
        .thisYearTADD; 
    },
    ambtenTADD() {
      return this.alreadyTADD;
    },
    

    headersVolgendJaarTADD() {
      return this.headers.filter(h => h.o == 1);
    },
    headersVoldoende() {
      return this.headers.filter(h => h.v == 1);
    },
    headersTADD() {
      return this.headers.filter(h => h.t == 1);
    },

    grensEff() {
      return this.grens("taddNeededEffective");
    },
    grensTot() {
      return this.grens("taddNeededTotal");
    },
    aantalRijenPerPagina: function() {
      return 10000;
      //return this.$root.settings.aantalRijenPerLijst.value;
    }
  },
  created() {
    var app = this;
    axios
      .get("api/v1/settingsByContext/calc")
      .then(function(resp) {
        console.log("retrieved grenswaarden");
        app.grenswaarden = resp.data;
        app.laadAmbten();
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load grenswaarden");
      });
      this.reloadTabs();
    
    this.headers = [
      {
        o: 1,
        v: 1,
        t: 1,
        b: 1,
        text: "Voornaam",
        align: "left",
        value: "firstname"
      },
      {
        o: 1,
        v: 1,
        t: 1,
        b: 1,
        text: "Naam",
        align: "left",
        value: "lastname"
      },
      // { o: 1, v:1,t:1,b:1, text: "Naam2", align: "left", value: "fullname"                                    },
      { o: 1, v: 1, t: 1, b: 1, text: "", align: "right", value: "hascomment", width: "10px" },
      { o: 1, v: 1, t: 1, b: 1, text: "", align: "right", value: "archived", width: "10px" },
      { o: 1, v: 1, t: 1, b: 1, text: "Ambt", align: "left", value: "ambt" },
      {
        o: 1,
        v: 1,
        t: 0,
        b: 0,
        text: "TOT",
        align: "center",
        value: "total_seniority_days_perc",
        width: "200px"
      },
      {
        o: 1,
        v: 1,
        t: 0,
        b: 0,
        text: "EFF",
        align: "center",
        value: "seniority_days_perc",
        width: "200px"
      },
      {
        o: 0,
        v: 1,
        t: 0,
        b: 0,
        text: "werkpunt",
        align: "center",
        value: "werkpunt",
        width: "16px"
      },
      {
        o: 0,
        v: this.ro ? 0 : 1,
        t: this.ro ? 0 : 1,
        b: 0,
        text: "TADD",
        align: "center",
        value: "istadd",
        width: "16px"
      },
      {
        o: 0,
        v: 0,
        t: this.ro ? 0 : 1,
        b: this.ro ? 0 : 1,
        text: "benoemd of weg",
        align: "center",
        value: "benoemd",
        width: "16px"
      }
      //{ text: "Benoemd", align: "center", value: "nocount", width: "16px" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component School.vue created.");
  }
};
</script>
