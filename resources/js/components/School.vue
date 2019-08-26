<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-card-title>
          Scholen overzicht
          <v-btn fab right absolute @click="dialog = !dialog">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-data-table :items="scholen" :headers="headers">
            <template v-slot:item.logo="{ item }">
              <img
                v-if="(item.logo_filename != 'nologo')"
                :src="imgUrl(item)"
                height="25px"
                width="25px"
              />
            </template>
            <template v-slot:item.action="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
              <v-icon small @click="deleteItem(item)">delete</v-icon>
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
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="editedItem.abbreviation" label="Afkorting"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="8">
                <v-text-field v-model="editedItem.name" label="Naam"></v-text-field>
              </v-col>

              <v-col cols="12" sm="12" md="12">
                <v-text-field v-model="editedItem.adres" label="Adres"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="4">
                <v-text-field v-model="editedItem.postcode" label="Postcode"></v-text-field>
              </v-col>
              <v-col cols="12" sm="8" md="8">
                <v-text-field v-model="editedItem.gemeente" label="Gemeente"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="6">
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
        postcode: -1
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000
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
    deleteItem(item){
      if (confirm("School echt verwijderen?")) {
                    var app = this;
                    var index = this.scholen.indexOf(item);
                    axios.delete('/api/v1/school/' + item.id)
                        .then(function (resp) {
                            app.scholen.splice(index, 1);
                            app.successSnack("School verwijderd");
                        })
                        .catch(function (resp) {
                            app.failSnack("Verwijderen mislukt");
                        });
                }
    },
    save() {
      var app = this;
      if (this.editedIndex > -1) {
        Object.assign(this.scholen[this.editedIndex], this.editedItem);
        axios
          .patch("/api/v1/school/" + this.editedItem.id, this.editedItem)
          .then(function(resp) {
            //app.$router.push({ path: "/scholen" });
            app.successSnack("Wijzigingen opgeslagen");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij opslaan wijzigingen");
          });
      } else {
        this.scholen.push(this.editedItem);
        axios
          .post("/api/v1/school", this.editedItem)
          .then(function(resp) {
            //app.$router.push({ path: "/scholen" });
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
    }
  },

  created() {
    var app = this;
    axios
      .get("/api/v1/school")
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
      { text: "", align: "center", value: "action" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component School.vue created.");
  }
};
</script>

<style>
</style>