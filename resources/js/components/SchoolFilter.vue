<template>
    <v-select
        :items="scholen"
        @change="emitChange"
        item-text="abbreviation"
        item-value="id"
        label="Schoolfilter"
        v-model="selectedSchool"
    ></v-select>
</template>

<script>
export default {
    data: function() {
        return {
            scholen: [],
            selectedSchool: this.initialSelection?this.initialSelection:null
        };
    },
    props: {
        initialSelection : Number,
    },
    methods: {
        emitChange() {
            this.$emit("selectedSchoolChanged", this.selectedSchool);
        }
    },
    created() {
        let app = this;
        axios
            .get("api/v1/school")
            .then(function(resp) {
                app.scholen = resp.data.scholen;
            })
            .catch(function(resp) {
                console.log(resp);
                alert("Could not load scholen");
            });
    }
};
</script>
