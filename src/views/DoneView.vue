<template>
    <div class="col-12" id="DoneView">
        <div class="col-12" v-if="this.$store.state['DoneTasks'].length <= 0" id="NoTasks">
            <img src="@/assets/NoTasks.png">
            <h1>لا توجد مشاريع منتهية حتي الأن</h1>
        </div>
        <div class="col-12" v-else>
            <h1 class="col-12 TabHeader">المشاريع المنتهية</h1>
            <table class="col-12 table table-hover table-bordered">
                <thead>
                    <tr>
                        <th>-</th>
                        <th>اسم المهمة</th>
                        <th>تاريخ التسليم</th>
                        <th>المصروفات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="Task, index in       this.$store.state['DoneTasks']      " :key="Task">
                        <td>{{ index + 1 }}</td>
                        <td>{{ Task['Task_Name'] }}</td>
                        <td>{{ Task['Starting_Date'] }}</td>
                        <td>
                            <button class="btn btn-info"
                                @click="this.AddExpenseIndex = 1; this.GetTaskExpenses(Task['Task_ID'])">عرض</button>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                                <th class="col-5">المبلغ</th>
                                <th class="col-7">اسم المصروف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input class="col-12" v-model="this.NewExpense['Value']" type="number"
                                        placeholder="المبلغ"></td>
                                <td><input class="col-12" v-model="this.NewExpense['Name']" type="text"
                                        placeholder="المصروف"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-success"
                        v-if="(this.NewExpense['Name'] != '') && (this.NewExpense['Name'] != null) && (this.NewExpense['Value'] != null) && (this.NewExpense['Value'] != 0)"
                        @click=" this.AddNewExpense()">اضف المصروف</button>
                </div>
                <hr class="col-12">
                <div class="col-12" id="LastExpenses" v-if="this.TaskExpenses.length > 0">
                    <h1 class="col-12 Header">المصروفات السابقة</h1>
                    <table class="col-12 table table-bordered table-hover" v-if="this.TaskExpenses.length > 0">
                        <thead>
                            <tr>
                                <th>المصروف</th>
                                <th>المبلغ</th>
                                <th>التاريخ</th>
                                <th>حذف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="      Expense, index       in       this.TaskExpenses      " :key="Expense">
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
    name: 'DoneView',
    components: {},
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
            AddExpenseIndex: 0,
            NewExpense: {
                Name: null,
                Value: null,
            },
            TaskExpenses: [],
            OpenTask: 0,
        };
    },
    created() {
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
                        )
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
            if (main.OpenTask != Task_ID && main.TaskExpenses.length == 0) {
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
            }
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
                main.NewExpense = {
                    Name: null,
                    Value: null,
                }
                setTimeout(() => {
                    main.$store.state['LoaderIndex'] = 0;
                    main.$swal.fire({
                        title: 'تم اضافة المصروف بنجاح',
                        icon: 'success',
                    });
                }, 2000);
            });

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
                confirmButtonText: 'حذف',
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
#DoneView {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    align-content: flex-start;
    justify-content: center;
    align-items: center;

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

    button {
        width: 100%;
        font-size: 1.3rem;
        padding: 1rem;
    }

    table {
        text-align: center;
        vertical-align: middle;
        text-align: center;
        vertical-align: middle;
        margin: 0;
        font-size: 1.3rem;

        th,
        td {
            padding: 1.2rem 0.5rem;
        }

        select {
            width: 100%;
            font-size: 1.3rem;
            padding: 0.4rem;
        }

        input {
            padding: 0.5rem;
        }
    }
}
</style>