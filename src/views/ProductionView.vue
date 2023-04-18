<template>
    <div class="col-12" id="ProductionView">
        <div class="col-12" v-if="this.$store.state['Production_Tasks'].length <= 0" id="NoTasks">
            <img src="@/assets/NoTasks.png">
            <h1>لا توجد مشاريع مطلوب العمل عليها</h1>
        </div>
        <div class="col-12" v-else>
            <h1 class="col-12 TabHeader">المشاريع المفتوحة</h1>
            <table class="col-12 table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>-</th>
                        <th>اسم المهمة</th>
                        <th>تفاصيل المهمة</th>
                        <th>تاريخ بداية المهمة</th>
                        <th>تاريخ التسليم</th>
                        <th>المرحلة الحالية</th>
                        <th>انهاء</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="Task, index in this.$store.state['Production_Tasks']" :key="Task">
                        <td>{{ index + 1 }}</td>
                        <td>{{ Task['Task_Name'] }}</td>
                        <td>
                            <pre>{{
                                Task['Task_Details'] }}</pre>
                        </td>
                        <td>{{ Task['Starting_Date'] }}</td>
                        <td>{{ Task['Deadline_Date'] }}</td>
                        <td>
                            <select :value="Task['Task_Stage_ID']" @change="this.UpdateTaskStage($event)">
                                <option :value="Stage['Stage_ID']" v-for="Stage in Task['TaskStages']" :key="Stage">{{
                                    Stage['Arabic_Name'] }}</option>
                            </select>
                        </td>
                        <td>
                            <btn class="btn btn-danger" @click="this.EndTask()">انهاء المهمة</btn>
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
    name: 'ProductionView',
    components: {},
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
            TaskStages: [""],
        };
    },
    created() {
        let main = this;
        console.log(this.$store.state['Production_Tasks']);
    },
    methods: {
        EndTask() {
            this.$swal.fire({
                title: 'هل تريد انهاء المهمة',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم لنقم بذلك',
                cancelButtonText: 'ليس الأن'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal.fire(
                        'تم انهاء المهمة بنجاح',
                    )
                }
            })
        },
        UpdateTaskStage(event) {
            this.$swal.fire({
                title: 'تم تعديل حالة المهمة',
                icon: 'success',
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
#ProductionView {
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