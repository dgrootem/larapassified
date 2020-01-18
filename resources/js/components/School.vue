<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-card-title>
          Scholen overzicht<v-icon class="ml-6" @click="help = !help">help</v-icon>
          <v-btn fab right absolute @click="dialog = !dialog">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-row v-if="help">
          <v-col lg="6" md="8" sm="12" >
          <p><i>Wanneer je een school </i>onzichtbaar<i> <v-icon>visibility_off</v-icon> zet kan je ze niet meer kiezen in het ingavescherm.</i></p>
          <p><i>Wanneer je een school op </i>niet meenemen in berekening<i> <v-icon color="red">exposure_zero</v-icon> 
          zet zullen de dagen herberekend worden voor alle personeelsleden die ooit in die school gewerkt hebben en nog niet TADD zijn. 
          Alle personeelsleden die <b>enkel</b> in deze school gewerkt hebben zullen worden onzichtbaar gezet.</i></p>
          <p>OPM: Als je de archivering gebruikt moet je deze best opnieuw laten lopen nadat je een school opnieuw laat meetellen omdat er mogelijk 
          teveel personeelsleden zijn geheractiveerd</p>
          </v-col>
          </v-row>
          <v-data-table :items="scholen" :headers="headers" :items-per-page="aantalRijenPerPagina">
            <template v-slot:item.logo="{ item }">
              <img
                v-if="(item.logo_filename != 'nologo')"
                :src="imgUrl(item)"
                height="25px"
                width="25px"
              />
            </template>
            <template v-slot:item.edit="{ item }">
              <v-icon title="Bewerken" small class="mr-2" @click="editItem(item)">edit</v-icon>
            </template>
            <template v-slot:item.delete="{ item }">
              <v-icon title="Verwijderen" v-if="item.cbd" small @click="deleteItem(item)">delete</v-icon>
            </template>
            <template v-slot:item.invis="{ item }">
              <v-icon
                :title="item.isActive ?'Zichtbaar in lijsten':'NIET zichtbaar in lijsten' "
                small
                class="mr-2"
                @click="toggleVisibility(item)"
              >{{ item.isActive ? 'visibility' : 'visibility_off' }}</v-icon>
            </template>
            <template v-slot:item.nocount="{ item }">
              <v-icon
                :title="item.useForCalculations ?'Meenemen in berekening':'NIET meenemen in berekening' "
                small
                :color="item.useForCalculations ?'green':'red'"
                @click="toggleNocount(item)"
              >{{ item.useForCalculations ? "exposure":"exposure_zero"}}</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-container>
    </v-card>
    <v-dialog v-model="dialog" max-width="500px">
      <!-- <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" v-on="on">Nieuwe school toevoegen</v-btn>
      </template>-->
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col  sm="6" md="4">
                <v-text-field v-model="editedItem.abbreviation" label="Afkorting"></v-text-field>
              </v-col>
              <v-col sm="6" md="8">
                <v-text-field v-model="editedItem.name" label="Naam"></v-text-field>
              </v-col>

              <v-col m="12" md="12">
                <v-text-field v-model="editedItem.adres" label="Adres"></v-text-field>
              </v-col>
              <v-col  sm="6" md="4">
                <v-text-field v-model="editedItem.postcode" label="Postcode"></v-text-field>
              </v-col>
              <v-col sm="8" md="8">
                <v-text-field v-model="editedItem.gemeente" label="Gemeente"></v-text-field>
              </v-col>
              <v-col sm="6" md="6">
                <v-select
                  v-model="editedItem.school_type_id"
                  :items="schooltypes"
                  item-text="naam"
                  item-value="id"
                  item-key="id"
                  label="Type"
                ></v-select>
              </v-col>
              <v-col cols="12" sm="6" md="6">
                <v-text-field v-model="editedItem.logo_filename" label="Logo"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col sm="6" md="6">
                <v-checkbox v-model="editedItem.isActive" label="Zichtbaar"></v-checkbox>
              </v-col>
              <v-col sm="6" md="6">
                <v-checkbox  v-model="editedItem.useForCalculations" label="Telt mee"></v-checkbox>
              </v-col>
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="save">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <v-snackbar v-model="snackbar" bottom :color="snack_color" :timeout="snack_timeout">
      {{ snack_text }}
      <v-btn dark text @click="snackbar = false">Close</v-btn>
    </v-snackbar>
    <v-overlay :value="overlay">
      Bezig met herberekenen...
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      message: "Some Message",
      scholen: [],
      schooltypes: [],
      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        name: "",
        schooltype: "",
        adres: "",
        abbreviation: "",
        logo_filename: "",
        postcode: -1,
        useForCalculations: 1,
        cbd: 1, //can be deleted
        isActive: 1
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,
      overlay : false,
      help : false
    };
  },

  methods: {
    imgUrl: function(item) {
      return (
        "http://www.skbl.be/joomla/images/logo/logo-scholen/" +
        item.logo_filename
      );
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
    deleteItem(item) {
      if (confirm("School echt verwijderen?")) {
        var app = this;
        var index = this.scholen.indexOf(item);
        axios
          .delete("api/v1/school/" + item.id)
          .then(function(resp) {
            app.scholen.splice(index, 1);
            app.successSnack("School verwijderd");
          })
          .catch(function(resp) {
            app.failSnack("Verwijderen mislukt");
          });
      }
    },
    toggleVisibility(item) {
      var app = this;
      //first copy to editItem, which we will send to the server for processing
      //and we only update the model when server successfully processes data
      this.editedItem = Object.assign({}, item);
      this.editedItem.isActive = (this.editedItem.isActive==1?0:1);
      this.editedIndex = this.scholen.indexOf(item);
      axios
        .patch("api/v1/school/" + this.editedItem.id, this.editedItem)
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });
          Object.assign(app.scholen[app.editedIndex], resp.data);
          app.successSnack("Wijzigingen opgeslagen");
        })
        .catch(function(resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
    },
    toggleNocount(item) {
      var app = this;
      //first copy to editItem, which we will send to the server for processing
      //and we only update the model when server successfully processes data
      this.editedItem = Object.assign({}, item);
      this.editedItem.useForCalculations = !this.editedItem.useForCalculations;
      this.editedIndex = this.scholen.indexOf(item);
      axios
        .patch("api/v1/school/" + this.editedItem.id, this.editedItem)
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });
          Object.assign(app.scholen[app.editedIndex], resp.data);
          app.successSnack("Wijzigingen opgeslagen");
          app.triggerRecalculateForSchool(item);
        })
        .catch(function(resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
    },
    triggerRecalculateForSchool(item){
      let app = this;
      app.overlay = true;
      axios
        .patch("api/v1/taddCalculator/school/" + item.id,item)
        .then(function(resp) {
          app.overlay = false;
          app.successSnack("Dagen herberekend");
        })
        .catch(function(resp) {
          app.overlay = false;
          console.log(resp);
          app.failSnack("Fout bij herberekenen van de dagen");
        });
    },
    save() {
      var app = this;
      if (this.editedIndex > -1) {
        axios
          .patch("api/v1/school/" + this.editedItem.id, this.editedItem)
          .then(function(resp) {
            Object.assign(app.scholen[app.editedIndex], resp.data);
            app.successSnack("Wijzigingen opgeslagen");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij opslaan wijzigingen");
          });
      } else {
        axios
          .post("api/v1/school", this.editedItem)
          .then(function(resp) {
            app.scholen.push(resp.data);
            app.successSnack("School toegevoegd");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij aanmaken school");
          });
      }
      this.close();
    },
    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      }, 300);
    }
  },

  computed: {
    formTitle() {
      return this.editedIndex === -1
        ? "Nieuwe school toevoegen"
        : "Bewerk schoolgegevens";
    },
    aantalRijenPerPagina: function(){
      return this.$root.settings.aantalRijenPerLijst.value;
    },
  },

  created() {
    var app = this;
    axios
      .get("api/v1/school")
      .then(function(resp) {
        app.scholen = resp.data.scholen;
        app.schooltypes = resp.data.schooltypes;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load schools");
      });

    this.headers = [
      { text: "", align: "center", value: "logo", width: "30px" },
      { text: "Naam", align: "left", value: "name" },
      { text: "Afkorting", align: "left", value: "abbreviation" },
      { text: "", align: "center", value: "edit", width: "16px" },
      { text: "", align: "center", value: "delete", width: "16px" },
      { text: "", align: "center", value: "invis", width: "16px" },
      { text: "", align: "center", value: "nocount", width: "16px" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component School.vue created.");
  }
};
</script>
