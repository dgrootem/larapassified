<template>
    <v-card>
        <v-card-title>
            <span class="headline">Beoordelingen</span>
        </v-card-title>
        <v-card-text>
            <v-container>
            <v-row>
                <v-col cols="12" sm="6" md="6">
                    <v-text-field
                    v-model="rating.formattedDatum"
                    label="Datum"
                    mask="##-##-####"
                    hint="DD-MM-YYYY"
                    @blur="setDatum"
                    ></v-text-field>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-select :items="ratingTypes" v-model="rating.rating_type_id" item-text="omschrijving"
                    item-value="id"
                    item-key="id"
                    label="Type beoordeling"></v-select>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <v-select :items="ratingOmschrijvingen" v-model="rating.positief" item-text="omschrijving"
                    item-value="id"
                    item-key="id"
                    label="Resultaat"></v-select>
                </v-col>
            </v-row>
        </v-container>
        
    </v-card-text>
    <v-card-actions>
        <div class="flex-grow-1"></div>
        <v-btn color="blue darken-1" text @click="emitCancel">Annuleren</v-btn>
        <v-btn color="blue darken-1" text @click="emitSave">Opslaan</v-btn>
    </v-card-actions>
    </v-card>
</template>

<script>
import * as DateUtil from "../DateUtil";

export default {
    data: function() {
        return {
            mask: "##-##-####",
        };
    },
    props: {
        functiondata: Object,
        ratingTypes: Array,
        rating: Object
        // fulltimehours : Number,
    },
    computed: {
        ratingOmschrijvingen: function(){
            if (!!this.rating.rating_type_id){
                let a =  new Array();
                a.push({
                    id : 1,
                    omschrijving : this.ratingTypes[this.rating.rating_type_id-1].omschrijving_pos
                });
                a.push({
                    id : 0,
                    omschrijving : this.ratingTypes[this.rating.rating_type_id-1].omschrijving_neg
                }
                );
                return a;
            }
        }
    },
    methods: {
        emitSave() {
            this.$emit("saverating", this.rating);
        },
        emitCancel(){
            this.$emit("cancelrating", this.rating);
        },
        setDatum(){
            if (DateUtil.isDate(this.rating.formattedDatum)) {
                this.rating.datum = DateUtil.formatDateToDB(
                    DateUtil.parseDate(this.rating.formattedDatum + " 12:00:00")
                );
                return true;
            } else {
                this.$emit("fail","Fout datumformaat");
                return false;
            }
        }
    },
    created() {
        let app = this;
        console.log(this.ratingTypes);
    }
};
</script>