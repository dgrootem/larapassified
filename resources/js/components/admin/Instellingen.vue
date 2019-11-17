<template>
  <div>
    <v-card>
      <v-card-title>
        <span class="headline">Instellingen</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-row>
            <v-card>
              <v-card-title>Grenswaarden</v-card-title>
              <v-card-text>
                <v-data-table :items="grenswaarden" :headers="grensheaders" group-by="categorie"></v-data-table>
              </v-card-text>
            </v-card>
          </v-row>
          <v-row>
            <v-card>
              <v-card-title>Gedrag</v-card-title>
              <v-card-text>
                <v-row dense>
                  <v-col sm="12" md="12">
                    <v-checkbox
                      v-model="appsettings.naarIngaveNaAanmaak.value"
                      :label="labelFor(appsettings.naarIngaveNaAanmaak)"
                    ></v-checkbox>
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col sm="12" md="12">
                    <v-checkbox
                      v-model="appsettings.naarIngaveNaUpdate.value"
                      :label="labelFor(appsettings.naarIngaveNaUpdate)"
                    ></v-checkbox>
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col sm="12" md="12">
                    <v-checkbox
                      v-model="appsettings.showAddForNewEmployee.value"
                      :label="labelFor(appsettings.showAddForNewEmployee)"
                    ></v-checkbox>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-row>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <!-- <div class="flex-grow-1"></div> -->
        <v-btn color="blue darken-1" text @click="save">Opslaan</v-btn>
      </v-card-actions>
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
      appsettings : this.$root.settings,
      grenswaarden: [],
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,
      grensheaders: [
        { text: 'omschrijving', align: 'left', value: 'omschrijving'},
        { text: 'waarde', align: 'left', value: 'value'},
        { text: 'van', align: 'left', value: 'van'},
        { text: 'tot', align: 'left', value: 'tot'}
      ]
    };
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
    
    labelFor(item) {
      return !!item ? item.omschrijving : "";
    },
    save() {
      var app = this;
      axios
        .patch("../api/v1/settings/1", this.settings)
        .then(function(resp) {
          try{
            app.$root.loadSettings();
            app.successSnack("Wijzigingen opgeslagen");
          }
          catch(e){
            console.log(resp);
            app.failSnack("Fout bij ophalen wijzigingen");
          }
        })
        .catch(function(resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
    }
  },
  created(){
    var app = this;
      axios
        .get("../api/v1/settingsByContext/calc", this.grenswaarden)
        .then(function(resp) {
            app.grenswaarden = resp.data;
        })
        .catch(function(resp) {
          console.log(resp);
            app.failSnack("Fout bij ophalen grenswaarden");
        });
  }
  
};
</script>