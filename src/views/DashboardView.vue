<template>
    <div class="col-12" id="DashboardView">
        <button v-for='Selection in this.Selections' :key="Selection" class="btn btn-info"
            @click="this.$store.state['CurrentComponent'] = Selection['TabView']">{{ this.$store.state['UserLang'] == 1 ?
                Selection['ArabicName'] :
                Selection['ArabicName'] }}</button>
    </div>
</template>
  
<script>
// @ is an alias to /src
import axios from 'axios';
export default {
    name: 'DashboardView',
    components: {},
    data() {
        return {
            Selections: [
                { name: "Pricing Tasks", Icon: "fa-solid fa-money-check-dollar", ArabicName: "مهام التسعير", Index: 0, TabView: "TasksView" },
                { name: "Pricing Tasks", Icon: "fa-solid fa-box-open", ArabicName: "المهام المفتوحة", Index: this.$store.state['Production_Tasks'], TabView: "ProductionView" },
                { name: "Pricing Tasks", Icon: "fa-solid fa-list-check", ArabicName: "المهام المنتهية", Index: this.$store.state['DoneTasks'], TabView: "DoneView" },
                { name: "Pricing Tasks", Icon: "fa-solid fa-dollar-sign", ArabicName: "اسعار الخامات", Index: false, TabView: "MaterialView" },
            ],
        };
    },
    created() {
        let main = this;
    },
    methods: {
    },
    watch: {

    },
    computed: {

    },
}
</script>
  
<style lang="scss" scoped>
#HomeView {
    display: flex;
}
</style>
  
  
  