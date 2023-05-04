<template>
    <div class="col-12" id="ProductionView">
        <div class="col-12" v-if="this.$store.state['Production_Tasks'].length <= 0" id="NoTasks">
            <img src="@/assets/NoTasks.png">
            <h1>لا توجد مشاريع مطلوب العمل عليها</h1>
        </div>
        <div class="col-12" v-else>
            <h1 class="col-12 TabHeader">المشاريع المفتوحة</h1>
            <div class="col-12 Task_Card" v-for="Task, index in       this.$store.state['Production_Tasks']      "
                :key="Task">
                <table class="col-12 table table-bordered">
                    <tbody>
                        <tr>
                            <th>اسم المهمة</th>
                            <td>{{ Task['Task_Name'] }}</td>
                        </tr>
                        <tr>
                            <th>تفاصيل المهمة</th>
                            <td>
                                <p>{{
                                    Task['Task_Details'] }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>تاريخ الطلب</th>
                            <td>{{ Task['Starting_Date'] }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ التسليم</th>
                            <td>{{ Task['Deadline_Date'] }}</td>
                        </tr>
                        <tr>
                            <th>المرحلة الحالية</th>
                            <td>
                                <select :value="Task['Task_Stage_ID']"
                                    @change="this.UpdateTaskStage($event, Task['Task_ID'], index)">
                                    <option :value="Stage['Stage_ID']" v-for="Stage in Task['TaskStages']" :key="Stage">{{
                                        Stage['Arabic_Name'] }}
                                    </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>انهاء المهمة </th>
                            <td>
                                <button class="btn btn-danger" @click="this.EndTask(Task['Task_ID'])">انهاء المهمة</button>
                            </td>
                        </tr>
                        <tr>
                            <th>اضافة مصروف</th>
                            <td>
                                <button class="btn btn-info"
                                    @click="this.AddExpenseIndex = 1; this.GetTaskExpenses(Task['Task_ID'])">اضافة</button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12" id="PopupPage" v-if="this.AddExpenseIndex == 1" @click=" this.AddExpenseIndex = 0">
            <div class="col-11 col-sm-10 col-md-8 col-lg-6" id="ExpenseBox" @click=" $event.stopPropagation();">
                <font-awesome-icon class="CloseSign" icon="fa-solid fa-x" id="CloseForm"
                    @click=" this.AddExpenseIndex = 0" />
                <div class="col-12" id="AddNewExpense">
                    <h1 class="col-12 Header">اضافة مصروف جديد</h1>
                    <table class="col-12 table table-bordered">
                        <thead>
                            <tr>
                                <th>اسم المصروف</th>
                                <th>المبلغ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="col-12" v-model="this.NewExpense['Name']" type="text"></td>
                                <td><input class="col-12" v-model="this.NewExpense['Value']" type="number"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-success"
                        v-if="(this.NewExpense['Name'] != '') && (this.NewExpense['Value'] != 0)"
                        @click=" this.AddNewExpense()">اضف المصروف</button>
                </div>
                <hr class="col-12">
                <div class="col-12" id="LastExpenses" v-if="this.TaskExpenses.length > 0">``
                    <h1 class="col-12 Header">المصروفات السابقة</h1>
                    <table class="col-12 table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>المصروف</th>
                                <th>المبلغ</th>
                                <th>التاريخ</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="                                                  Expense, index                                                   in                                                   this.TaskExpenses                                                  "
                                :key="Expense">
                                <td>{{ Expense['Expense_Name'] }}</td>
                                <td><b>{{ Expense['Expense_Value'] }}</b></td>
                                <td>{{ Expense['Last_Update'].indexOf('T') != -1 ? Expense['Last_Update'].split('T')[0] :
                                    'اليوم' }}
                                </td>
                                <td><button class="btn btn-danger" @click=" this.DeleteExpense(index)">حذف</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
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
            AddExpenseIndex: 0,
            NewExpense: {
                Name: "",
                Value: 0,
            },
            TaskExpenses: [],
            OpenTask: 0,
        };
    },
    created() {
        // console.log(this.$store.state['Production_Tasks'][0]);
    },
    methods: {
        EndTask(Task_ID) {
            let main = this;
            this.$swal.fire({
                title: 'هل تريد انهاء المهمة',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'انهاء',
                cancelButtonText: 'ليس الأن'
            }).then((result) => {
                if (result.isConfirmed) {
                    main.$store.state['LoaderIndex'] = 1;
                    axios.post(main.Api_Url, {
                        api_name: "UpdateTaskDone",
                        Task_ID: Task_ID,
                        NewVal: 1,
                    }).then(function (res) {
                        main.$store.state['LoaderIndex'] = 0;
                        main.$swal.fire(
                            'تم انهاء المهمة بنجاح',
                        );
                        location.reload();
                    });
                }
                else {
                    main.$store.state['LoaderIndex'] = 0;
                }
            })
        },
        UpdateTaskStage(event, Task_ID, index) {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            let NewID = $(event.target).val();
            this.$store.state['Production_Tasks'][index]['Task_Stage_ID'] = NewID;
            axios.post(this.Api_Url, {
                api_name: "UpdateTaskStage",
                Task_ID: Task_ID,
                NewVal: NewID,
            }).then(function (res) {
                main.$store.state['LoaderIndex'] = 0;
                main.$swal.fire({
                    title: 'تم تعديل حالة المهمة',
                    icon: 'success',
                });
            });

        },
        GetTaskExpenses(Task_ID) {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            axios.post(this.Api_Url, {
                api_name: "GetTaskExpenses",
                Task_ID: Task_ID
            }).then(function (res) {
                main.OpenTask = Task_ID;
                if (res.data.length > 0) {
                    main.TaskExpenses = res.data;
                }
                main.$store.state['LoaderIndex'] = 0;
            });
        },
        AddNewExpense() {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            let TheNewExpense = {
                Expense_ID: 0,
                Expense_Name: this.NewExpense['Name'],
                Expense_Value: this.NewExpense['Value'],
                Last_Update: 'today',
                Due_Task_ID: this.OpenTask
            };
            this.TaskExpenses.push(TheNewExpense);
            axios.post(this.Api_Url, {
                api_name: "AddNewExpense",
                Expense_Name: this.NewExpense['Name'],
                Expense_Value: this.NewExpense['Value'],
                Due_Task_ID: this.OpenTask,
            }).then(function (res) {
                main.$store.state['LoaderIndex'] = 0;
                main.$swal.fire({
                    title: 'تم اضافة المصروف بنجاح',
                    icon: 'success',
                });
            });
            this.NewExpense = {
                Name: "",
                Value: 0,
            }
        },
        DeleteExpense(index) {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            let Expense_ID = this.TaskExpenses[index]['Expense_ID'];
            this.$swal.fire({
                title: 'هل تريد حذف المصروف',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'انهاء',
                cancelButtonText: 'ليس الأن'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(this.Api_Url, {
                        api_name: "RemoveExpense",
                        Expense_ID: Expense_ID,
                    }).then(function (res) {
                        main.$store.state['LoaderIndex'] = 0;
                        main.TaskExpenses.splice(index, 1);
                        main.$swal.fire(
                            'تم حذف المصروف بنجاح ',
                        )
                    });

                }
            })
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
    padding: 1rem;

    #PopupPage {
        display: flex;
        top: 0;
        position: fixed;
        right: 0;
        height: 100vh;
        background-color: rgb(0 0 0 / 65%);
        justify-content: center;
        align-items: center;
        flex-direction: row;
        flex-wrap: nowrap;

        #ExpenseBox {
            height: 80vh;
            background-color: white;
            position: relative;
            display: flex;
            padding: 1rem;
            flex-direction: row;
            flex-wrap: wrap;
            align-content: flex-start;
            overflow-y: auto;

            .CloseSign {
                position: absolute;
                background-color: #dc3545;
                border: 1px solid #dc3545;
                padding: 0.4rem 0.5rem;
                border-radius: 50%;
                color: white;
                cursor: pointer;
                left: 1rem;
                top: 1rem;
            }

            .CloseSign:hover {
                border: 1px solid black;
            }

            .Header {
                font-size: 1.2rem;
                margin-bottom: 1rem !important;
            }
        }
    }

    #NoTasks {
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        align-items: center;
        height: auto;
        justify-content: center;

        h1 {
            font-size: 2rem;
            text-align: center;
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
        margin: 0;

        pre {
            margin: 0;
            padding: 0;
        }

        select {
            width: 80%;
            padding: 0.4rem;
        }

        button {
            width: 80%;
        }
    }


    .Task_Card {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-content: center;
        justify-content: center;
        align-items: center;
        background-color: white;
        margin-bottom: 1rem;
    }
}
</style>