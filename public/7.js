(window.webpackJsonp=window.webpackJsonp||[]).push([[7],{32:function(e,t,i){"use strict";i.r(t);var s={data:function(){return{message:"Some Message",users:[],headers:[],fab:!1,dialog:!1,editedItem:null,defaultItem:{name:"",password:null,confirm:null,isadmin:!1,isactive:!1},editedIndex:-1,snackbar:!1,snack_text:"",snack_color:"",snack_timeout:2e3,passRules:[this.passLength],confirmRules:[this.comparePassFields],show1:!1}},methods:{comparePassFields:function(e){return this.editedItem.password==this.editedItem.confirm||"wachtwoorden komen niet overeen"},passLength:function(e){return!this.editedItem||!this.editedItem.password||(null==this.editedItem.password||(this.editedItem.password.length>=8||"wachtwoord moet minstens 8 tekens lang zijn"))},successSnack:function(e){this.snack_text=e,this.snack_color="success",this.snackbar=!0},failSnack:function(e){this.snack_text=e,this.snack_color="error",this.snackbar=!0},editItem:function(e){this.editedIndex=this.users.indexOf(e),this.editedItem=Object.assign({},e),this.dialog=!0},deleteItem:function(e){if(confirm("Gebruiker echt verwijderen?")){var t=this,i=this.users.indexOf(e);axios.delete("/api/v1/user/"+e.id).then(function(e){t.users.splice(i,1),t.successSnack("Gebruiker verwijderd")}).catch(function(e){t.failSnack("Verwijderen mislukt")})}},save:function(){var e=this;this.editedIndex>-1?axios.patch("/api/v1/user/"+this.editedItem.id,this.editedItem).then(function(t){Object.assign(e.users[e.editedIndex],t.data),e.successSnack("Wijzigingen opgeslagen")}).catch(function(t){console.log(t),e.failSnack("Fout bij opslaan wijzigingen")}):axios.post("/api/v1/user",this.editedItem).then(function(t){e.users.push(t.data),e.successSnack("Gebruiker toegevoegd")}).catch(function(t){console.log(t),e.failSnack("Fout bij aanmaken user")}),this.close()},close:function(){var e=this;this.dialog=!1,setTimeout(function(){e.editedItem=Object.assign({},e.defaultItem),e.editedIndex=-1},300)}},computed:{formTitle:function(){return-1===this.editedIndex?"Nieuwe gebruiker toevoegen":"Bewerk gebruiker"}},created:function(){var e=this;axios.get("/api/v1/user").then(function(t){e.users=t.data}).catch(function(e){console.log(e),alert("Could not load users")}),this.headers=[{text:"Naam",align:"left",value:"name"},{text:"email",align:"left",value:"email"},{text:"admin",align:"left",value:"isadmin"},{text:"active",align:"left",value:"isactive"},{text:"",align:"center",value:"action"}],this.editedItem=Object.assign({},this.defaultItem),console.log("Component Function.vue created.")}},a=i(2),n=Object(a.a)(s,function(){var e=this,t=e.$createElement,i=e._self._c||t;return i("div",[i("v-card",{attrs:{width:"100%"}},[i("v-container",{attrs:{fluid:""}},[i("v-card-title",[e._v("\n        Gebruikers overzicht\n        "),i("v-btn",{attrs:{fab:"",right:"",absolute:""},on:{click:function(t){e.dialog=!e.dialog}}},[i("v-icon",[e._v("add")])],1)],1),e._v(" "),i("v-card-text",[i("v-data-table",{attrs:{items:e.users,headers:e.headers},scopedSlots:e._u([{key:"item.action",fn:function(t){var s=t.item;return[i("v-icon",{staticClass:"mr-2",attrs:{small:""},on:{click:function(t){return e.editItem(s)}}},[e._v("edit")]),e._v(" "),i("v-icon",{attrs:{small:""},on:{click:function(t){return e.deleteItem(s)}}},[e._v("delete")])]}},{key:"item.isadmin",fn:function(t){return[t.item.isadmin?i("v-icon",{staticClass:"mr-2",attrs:{color:"success"}},[e._v("check")]):e._e()]}},{key:"item.isactive",fn:function(t){return[t.item.isactive?i("v-icon",{staticClass:"mr-2",attrs:{color:"success"}},[e._v("check")]):e._e()]}}])})],1)],1)],1),e._v(" "),i("v-dialog",{attrs:{"max-width":"500px"},model:{value:e.dialog,callback:function(t){e.dialog=t},expression:"dialog"}},[i("v-card",[i("v-card-title",[i("span",{staticClass:"headline"},[e._v(e._s(e.formTitle))])]),e._v(" "),i("v-card-text",[i("v-container",[i("v-row",[i("v-col",{attrs:{cols:"12",sm:"8",md:"8"}},[i("v-text-field",{attrs:{label:"Naam"},model:{value:e.editedItem.name,callback:function(t){e.$set(e.editedItem,"name",t)},expression:"editedItem.name"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",sm:"8",md:"8"}},[i("v-text-field",{attrs:{label:"email"},model:{value:e.editedItem.email,callback:function(t){e.$set(e.editedItem,"email",t)},expression:"editedItem.email"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",sm:"12",md:"12"}},[i("v-text-field",{attrs:{type:e.show1?"text":"password",label:"nieuw wachtwoord",rules:e.passRules,"append-icon":e.show1?"visibility":"visibility_off",counter:""},on:{"click:append":function(t){e.show1=!e.show1}},model:{value:e.editedItem.password,callback:function(t){e.$set(e.editedItem,"password",t)},expression:"editedItem.password"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",sm:"12",md:"12"}},[i("v-text-field",{attrs:{type:e.show1?"text":"password",label:"bevestig",rules:e.confirmRules,"append-icon":e.show1?"visibility":"visibility_off"},on:{"click:append":function(t){e.show1=!e.show1}},model:{value:e.editedItem.confirm,callback:function(t){e.$set(e.editedItem,"confirm",t)},expression:"editedItem.confirm"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",sm:"12",md:"12"}},[i("v-checkbox",{attrs:{label:"Beheerder"},model:{value:e.editedItem.isadmin,callback:function(t){e.$set(e.editedItem,"isadmin",t)},expression:"editedItem.isadmin"}})],1),e._v(" "),i("v-col",{attrs:{cols:"12",sm:"12",md:"12"}},[i("v-checkbox",{attrs:{label:"Actief"},model:{value:e.editedItem.isactive,callback:function(t){e.$set(e.editedItem,"isactive",t)},expression:"editedItem.isactive"}})],1)],1)],1)],1),e._v(" "),i("v-card-actions",[i("div",{staticClass:"flex-grow-1"}),e._v(" "),i("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:e.close}},[e._v("Annuleren")]),e._v(" "),i("v-btn",{attrs:{color:"blue darken-1",text:""},on:{click:e.save}},[e._v("Opslaan")])],1)],1)],1),e._v(" "),i("v-snackbar",{attrs:{bottom:"",color:e.snack_color,timeout:e.snack_timeout},model:{value:e.snackbar,callback:function(t){e.snackbar=t},expression:"snackbar"}},[e._v("\n    "+e._s(e.snack_text)+"\n    "),i("v-btn",{attrs:{dark:"",text:""},on:{click:function(t){e.snackbar=!1}}},[e._v("Close")])],1)],1)},[],!1,null,null,null);t.default=n.exports}}]);