<template>
    <div class="col-12" id="DashboardView">
        <div class="col-12 Filter">
            <img src="@/assets/TheLogo.png">
            <div class="col-12" id="AllCards">
                <div class="col-6 Card" v-if="this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 1"
                    @click="this.$store.state['CurrentComponent'] = 'TasksView'">
                    <h1 class="col-12">طلبات التسعير</h1>
                    <span class="col-12">{{ this.$store.state['Price_Tasks'].length }}</span>
                </div>
                <div class="col-6 Card" v-if="this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 1"
                    @click="this.$store.state['CurrentComponent'] = 'ProductionView'">
                    <h1 class="col-12"> مهام تحت التشغيل</h1>
                    <span class="col-12">{{ this.$store.state['Production_Tasks'].length }}</span>
                </div>
                <div class="col-6 Card" v-if="this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 2"
                    @click="this.$store.state['CurrentComponent'] = 'DeliveryView'">
                    <h1 class="col-12">مشاوير التوصيل</h1>
                    <span class="col-12">{{ this.$store.state['OpenDelivery'].length }}</span>
                </div>
                <div class="col-6 Card" v-if="this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 2"
                    @click="this.$store.state['CurrentComponent'] = 'DeliveryView'">
                    <h1 class="col-12">طلبات الشراء</h1>
                    <span class="col-12">{{ this.$store.state['OpenDelivery'].length }}</span>
                </div>
            </div>
        </div>
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
#DashboardView {
    display: flex;
    width: 100%;
    background-position: 50%;
    background-size: cover;
    background-image: url('@/assets/Dashboard.jpg');

    .Filter {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: flex-start;
        justify-content: center;
        padding-top: 5rem;
        background-color: #0000004d;

        img {
            height: 9rem;
        }
    }

    #AllCards {
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        justify-content: center;
        align-items: center;
        padding-top: 6rem;

        .Card {
            color: white;
            background-color: #ffffff1f;
            padding: 1rem;
            border: 1px solid;
            margin-bottom: 2rem;
            font-size: 2rem;
            font-weight: 600;
            text-align: center;
            border-radius: 0.5rem;

            h1 {
                font-size: 1.5rem;
            }
        }
    }


}
</style>
  
  
  