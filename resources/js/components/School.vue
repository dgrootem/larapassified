<template>
  <v-card width="100%">
    <v-card-title>
      Scholen
    </v-card-title>
    <v-card-text>
      <v-data-table :items="scholen"></v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
data: {
  scholen : [
    { name : "Sancta Maria", afkorting : "SMB"},
    { name : "Mater Dei", afkorting : "MD"},
    { name : "Windekind", afkorting : "Wind"},
    { name : "Groenveld", afkorting : "DB"},
    ]
}
}
</script>

<style>

</style>