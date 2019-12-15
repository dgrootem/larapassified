<template>
    <div>
        <v-card>
            <v-card-title>
                <span class="headline">Mijn profielgegevens</span>
            </v-card-title>
            <v-card-subtitle>
                <span class="subtitle">{{ user.name }}</span>
            </v-card-subtitle>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="12" sm="12" md="6">
                            <v-text-field
                                v-model="passchanges.oldpassword"
                                :type="show1 ? 'text' : 'password'"
                                label="oud wachtwoord"
                                :rules="passRules"
                                :append-icon="
                                    show1 ? 'visibility' : 'visibility_off'
                                "
                                @click:append="show1 = !show1"
                                counter
                            ></v-text-field>
                        </v-col> </v-row
                    ><v-row>
                        <v-col cols="12" sm="12" md="6">
                            <v-text-field
                                v-model="passchanges.password"
                                :type="show1 ? 'text' : 'password'"
                                label="nieuw wachtwoord"
                                :rules="passRules"
                                :append-icon="
                                    show1 ? 'visibility' : 'visibility_off'
                                "
                                @click:append="show1 = !show1"
                                counter
                            ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" sm="12" md="6">
                            <v-text-field
                                v-model="passchanges.confirm"
                                :type="show1 ? 'text' : 'password'"
                                label="bevestig"
                                :rules="confirmRules"
                                :append-icon="
                                    show1 ? 'visibility' : 'visibility_off'
                                "
                                @click:append="show1 = !show1"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <div class="flex-grow-1"></div>
                <v-btn color="blue darken-1" text @click="save">Opslaan</v-btn>
            </v-card-actions>
        </v-card>
        <v-snackbar
            v-model="snackbar"
            bottom
            :color="snack_color"
            :timeout="snack_timeout"
        >
            {{ snack_text }}
            <v-btn dark text @click="snackbar = false">Close</v-btn>
        </v-snackbar>
    </div>
</template>

<script>
import { stringify } from "querystring";
export default {
    data: function() {
        return {
            user: {
                name: "",
                id: -1,
                readonly: false,
                isadmin: false,
                isactive: false
            },
            snackbar: false,
            snack_text: "",
            snack_color: "",
            snack_timeout: 2000,
            passRules: [this.passLength],
            confirmRules: [this.comparePassFields],
            show1: false,

            passchanges: {
                password: "",
                confirm: "",
                oldpassword: ""
            }
        };
    },
    methods: {
        comparePassFields(field1) {
            if (this.passchanges.password == this.passchanges.confirm)
                return true;
            else return "wachtwoorden komen niet overeen";
        },
        passLength(field1) {
            if (this.passchanges && this.passchanges.password) {
                // if (this.passchanges.password == undefined) return true;
                if (this.passchanges.password.length >= 8) return true;
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
        save() {
            let app = this;
            axios
                .patch("../api/v1/user/" + this.user.id, this.passchanges)
                .then(function(resp) {
                    app.successSnack("Wijzigingen opgeslagen");
                })
                .catch(function(resp) {
                    console.log(resp);
                    app.failSnack("Fout bij opslaan wijzigingen");
                });
        }
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            console.log(JSON.stringify(to.params));
            if (to.params.user) vm.user.id = to.params.user;
            else console.log("no user parameter found...");
            let app = vm;
            axios
                .get("../api/v1/user/attributes/" + app.user.id)
                .then(function(resp) {
                    app.user = resp.data;
                    console.log;
                })
                .catch(function(resp) {
                    console.log(resp);
                    alert("Could not load user data");
                });
        });
    },
    beforeRouteUpdate(to, from, next) {
        // just use `this`
        console.log(JSON.stringify(to.params));
        if (to.params.user) this.user.id = to.params.user;
        else console.log("no user parameter found...");
        next();
    },
    created: function() {}
};
</script>
