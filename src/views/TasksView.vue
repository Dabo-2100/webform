<template>
    <div class="col-12" id="TasksView">
        <div class="col-12" v-if="this.$store.state['Price_Tasks'].length <= 0" id="NoTasks">
            <img src="@/assets/NoTasks.png">
            <h1>لا يوجد مهمات تسعير جديدة مطلوبة منك</h1>
        </div>
        <div class="col-12" v-else>
            <h1 class="col-12 TabHeader">مهام التسعير</h1>
            <table class="col-12 table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>-</th>
                        <th>اسم المهمة</th>
                        <th>تفاصيل المهمة</th>
                        <th>تاريخ المهمة</th>
                        <th>عدد القطع</th>
                        <th>التسعير</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="PriceTask, index in this.$store.state['Price_Tasks']" :key="PriceTask">
                        <td>{{ index + 1 }}</td>
                        <td>{{ PriceTask['Name'] }}</td>
                        <td>
                            <pre>{{
                                PriceTask['Requirement_Details'] }}</pre>
                        </td>
                        <td>{{ PriceTask['Created_Date'] }}</td>
                        <td>{{ PriceTask['Quantity'] }}</td>
                        <td><button class="btn btn-success" @click="this.OpenTask(PriceTask['Task_ID'])">التسعير</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
  
<script>
// @ is an alias to /src
import axios from 'axios';
export default {
    name: 'TasksView',
    components: {},
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
        };
    },
    created() {
        let main = this;
    },
    methods: {
        OpenTask(Task_ID) {
            let main = this;
            main.$store.state['TheTaskID'] = Task_ID;
            axios.post(this.Api_Url, {
                api_name: "SaveTaskId",
                the_code: Task_ID
            }).then(function (res) {
                main.$store.state['CurrentComponent'] = 'FinalView';
                main.$store.state['RunApi'] = 1;
            });
        }
    },
    watch: {

    },
    computed: {

    },
}
</script>
  
<style lang="scss" scoped>
#TasksView {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;
    align-items: center;

    #NoTasks {
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        align-items: center;
        height: 100vh;
        justify-content: center;

        h1 {
            font-size: 2rem;
        }

        img {
            width: 10rem;
            margin-bottom: 1rem;
        }
    }

    .TabHeader {
        font-size: 1.4rem;
        padding: 1rem;
    }

    table {
        text-align: center;
        vertical-align: middle;

        pre {
            margin: 0;
            padding: 0;
        }
    }
}
</style>