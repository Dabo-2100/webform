<template>
    <div class="col-12" id="DashboardView">
        <div class="col-12 Filter">
            <img src="@/assets/TheLogo.png">
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
}
</style>
  
  
  