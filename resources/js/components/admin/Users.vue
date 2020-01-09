<template>
  <div>
    <v-card width="100%">
      <v-container fluid>
        <v-card-title>
          Gebruikers overzicht
          <v-btn fab right absolute @click="dialog = !dialog">
            <v-icon>add</v-icon>
          </v-btn>
        </v-card-title>
        <v-card-text>
          <v-data-table :items="users" :headers="headers">
            <template v-slot:item.action="{ item }">
              <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
              <v-icon small @click="deleteItem(item)">delete</v-icon>
            </template>
            <template v-slot:item.isadmin="{ item }">
              <v-icon color="success"  class="mr-2" v-if="item.isadmin">check</v-icon>
            </template>
            <template v-slot:item.isactive="{ item }">
              <v-icon color="success"  class="mr-2" v-if="item.isactive">check</v-icon>
            </template>
            <template v-slot:item.readonly="{ item }">
              <v-icon color="success"  class="mr-2" v-if="item.readonly">check</v-icon>
            </template>
          </v-data-table>
        </v-card-text>
      </v-container>
    </v-card>
    <v-dialog v-model="dialog" max-width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">{{ formTitle }}</span>
        </v-card-title>

        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12" sm="8" md="4">
                <v-text-field v-model="editedItem.name" label="Naam"></v-text-field>
              </v-col>
              <v-col cols="12" sm="8" md="8">
                <v-text-field v-model="editedItem.email" label="email"></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="6">
                <v-text-field
                  v-model="editedItem.password"
                  :type="show1 ? 'text' : 'password'"
                  label="nieuw wachtwoord"
                  :rules="passRules"
                  :append-icon="show1 ? 'visibility' : 'visibility_off'"
                  @click:append="show1 = !show1"
                  counter
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="12" md="6">
                <v-text-field
                  v-model="editedItem.confirm"
                  :type="show1 ? 'text' : 'password'"
                  label="bevestig"
                  :rules="confirmRules"
                  :append-icon="show1 ? 'visibility' : 'visibility_off'"
                  @click:append="show1 = !show1"
                ></v-text-field>
              </v-col>
            </v-row><v-row dense>
              <v-col cols="12" sm="12" md="12">
                Beheerders kunnen gebruikers aanmaken en instellingen aanpassen.
                <v-checkbox v-model="editedItem.isadmin" label="Beheerder"></v-checkbox>
              </v-col>
              <v-col cols="12" sm="12" md="12">
                Enkel actieve gebuikers kunnen inloggen in het systeem.
                <v-checkbox v-model="editedItem.isactive" label="Actief"></v-checkbox>
              </v-col>
              <v-col cols="12" sm="12" md="12">
                Alleen-lezen gebruikers kunnen geen wijzigingen aanbrengen.
                <v-checkbox v-model="editedItem.readonly" label="Alleen-lezen"></v-checkbox>
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
      users: [],

      headers: [],
      fab: false,
      dialog: false,
      editedItem: null,
      defaultItem: {
        name: "",
        password: null,
        confirm: null,
        isadmin: false,
        isactive: true,
        readonly : true
      },
      editedIndex: -1,
      snackbar: false,
      snack_text: "",
      snack_color: "",
      snack_timeout: 2000,
      passRules: [this.passLength],
      confirmRules: [this.comparePassFields],
      show1 : false,

      mybaseurl : 'api/v1/'
    };
  },

  methods: {
    comparePassFields(field1) {
      if (this.editedItem.password == this.editedItem.confirm) return true;
      else return "wachtwoorden komen niet overeen";
    },
    passLength(field1) {
      if ((this.editedItem) && (this.editedItem.password)){
          if (this.editedItem.password == undefined) return true;
        if (this.editedItem.password.length >= 8)
            return true;
        else return "wachtwoord moet minstens 8 tekens lang zijn";
      }
      return true;
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
      this.editedIndex = this.users.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      if (confirm("Gebruiker echt verwijderen?")) {
        var app = this;
        var index = this.users.indexOf(item);
        axios
          .delete(this.mybaseurl+"user/" + item.id)
          .then(function(resp) {
            app.users.splice(index, 1);
            app.successSnack("Gebruiker verwijderd");
          })
          .catch(function(resp) {
            app.failSnack("Verwijderen mislukt");
          });
      }
    },
    save() {
      var app = this;
      if (this.editedIndex > -1) {
        
        axios
          .patch(this.mybaseurl+"user/" + this.editedItem.id, this.editedItem)
          .then(function(resp) {
            /*Vue.set(
                app.users,
                app.editedIndex,
                Object.assign({}, resp.data)
              );*/
            Object.assign(app.users[app.editedIndex], resp.data[0]);
            app.successSnack("Wijzigingen opgeslagen");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij opslaan wijzigingen");
          });
      } else {
        axios
          .post(this.mybaseurl+"user", this.editedItem)
          .then(function(resp) {
            app.users.push(resp.data[0]);
            app.successSnack("Gebruiker toegevoegd");
          })
          .catch(function(resp) {
            console.log(resp);
            app.failSnack("Fout bij aanmaken user");
          })
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
        ? "Nieuwe gebruiker toevoegen"
        : "Bewerk gebruiker";
    }
  },

  created() {
    var app = this;
    axios
      .get(this.mybaseurl+"user")
      .then(function(resp) {
        app.users = resp.data;
      })
      .catch(function(resp) {
        console.log(resp);
        alert("Could not load users");
      });

    this.headers = [
      { text: "Naam", align: "left", value: "name" },
      { text: "email", align: "left", value: "email" },
      { text: "Beheerder", align: "left", value: "isadmin" },
      { text: "Actief", align: "left", value: "isactive" },
      { text: "Alleen-lezen", align: "left", value: "readonly" },
      { text: "", align: "center", value: "action" }
    ];
    this.editedItem = Object.assign({}, this.defaultItem);
    console.log("Component Function.vue created.");
  }
};
</script>
