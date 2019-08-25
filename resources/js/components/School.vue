<template>
  <v-card width="100%">
    <v-card-title>Scholen overzicht</v-card-title>
    <v-card-text>
      <v-data-table :items="scholen" :headers="headers">
        <template v-slot:item.logo ="{ item }" >
          <img v-if="(item.logo_filename != 'nologo')" :src="imgUrl(item)" height="25px" width="25px">
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
    data: function() {
    return {
      message: 'Some Message',
      scholen: [],
      headers: []
    };
  },

  methods:{
    imgUrl : function (item){
      
        return 'http://www.skbl.be/joomla/images/logo/logo-scholen/'+item.logo_filename ;
      
    } ,
  },

 
  created() {
    var app = this;
            axios.get('/api/v1/school')
                .then(function (resp) {
                    app.scholen = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load schools");
                });
      this.headers = [
        { text:"",align:"center",value:"logo", width: "30px"},
        { text: "Naam", align: "left", value: "name" },
        { text: "Afkorting", align: "left", value: "abbreviation" }
        
      ];
            console.log('Component School.vue created.')
  }
};
</script>

<style>
</style>