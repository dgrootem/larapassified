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
                <v-row dense>
                  <v-col sm="12" md="12">
                    <v-label>Benodigd aantal dagen gepresteerd door een personeelslid</v-label>
                  </v-col>
                </v-row>
                <v-row dense align="end">
                  <v-col sm="12" md="8">
                    <v-label>{{labelFor(settings.taddGrens1)}}</v-label>
                  </v-col>
                  <v-col sm="4" md="4">
                    <v-text-field
                      hide-details
                      type="number"
                      min="0"
                      suffix="dagen"
                      v-model="settings.taddGrens1.value"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row dense align="end">
                  <v-col sm="12" md="8">
                    <v-label>{{labelFor(settings.taddGrens2)}}</v-label>
                  </v-col>
                  <v-col sm="4" md="4">
                    <v-text-field
                      hide-details
                      type="number"
                      min="0"
                      suffix="dagen"
                      v-model="settings.taddGrens2.value"
                    ></v-text-field>
                  </v-col>
                </v-row>
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
                      v-model="settings.naarIngaveNaAanmaak.value"
                      :label="labelFor(settings.naarIngaveNaAanmaak)"
                    ></v-checkbox>
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col sm="12" md="12">
                    <v-checkbox
                      v-model="settings.naarIngaveNaUpdate.value"
                      :label="labelFor(settings.naarIngaveNaUpdate)"
                    ></v-checkbox>
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col sm="12" md="12">
                    <v-checkbox
                      v-model="settings.showAddForNewEmployee.value"
                      :label="labelFor(settings.showAddForNewEmployee)"
                    ></v-checkbox>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-row>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <div class="flex-grow-1"></div>
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
      settings: {
        taddGrens1: { name: null, value: null, omschrijving: "" },
        taddGrens2: { name: null, value: null, omschrijving: "" },
        naarIngaveNaAanmaak: { name: null, value: true, omschrijving: "" },
        naarIngaveNaUpdate: { name: null, value: false, omschrijving: "" },
        showAddForNewEmployee: { name: null, value: true, omschrijving: "" }
      },
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000
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
    initSettingsFromDB(settingsFromDB) {
      let app = this;
      let s = null;
      for (s in settingsFromDB) {
        let n = settingsFromDB[s].name;
        app.settings[n] = settingsFromDB[s];
        try {
          app.settings[n].value = parseInt(app.settings[n].value);
        } catch (e) {}
      }
    },
    labelFor(item) {
      return !!item ? item.omschrijving : "";
    },
    save() {
      var app = this;
      axios
        .patch("../api/v1/settings/1", this.settings)
        .then(function(resp) {
          app.initSettingsFromDB(resp.data);
          //Object.assign(app.employees[app.editedIndex], resp.data);
          app.successSnack("Wijzigingen opgeslagen");
        })
        .catch(function(resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
    }
  },
  created() {
    var app = this;
    axios
      .get("../api/v1/settings")
      .then(function(resp) {
        app.initSettingsFromDB(resp.data);
        //app.settings = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load settings");
      });
  }
};
</script>