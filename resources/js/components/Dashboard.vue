<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-tabs v-model="dashtab">
          <v-tab key="voldoende" >Voldoende
          </v-tab>
          <v-tab key="TADD" >TADD
          </v-tab>
          <v-tab key="benoemd" >Benoemd
          </v-tab>
          <v-tab key="onvoldoende" >Onvoldoende
          </v-tab>
        </v-tabs>
        <v-tabs-items v-model="dashtab">
          
          <v-tab-item key="voldoende">
            <v-data-table :items="ambtenVoldoende" :headers="headersVoldoende">
              <template v-slot:item.seniority_days="{ item }">
                <v-progress-linear :value="item.seniority_days"></v-progress-linear>
              </template>
              <template v-slot:item.total_seniority_days="{ item }">
                <v-progress-linear :value="item.total_seniority_days"></v-progress-linear>
              </template>
              <template v-slot:item.werkpunt="{ item }">
                <v-icon :title="'Verwijder werkpunten van '+item.werkpunt" color="red" v-if="!!item.werkpunt" @click="zetWerkpunten(item,false)">thumb_down</v-icon>
                <v-icon title="Geef werkpunten" color="green" v-if="!item.werkpunt" @click="zetWerkpunten(item,true)">thumb_up</v-icon>
              </template>
              <template v-slot:item.istadd="{ item }">
                <v-icon title="maakTADD" color="gray" v-if="!item.werkpunt" @click="zetTADD(item,true)">star</v-icon>
              </template>
            </v-data-table>
          </v-tab-item>
          <v-tab-item key="TADD">
            <v-data-table :items="ambtenTADD" :headers="headersTADD">
              <template v-slot:item.seniority_days="{ item }">
                <v-progress-linear :value="item.seniority_days"></v-progress-linear>
              </template>
              <template v-slot:item.total_seniority_days="{ item }">
                <v-progress-linear :value="item.total_seniority_days"></v-progress-linear>
              </template>
              <template v-slot:item.istadd="{ item }">
                <v-icon title="verwijder TADD status" color="yellow" @click="zetTADD(item,false)">star</v-icon>
              </template>
              <template v-slot:item.benoemd="{ item }">
                <v-icon title="maakBenoemd" color="gray" @click="zetBenoemd(item,true)">star</v-icon>
              </template>
            </v-data-table>
          </v-tab-item>
          <v-tab-item key="benoemd">
            <v-data-table :items="ambtenBenoemd" :headers="headersBenoemd">
              
            </v-data-table>
          </v-tab-item>
          <v-tab-item key="onvoldoende">
            <v-data-table :items="ambtenOnvoldoende" :headers="headersOnvoldoende">
              <template v-slot:item.seniority_days="{ item }">
                <v-progress-linear :value="item.seniority_days"></v-progress-linear>
              </template>
              <template v-slot:item.total_seniority_days="{ item }">
                <v-progress-linear :value="item.total_seniority_days"></v-progress-linear>
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
export default {
  data: function() {
    return {
      message: "Some Message",
      ambten : [],
      headers: [],

      taddNeededTotal : 580,
      taddNeededEffective : 400,
      
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,

      dashtab : null
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
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1
        ? "Nieuwe school toevoegen"
        : "Bewerk schoolgegevens";
    },
    ambtenOnvoldoende(){
      var app = this;
      return this.ambten.filter(a =>{
        
        return ((a.total_seniority_days < 100) || (a.seniority_days < 100));
      });
    },
    ambtenVoldoende(){
      var app = this;
      return this.ambten.filter(a =>{
        
        return ((a.total_seniority_days >= 100) && (a.seniority_days >= 100)
                && (a.istadd == 0));
      });
    },
    ambtenTADD(){
      var app = this;
      return this.ambten.filter(a =>{
        
        return ((a.total_seniority_days >= 100) && (a.seniority_days >= 100)
                && (a.istadd == 1));
      });
    },
    ambtenBenoemd(){
      var app = this;
      return this.ambten.filter(a =>{
        return [];
        /*return ((a.total_seniority_days >= 100) && (a.seniority_days >= 100)
                && (a.istadd == 1));*/
      });
    },
    headersOnvoldoende(){
      return this.headers.filter(h => (h.o ==1));
    },
    headersVoldoende(){
      return this.headers.filter(h => (h.v ==1));
    },
    headersTADD(){
      return this.headers.filter(h => (h.t ==1));
    },
    headersBenoemd(){
      return this.headers.filter(h => (h.b ==1));
    },

  },

  created() {
    var app = this;
    axios
      .get("api/v1/educationalFunctionData/tadd/fullIndex")
      .then(function(resp) {
        app.ambten = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load schools");
      });

    this.headers = [
      { text: "Voornaam", align: "left", value: "firstname" , o: 1, v:1,t:1,b:1},
      { text: "Naam", align: "left", value: "lastname" , o: 1, v:1,t:1,b:1},
      { text: "Ambt", align: "left", value: "ambt" , o: 1, v:1,t:1,b:1},
      { text: "TOT", align: "center", value: "total_seniority_days", width: "16px" , o: 1, v:1,t:0,b:0},
      { text: "EFF", align: "center", value: "seniority_days", width: "16px" , o: 1, v:1,t:0,b:0},
      { text: "werkpunt", align: "center", value: "werkpunt", width: "16px" , o: 0, v:1,t:0,b:0},
      { text: "TADD", align: "center", value: "istadd", width: "16px" , o: 0, v:1,t:1,b:0},
      { text: "benoemd", align: "center", value: "benoemd", width: "16px" , o: 0, v:0,t:1,b:1}
      //{ text: "Benoemd", align: "center", value: "nocount", width: "16px" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component School.vue created.");
  }
};
</script>
