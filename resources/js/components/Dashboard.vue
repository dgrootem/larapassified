<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-text-field
            v-model="search"
            append-icon="search"
            label="Zoeken"
            single-line
            hide-details
          ></v-text-field>
        <v-tabs v-model="dashtab">
          <v-tab key="Volgend jaar gerechtigd" >Volgend jaar gerechtigd
          </v-tab>
          <v-tab key="TADD gerechtigd" >TADD gerechtigd
          </v-tab>
          <v-tab key="TADD" >TADD
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="dashtab">
          <v-tab-item key="Volgend jaar gerechtigd">
            <v-data-table :items="nextYearTADD" :headers="headersVolgendJaarTADD" :sort-by="sortby" :search="search" :items-per-page="10000" hide-default-footer>
              <template v-slot:item.seniority_days_perc="{ item }">
                <v-progress-linear dark height="20" background-opacity="0.5" :value="Number(item.seniority_days_perc)">{{item.seniority_days}}</v-progress-linear>
              </template>
              <template v-slot:item.total_seniority_days_perc="{ item }">
                <v-progress-linear dark height="20" background-opacity="0.5" :value="Number(item.total_seniority_days_perc)">{{item.total_seniority_days}}</v-progress-linear>
              </template>
            </v-data-table>
          </v-tab-item>
          <v-tab-item key="TADD gerechtigd">
            <v-data-table :items="thisYearTADD" :headers="headersVoldoende" :sort-by="sortby" :search="search" :items-per-page="10000" hide-default-footer>
              <template v-slot:item.seniority_days_perc="{ item }">
                <!-- <v-progress-linear :value="item.seniority_days"></v-progress-linear> -->
                {{item.seniority_days}}
              </template>
              <template v-slot:item.total_seniority_days_perc="{ item }">
                <!-- <v-progress-linear :value="item.total_seniority_days"></v-progress-linear> -->
                {{item.total_seniority_days}}
              </template>
              <template v-if="!ro" v-slot:item.werkpunt="{ item }">

                <v-icon :title="'Verwijder werkpunten van '+item.werkpunt" color="red" v-if="!!item.werkpunt && (item.oudsysteem==0)" @click="zetWerkpunten(item,false)">thumb_down</v-icon>
                <v-icon title="Geef werkpunten" color="green" v-if="!item.werkpunt && (item.oudsysteem==0)" @click="zetWerkpunten(item,true)">thumb_up</v-icon>
              </template>
              <template v-if="!ro" v-slot:item.istadd="{ item }">
                <v-icon title="maakTADD" color="gray" v-if="!item.werkpunt" @click="zetTADD(item,true)">star</v-icon>
              </template>
            </v-data-table>
          </v-tab-item>
          <v-tab-item key="TADD">
            <v-data-table :items="alreadyTADD" :headers="headersTADD" :sort-by="sortby" :search="search" :items-per-page="10000" hide-default-footer>
              <template v-slot:item.seniority_days="{ item }">
                <v-progress-linear :value="item.seniority_days_perc"></v-progress-linear>
              </template>
              <template v-slot:item.total_seniority_days="{ item }">
                <v-progress-linear :value="item.total_seniority_days_perc"></v-progress-linear>
              </template>
              <template v-if="!ro" v-slot:item.istadd="{ item }">
                <v-icon title="verwijder TADD status" color="yellow" @click="zetTADD(item,false)">star</v-icon>
              </template>
              <template v-if="!ro" v-slot:item.benoemd="{ item }">
                <v-icon title="maakBenoemd" color="gray" @click="zetBenoemd(item,true)">star</v-icon>
              </template>
            </v-data-table>
          </v-tab-item>
        </v-tabs-items>
      </v-container>
    </v-card>
    
    <v-snackbar v-model="snackbar" bottom :color="snack_color" :timeout="snack_timeout">
      {{ snack_text }}
      <v-btn dark text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
import { compareAsc} from "date-fns";
import * as DateUtil from "../DateUtil";

