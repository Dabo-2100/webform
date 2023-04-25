<template>
    <div id="WorkingArea">
        <component :is="this.$store.state['CurrentComponent']"></component>
    </div>
</template>
  
<script>
// @ is an alias to /src
import axios from 'axios';
import FinalView from '@/views/FinalView.vue';
import TasksView from '@/views/TasksView.vue';
import ProductionView from '@/views/ProductionView.vue';
import DashboardView from '@/views/DashboardView.vue';
import MaterialView from '@/views/MaterialView.vue';
import DoneView from '@/views/DoneView.vue';
import DeliveryView from '@/views/DeliveryView.vue';


export default {
    name: 'WorkingArea',
    components: { FinalView, TasksView, DashboardView, MaterialView, ProductionView, DoneView, DeliveryView },
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
        };
    },
    created() {
        let main = this;
        let TheUserID = 4432004000007195001;
        let ThePureURL = window.location.href;
        if (ThePureURL.indexOf("code") != -1) {
            let TheCode = ThePureURL.split('code=')[1].split('&')[0];
            axios.post(this.Api_Url, {
                api_name: "RefreshAccessToken",
                the_code: TheCode,
            }).then(function (res) { });
        } else {
            axios.post(this.Api_Url, {
                api_name: "CheckTheConnection",
                TheUserID: TheUserID,
            }).then(function (res) {
                if (res.data['code'] !== undefined && res.data['code'] == 'INVALID_TOKEN') {
                    window.location = 'https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id=1000.R3K41GUMKFVW5K825Z6PZ6JU1HTQ3Q&scope=ZohoCRM.modules.ALL&redirect_uri=https://webform.designido.net&prompt=consent';
                }
            });
        }
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
#WorkingArea {
    width: 80%;
    flex-grow: 1;
    display: flex;
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
}
</style>
  
  
  