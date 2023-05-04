<template>
    <div id="WorkingArea" @click="this.$store.state['SideMenuIndex'] = 0">
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
import DoneDeliveryView from '@/views/DoneDeliveryView.vue';



export default {
    name: 'WorkingArea',
    components: { FinalView, TasksView, DashboardView, MaterialView, ProductionView, DoneView, DeliveryView, DoneDeliveryView },
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
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
#WorkingArea {
    width: 80%;
    height: auto;
    flex-grow: 1;
    display: flex;
    overflow-y: auto;
    overflow-x: auto;

    @media screen and (max-width: 767px) {
        width: 100%;
        flex-grow: none;
    }
}
</style>
  
  
  