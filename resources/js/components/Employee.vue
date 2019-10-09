<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-card-title>
          Personeelsleden
          <div class="flex-grow-1"></div>
          <v-text-field
        v-model="search"
        append-icon="search"
        label="Zoeken"
        single-line
        hide-details
      ></v-text-field>
      <div class="flex-grow-1"></div>
          <v-btn fab right absolute @click="dialog = !dialog">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          
          <v-data-table :items="employees" :headers="headers" :search="search">
            <template v-slot:item.birthDate="{ item }">{{ formatDate(item.birthDate) }}</template>
            <template v-slot:item.action="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
              <v-icon small class="mr-2" @click="deleteItem(item)">delete</v-icon>
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
      <!-- <template v-slot:activator="{ on }">
            <v-btn color="primary" dark class="mb-2" v-on="on">Nieuwe personeelslid toevoegen</v-btn>
      </template>-->
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="6" md="7">
                <v-text-field v-model="editedItem.lastName" label="Achternaam"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="5">
                <v-text-field v-model="editedItem.firstName" label="Voornaam"></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="7">
                <v-text-field
                  v-model="editedItem.registrationNumber"
                  @blur="calcBdate"
                  label="Stamboeknummer"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6" md="5">
                <v-text-field
                  v-model="editedItem.birthDate"
                  :disabled="editedItem.registrationNumber != ''"
                  label="Geboortedatum"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12" sm="12" md="6">
                <v-text-field v-model="editedItem.startwaarde" label="Startwaarde" type="number" min="0"></v-text-field>
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
import moment from "moment";

export default {
  data: function() {
    return {
      message: "Some Message",
      employees: [],
      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        firstName: "",
        lastName: "",
        registrationNumber: "",
        birthDate: null,
        isActive: true
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,
      search : ""
      // registrationNumberProxy : null
    };
  },

  methods: {
    calcBdate: function() {
      
      if (this.editedItem.registrationNumber.length == 11) {
        if (!isNaN(this.editedItem.registrationNumber)) {
          //only do something when it is a number
          var bd = this.editedItem.registrationNumber.substring(1, 7);
          var year = bd.substring(0, 2);
          if (parseInt(year) > 40) year = "19"+year;
          else year = "20"+year;
          this.editedItem.birthDate =
            moment(bd.substring(4, 6) + "-" + bd.substring(2, 4) + "-" + year,'DD-MM-YYYY');
        }
      }
    },
    formatDate: function(value) {
      if (value) {
        return moment(String(value)).format("MM-DD-YYYY");
      }
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
      this.editedIndex = this.employees.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      if (confirm("Personeelslid echt verwijderen?")) {
        var app = this;
        var index = this.employees.indexOf(item);
        axios
          .delete("/api/v1/employee/" + item.id)
          .then(function(resp) {
            app.employees.splice(index, 1);
            app.successSnack("Personeelslid verwijderd");
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
      this.editedItem.isActive = !this.editedItem.isActive;
      this.editedIndex = this.employees.indexOf(item);
      axios
        .patch("/api/v1/employee/" + this.editedItem.id, this.editedItem)
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });
          Object.assign(app.employees[app.editedIndex], resp.data);
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
          .patch("/api/v1/employee/" + this.editedItem.id, this.editedItem)
          .then(function(resp) {
            Object.assign(app.employees[app.editedIndex], resp.data);
            app.successSnack("Wijzigingen opgeslagen");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij opslaan wijzigingen");
          });
      } else {
        axios
          .post("/api/v1/employee", this.editedItem)
          .then(function(resp) {
            app.employees.push(resp.data);
            app.successSnack("Personeelslid toegevoegd");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij aanmaken personeelslid");
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
    formTitle: function() {
      return this.editedIndex === -1
        ? "Nieuw personeelslid toevoegen"
        : "Bewerk gegevens";
    }
    
  },

  created() {
    var app = this;
    axios
      .get("/api/v1/employee")
      .then(function(resp) {
        app.employees = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load personeelsleden");
      });

    this.headers = [
      { text: "Achternaam", align: "left", value: "lastName" },
      { text: "Voornaam", align: "left", value: "firstName" },
      { text: "Stamboeknummer", align: "left", value: "registrationNumber" },
      { text: "Geboortedatum", align: "left", value: "birthDate" },
      { text: "Startwaarde", align: "right", value: "startwaarde" },
      { text: "", align: "center", value: "action" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component personeelslid.vue created.");
  }
};
</script>

<style>
</style>