<template>
    <v-row>
        <v-col md="6">
            <v-card>
                <v-card-title>Archief</v-card-title>
                <v-card-text>
                    Met deze knop kan je personeelsleden onzichtbaar zetten in
                    het ingavescherm en het dashbord wanneer ze aan een van deze
                    criteria voldoen: het einde van de laatste aanstelling meer
                    dan 5 jaar geleden is
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="archiveer">Archiveer</v-btn>
                </v-card-actions>
                <v-card-text>
                    Maak alle personeelsleden weer zichtbaar:
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="allvisible"
                        >Alle personeelsleden zichtbaar</v-btn
                    >
                </v-card-actions>
            </v-card>
            <v-card>
                <v-card-title>Onderhoud</v-card-title>
                <v-card-text>
                    Elk schooljaar moet je minstens 1 maal 
                    onderstaande knop gebruiken om voor alle 
                    personeelsleden de berekening te laten uitvoeren 
                    van het aantal dagen dit schooljaar. Dit kan 
                    om op het even welk moment in het schooljaar gebeuren.
                </v-card-text>
                <v-card-actions>
                    <v-btn @click="startberekening">Start berekening</v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
export default {
    methods: {
        archiveer: function() {
            console.log("archive old or already full-TADD employees");
            axios.post("../api/v1/employee/archive/1");
        },
        allvisible: function() {
            console.log("toggle visibility for all employees : set to true");
            axios.post("../api/v1/employee/toggleAllVisible/true");
        },
        startberekening : function(){
            console.log('trigger uitvoeren van berekening');
            axios.post("../api/v1/employee/startcalculation/update");
        }
    }
};
</script>