export default {
  data: function() {
    return {
      message: "Some Message",
      alreadyTADD : [],
      thisYearTADD : [],
      nextYearTADD : [],
      headers: [],
      grenswaarden: [],

      taddNeededTotal : 580,
      taddNeededEffective : 400,
      
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,

      dashtab : null,

      search : null,

      sortby : ['lastname','firstname']
    };
  },

  methods: {
    progressColor(value,max){
      let v = value / max * 100.0;
      if (v < 33) return 'red';
      else if (v < 66) return 'orange';
      else return 'green';
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
    zetWerkpunten(item,state) {
      var app = this;
      var id = item.id;
      if (state)
        axios
          .post("api/v1/educationalFunctionData/"+item.id+"/werkpunt")
          .then(function(resp) {
            item.werkpunt = new Date();
            app.successSnack("Werkpunt toegevoegd");
          })
          .catch(function(resp) {
            app.failSnack("Verwijderen mislukt");
          });
      else
      axios
          .delete("api/v1/educationalFunctionData/"+item.id+"/werkpunt")
          .then(function(resp) {
            item.werkpunt = null;
            app.successSnack("Werkpunt verwijderd");
          })
          .catch(function(resp) {
            app.failSnack("Verwijderen mislukt");
          });
    },
    zetTADD(item,state) {
      var app = this;
      var id = item.id;
      if (state)
        axios
          .post("api/v1/educationalFunctionData/"+item.id+"/tadd")
          .then(function(resp) {
            item.istadd = true;
            app.successSnack("TADD toegevoegd");
            app.alreadyTADD.push(item);
            let index = app.thisYearTADD.findIndex(a => a.id == item.id);
            app.thisYearTADD.splice(index,1);
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("TADD toevoegen mislukt");
          });
      else
        axios
          .delete("api/v1/educationalFunctionData/"+item.id+"/werkpunt")
          .then(function(resp) {
            item.istadd = false;
            app.successSnack("TADD verwijderd");
            app.thisYearTADD.push(item);
            let index = app.alreadyTADD.findIndex(a => a.id == item.id);
            app.alreadyTADD.splice(index,1);
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("TADD verwijderen mislukt");
          });
    },
    grens(name){
      let result = -100;
      let grenzen = [];
      if (this.grenswaarden.length > 0) {
        grenzen = this.grenswaarden.filter(function(f){
          //console.log('====================================');
          console.log(f.name+ '['+f.van+'->'+f.tot+']');
          let compasc1 = compareAsc(DateUtil.parseDateFromDB(f.van),new Date());
          let compasc2 = compareAsc(DateUtil.parseDateFromDB(f.tot),new Date());
          // console.log('van is voor nu ?' + (compasc1 < 1));
          // console.log('tot is na nu ?' + (compasc2 > -1));
          return ((f.name == name) && (compasc1 < 1) && (compasc2 > -1));
        });
        console.log('#grenzen='+grenzen.length);
        if (grenzen.length == 0){
          this.failSnack('Fout bij bepalen grenswaarden');
          result = 100;
        }
        if (grenzen.length == 1) result = grenzen[0].value;
        else{
          
          let grens = grenzen[0].value;
          let i=1;
          for(i=1;i!=grenzen.length;i++) if (grenzen[i].value > grens) grens = grenzen[i].value;
          result = grens;
        }
      }
      console.log(name+'='+result);

      return result;
    },
    laadAmbten(){
      
    }
  },
  

  computed: {
    ro() { //shorthand for "read only"
      return window.u53r.readonly;
    },
    formTitle() {
      return this.editedIndex === -1
        ? "Nieuwe school toevoegen"
        : "Bewerk schoolgegevens";
    },
    volgendJaarTADD(){
      var app = this;
      return this.nextYearTADD;
      /*
      return this.ambten.filter(a =>{
        
        return ((a.total_seniority_days < this.grenswaarden.) || (a.seniority_days < 100));
      });*/
    },
    ambtenVoldoende(){
      return this.thisYearTADD;/*
      var app = this;
      return this.ambten.filter(a =>{
        
        return ((a.total_seniority_days >= 100) && (a.seniority_days >= 100)
                && (a.istadd == 0));
      });*/
    },
    ambtenTADD(){
      return this.alreadyTADD;
      /*return this.ambten.filter(a =>{
        
        return ((a.total_seniority_days >= 100) && (a.seniority_days >= 100)
                && (a.istadd == 1));
      });*/
    },
    
    headersVolgendJaarTADD(){
      return this.headers.filter(h => (h.o ==1));
    },
    headersVoldoende(){
      return this.headers.filter(h => (h.v ==1));
    },
    headersTADD(){
      return this.headers.filter(h => (h.t ==1));
    },
    
    grensEff(){
      return this.grens('taddNeededEffective');
    },
    grensTot(){
      return this.grens('taddNeededTotal');
    }
    

  },
  created() {
    var app = this;
    axios
      .get("api/v1/settingsByContext/calc")
      .then(function(resp) {
        console.log('retrieved grenswaarden');
        app.grenswaarden = resp.data;
        app.laadAmbten();
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load grenswaarden");
      });
    axios
      .get("api/v1/educationalFunctionData/tadd/nextyear")
      .then(function(resp) {
        console.log('loaded nextYearTADD');
        app.nextYearTADD = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load data");
      });
    axios
      .get("api/v1/educationalFunctionData/tadd/thisyear")
      .then(function(resp) {
        app.thisYearTADD = resp.data;
        console.log('loaded thisYearTADD');
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load data");
      });
    axios
      .get("api/v1/educationalFunctionData/tadd/alreadytadd")
      .then(function(resp) {
        app.alreadyTADD = resp.data;
        console.log('loaded alreadyTADD');
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load data");
      });
    
    

    this.headers = [
      { o: 1, v:1,t:1,b:1, text: "Voornaam", align: "left", value: "firstname"                               },
      { o: 1, v:1,t:1,b:1, text: "Naam", align: "left", value: "lastname"                                    },
      { o: 1, v:1,t:1,b:1, text: "Ambt", align: "left", value: "ambt"                                        },
      { o: 1, v:1,t:0,b:0, text: "TOT", align: "center", value: "total_seniority_days_perc", width: "200px"   },
      { o: 1, v:1,t:0,b:0, text: "EFF", align: "center", value: "seniority_days_perc", width: "200px"         },
      { o: 0, v:1,t:0,b:0, text: "werkpunt", align: "center", value: "werkpunt", width: "16px"               },
      { o: 0, v:1,t:1,b:0, text: "TADD", align: "center", value: "istadd", width: "16px"                     },
      { o: 0, v:0,t:1,b:1, text: "benoemd", align: "center", value: "benoemd", width: "16px"                 }
      //{ text: "Benoemd", align: "center", value: "nocount", width: "16px" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component School.vue created.");
  }
};
</script>
