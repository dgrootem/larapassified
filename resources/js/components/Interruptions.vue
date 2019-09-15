<template>
  
</template>

<script>
export default {
  props: {
    interrruptions: {},
    selected_employee : number,
  },
  data: function() {
    return {
      
      interruptionDialog : false,
      editedIndex: -1,
      editedItem: {},
    };
  },
  computed :{
    formTitleInterruption() {
      return this.editedIndex === -1
        ? "Nieuwe onderbreking"
        : "Bewerk onderbrekeing";
    },
    formattedBegin: {
      get() {
        if (this.editedItem && this.editedItem.beginDate)
          return this.editedItem.beginDate.format("DD-MM-YYYY");
        else return "";
      },
      set(val) {
        this.editedItem.beginDate = moment(val, "DD-MM-YYYY");
      }
    },
    formattedEnd: {
      get() {
        if (this.editedItem && this.editedItem.endDate)
          return this.editedItem.endDate.format("DD-MM-YYYY");
        else return "";
      },
      set(val) {
        this.editedItem.endDate = moment(val, "DD-MM-YYYY");
      }
    }
  },
  methods: {
    emitFail() {},
    emitSuccess() {},
    
    addInterruption() {},
    saveInterruption() {
      var app = this;
      axios
        .post("/api/v1/interruption", this.editedItem)
        .then(function(resp) {
          //app.$router.push({ path: "/employees" });
          app.functiondata.push(resp.data);
          app.successSnack("Onderbreking toegevoegd");
        })
        .catch(function(resp) {
          console.log(resp);
          app.failSnack("Fout bij aanmaken onderbreking");
        });
    },
    closeInterruption() {},
    deleteInterruption(){},
  }
};
</script>

<style>
</style>