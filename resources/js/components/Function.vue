<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-card-title>
          Ambten overzicht
          <v-btn fab right absolute @click="dialog = !dialog">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-data-table :items="ambten" :headers="headers" :items-per-page="aantalRijenPerPagina">
            <template v-slot:item.action="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
              <v-icon small @click="deleteItem(item)">delete</v-icon>
              <v-icon
                small
                class="mr-2"
                @click="toggleVisibility(item)"
              >{{ item.isActive ? 'visibility' : 'visibility_off' }}</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-container>
    </v-card>
    <v-dialog v-model="dialog" max-width="500px">
     
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              
              <v-col cols="12" sm="6" md="8">
                <v-text-field v-model="editedItem.name" label="Naam"></v-text-field>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <v-text-field v-model="editedItem.denominator" label="Noemer"></v-text-field>
              </v-col>              
            </v-row>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <div class="flex-grow-1"></div>
          <v-btn color="blue darken-1" text @click="close">Annuleren</v-btn>
          <v-btn color="blue darken-1" text @click="save">Opslaan</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
      ambten: [],
      
      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        name: "",
        denominator : ''
      },
      editedIndex: -1,
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
    editItem(item) {
      this.editedIndex = this.ambten.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item){
      if (confirm("Ambt echt verwijderen?")) {
                    var app = this;
                    var index = this.ambten.indexOf(item);
                    axios.delete('api/v1/ambt/' + item.id)
                        .then(function (resp) {
                            app.ambten.splice(index, 1);
                            app.successSnack("School verwijderd");
                        })
                        .catch(function (resp) {
                            app.failSnack("Verwijderen mislukt");
                        });
                }
    },
    toggleVisibility(item) {
      var app = this;
      //first copy to editItem, which we will send to the server for processing
      //and we only update the model when server successfully processes data
      this.editedItem = Object.assign({}, item);
      this.editedItem.isActive = !this.editedItem.isActive;
      this.editedIndex = this.ambten.indexOf(item);
      axios
        .patch("api/v1/ambt/" + this.editedItem.id, this.editedItem)
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });
          Object.assign(app.ambten[app.editedIndex], resp.data);
          app.successSnack("Wijzigingen opgeslagen");
        })
        .catch(function(resp) {
          console.log(resp);
          app.failSnack("Fout bij opslaan wijzigingen");
        });
    },
    save() {
      var app = this;
      if (this.editedIndex > -1) {
        axios
          .patch("api/v1/ambt/" + this.editedItem.id, this.editedItem)
          .then(function(resp) {
            Object.assign(app.ambten[app.editedIndex], resp.data);
            app.successSnack("Wijzigingen opgeslagen");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij opslaan wijzigingen");
          });
      } else {
        
        axios
          .post("api/v1/ambt", this.editedItem)
          .then(function(resp) {
            app.ambten.push(resp.data);
            app.successSnack("Ambt toegevoegd");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij aanmaken ambt");
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
        ? "Nieuw ambt toevoegen"
        : "Bewerk ambt";
    },
    aantalRijenPerPagina: function(){
      return this.$root.settings.aantalRijenPerLijst.value;
    },
  },

  created() {
    var app = this;
    axios
      .get("api/v1/ambt")  // removed slash at beginning of url for deploy
      .then(function(resp) {
        app.ambten = resp.data; 
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load ambten");
      });

    this.headers = [
      { text: "Naam", align: "left", value: "name" },
      { text: "Noemer", align: "left", value: "denominator" },
      { text: "", align: "center", value: "action" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component Function.vue created.");
  }
};
</script>