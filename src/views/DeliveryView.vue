<template>
    <div class="col-12" id="DeliveryView">
        <div class="col-12" v-if="this.$store.state['OpenDelivery'].length <= 0" id="NoTasks">
            <img src="@/assets/NoTasks.png">
            <h1>لا توجد مشاوير توصيل مطلوب العمل عليها</h1>
        </div>
        <div class="col-12" v-else>
            <h1 class="col-12 TabHeader">مشاوير التوصيل المطلوبة</h1>
            <div class="col-12 Task_Card" v-for="Task in   this.$store.state['OpenDelivery']   " :key="Task">
                <h1 class="col-12">معلومات الأستلام</h1>
                <table class="col-12 table table-bordered">
                    <tbody>
                        <tr>
                            <th class="col-6">مكان الاستلام</th>
                            <td class="col-6">{{ Task['PickUp_Location'] == null ? '----' : Task['PickUp_Location'] }}</td>
                        </tr>
                        <tr>
                            <th>موعد الأستلام</th>
                            <td>
                                {{ Task['PickUp_Time'] == null ? '----' : Task['PickUp_Time'].split("T")[0] }}
                                <br>
                                {{ Task['PickUp_Time'] != null ?
                                    'الساعة : ' + Task['PickUp_Time'].split("T")[1].split('+')[0] : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th>طريقة الأستلام</th>
                            <td>{{ Task['PickUp_Option'] == null ? '----' : Task['PickUp_Option'] }}</td>
                        </tr>
                        <tr>
                            <th>دليفري نوت</th>
                            <td>{{ Task['Delivery_Note'] == null ? '----' : Task['Delivery_Note'] }}</td>
                        </tr>
                    </tbody>
                </table>

                <h1 class="col-12">معلومات التسليم</h1>
                <table class="col-12 table table-bordered">
                    <tbody>
                        <tr>
                            <th class="col-6">اسم الشركة</th>
                            <td class="col-6">{{ Task['Delivery_Place'] == null ? '----' : Task['Delivery_Place']['name'] }}
                            </td>
                        </tr>
                        <tr>
                            <th>لوكيشن التسليم</th>
                            <td>
                                <a v-if="Task['Delivery_Location'] != null" :href="Task['Delivery_Location']">عرض
                                    الخريطة</a>
                                <p v-else>----</p>
                            </td>
                        </tr>
                        <tr>
                            <th>عنوان التسلم</th>
                            <td>{{ Task['Shipping_Details'] == null ? '----' : Task['Shipping_Details'] }}</td>
                        </tr>
                        <tr>
                            <th>رقم التواصل</th>
                            <td>{{ Task['Shipping_Phone_No'] == null ? '----' : Task['Shipping_Phone_No'] }}</td>
                        </tr>
                        <tr>
                            <th>مبلغ التحصيل</th>
                            <td>{{ Task['Collecting_Value'] != null ? Task['Collecting_Value'] : 'بدون تحصيل' }}</td>
                        </tr>
                        <tr>
                            <td><button class="btn btn-info"
                                    @click="this.AddExpenseIndex = 1; this.GetTaskExpenses(Task['id'])"> اضافة
                                    مصروف</button>
                            </td>
                            <td>
                                <button class="btn btn-danger" @click=" this.EndTask(Task['id']) ">تم
                                    التوصيل</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12" id="PopupPage" v-if=" this.AddExpenseIndex == 1 " @click=" this.AddExpenseIndex = 0 ">
            <div class="col-11 col-sm-10 col-md-8 col-lg-6" id="ExpenseBox" @click=" $event.stopPropagation(); ">
                <font-awesome-icon class="CloseSign" icon="fa-solid fa-x" id="CloseForm"
                    @click=" this.AddExpenseIndex = 0 " />
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
                                <td><input class="col-12" v-model=" this.NewExpense['Value'] " type="number"></td>
                                <td><input class="col-12" v-model=" this.NewExpense['Name'] " type="text"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button class="btn btn-success"
                        v-if=" (this.NewExpense['Name'] != '') && (this.NewExpense['Value'] != 0) "
                        @click=" this.AddNewExpense() ">اضف المصروف</button>
                </div>
                <hr class="col-12">
                <div class="col-12" id="LastExpenses" v-if=" this.TaskExpenses.length > 0 ">``
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
                            <tr v-for="                                                       Expense, index                                                        in                                                        this.TaskExpenses                                                       "
                                :key=" Expense ">
                                <td>{{ Expense['Expense_Name'] }}</td>
                                <td><b>{{ Expense['Expense_Value'] }}</b></td>
                                <td>{{ Expense['Last_Update'].indexOf('T') != -1 ? Expense['Last_Update'].split('T')[0] :
                                    'اليوم' }}
                                </td>
                                <td><button class="btn btn-danger" @click=" this.DeleteExpense(index) ">حذف</button></td>
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
    name: 'DeliveryView',
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

    },
    methods: {
        EndTask(Task_ID) {
            let main = this;
            this.$swal.fire({
                title: 'هل تريد تأكيد عملية التوصيل',
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
                        Task_Type: "Operation"
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
                Task_ID: Task_ID,
                Task_Type: "Operation"
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
                Task_Type: "Operation",
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
#DeliveryView {
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
        margin: 0;
        font-size: 1.2rem;

        th,
        td {
            padding: 1.2rem 0.5rem;
        }

        select {
            width: 100%;
            font-size: 1.2rem;
            padding: 0.4rem;
        }

        input {
            padding: 0.5rem;
        }
    }

    .Task_Card {
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        align-content: center;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        background-color: white;
        margin-bottom: 1rem;

        h1 {
            font-size: 1.1rem;
            margin: 1rem !important;
            font-weight: 800;
        }

    }
}
</style>