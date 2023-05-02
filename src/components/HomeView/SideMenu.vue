<template>
    <transition name="fade" mode="out-in">
        <div id="SideMenu"
            v-if="(this.$store.state['CurrentWidth'] < 767 && this.$store.state['SideMenuIndex'] == 1) || this.$store.state['CurrentWidth'] >= 767">
            <ul id="MenuSelections">
                <li v-for="Selection, index in this.Selections.filter(this.AvialbleTabs)" :key="Selection"
                    @click="Selection['TabView'] != false ? this.$store.state['CurrentComponent'] = Selection['TabView'] : this.LogOut()">
                    <font-awesome-icon :icon="Selection['Icon']" />
                    <p>{{ this.$store.state['UserLang'] == 1 ? Selection['ArabicName'] :
                        Selection['ArabicName'] }}
                    </p>
                    <span v-if="Selection['Index'] != false">{{ Selection['Index'] }}</span>
                </li>
            </ul>

        </div>
    </transition>
</template>
  
<script>
// @ is an alias to /src
import axios from 'axios';
export default {
    name: 'SideMenu',
    components: {},
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
            Selections: [
                { name: "Pricing Tasks", Icon: "fa-solid fa-money-check-dollar", ArabicName: "مهام التسعير", Index: 0, TabView: "TasksView", User_Type: [0, 1] },
                { name: "Pricing Tasks", Icon: "fa-solid fa-box-open", ArabicName: "مهام التصنيع", Index: this.$store.state['Production_Tasks'], TabView: "ProductionView", User_Type: [0, 1] },
                { name: "Pricing Tasks", Icon: "fa-solid fa-list-check", ArabicName: "المهام المنتهية", Index: this.$store.state['DoneTasks'], TabView: "DoneView", User_Type: [0, 1] },
                { name: "Pricing Tasks", Icon: "fa-solid fa-dollar-sign", ArabicName: "اسعار الخامات", Index: false, TabView: "MaterialView", User_Type: [0, 1] },
                { name: "Pricing Tasks", Icon: "fa-solid fa-list-check", ArabicName: "مهام التوصيل", Index: false, TabView: "DeliveryView", User_Type: [0, 2] },
                { name: "Pricing Tasks", Icon: "fa-solid fa-list-check", ArabicName: "مهام التوصيل المنتهية", Index: false, TabView: "DeliveryView", User_Type: [0, 2] },
                { name: "Pricing Tasks", Icon: "fa-solid fa-power-off", ArabicName: "تسجيل الخروج", Index: false, TabView: false, User_Type: [0, 1, 2] },
            ],
        };
    },
    created() {
        let main = this;
        let token = localStorage.getItem("token");
        let email = localStorage.getItem("email");
        axios.post(this.Api_Url, {
            api_name: "CheckTheConnection",
            TheUserID: localStorage.getItem("Zoho_ID"),
        }).then(function (res) {
            if (res.data['code'] !== undefined && res.data['code'] == 'INVALID_TOKEN') {
                window.location = 'https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id=1000.R3K41GUMKFVW5K825Z6PZ6JU1HTQ3Q&scope=ZohoCRM.modules.ALL,ZohoCRM.users.ALL&redirect_uri=https://webform.designido.net&prompt=consent';
            }
            else {
                if (token != null && token != undefined) {
                    axios.post(main.Api_Url, {
                        api_name: "CheckToken",
                        token: token,
                        email: email
                    }).then(function (res) {
                        if (res.data['user_id'] !== undefined) {
                            main.$store.state['User_Type'] = res.data['user_type'];
                            if (res.data['user_type'] == 1) {
                                main.GetPricingTasks();
                                main.GetOpenProjects();
                            }
                            else if (res.data['user_type'] == 0) {
                                main.GetPricingTasks();
                                main.GetOpenProjects();
                                main.GetDeliveryTasks();
                            }
                            else {
                                main.GetDeliveryTasks();
                            }
                        }
                        else {
                            localStorage.clear();
                            main.$router.push({ name: 'login' });
                        }
                    });
                }
                else {
                    localStorage.clear();
                    main.$router.push({ name: 'login' });
                }
            }
        });



    },
    methods: {
        AvialbleTabs(Tab) {
            let main = this;
            if (Tab['User_Type'].indexOf(main.$store.state['User_Type']) != -1) {
                return Tab;
            }
        },
        GetPricingTasks() {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            let Zoho_ID = localStorage.getItem('Zoho_ID');
            if (localStorage.getItem('user_type') == 0) {
                Zoho_ID = 0;
            }
            axios.post(main.Api_Url, {
                api_name: "GetPricingTasks",
                Zoho_ID: Zoho_ID,
            }).then(function (res) {
                let Final_array = res.data;
                main.Selections[0]['Index'] = Final_array.length;
                main.$store.state['Price_Tasks'] = Final_array;
                main.$store.state['LoaderIndex'] = 0;
            });
        },
        GetOpenProjects() {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            let Zoho_ID = localStorage.getItem('Zoho_ID');
            if (localStorage.getItem('user_type') == 0) {
                Zoho_ID = 0;
            }
            main.$store.state['LoaderIndex'] = 1;
            axios.post(main.Api_Url, {
                api_name: "GetOpenProjects",
                Zoho_ID: Zoho_ID,

            }).then(function (res) {
                let Final_array = res.data;
                function GetDoneTasks(Task) {
                    if (Task['Task_Done'] == 1) {
                        return Task;
                    }
                }
                function GetOpenTasks(Task) {
                    if (Task['Task_Done'] == 0) {
                        return Task;
                    }
                }
                main.$store.state['Production_Tasks'] = Final_array.filter(GetOpenTasks);
                main.$store.state['DoneTasks'] = Final_array.filter(GetDoneTasks);
                main.Selections[1]['Index'] = Final_array.filter(GetOpenTasks).length;
                main.Selections[2]['Index'] = Final_array.filter(GetDoneTasks).length;
                main.$store.state['LoaderIndex'] = 0;
            });
        },
        GetDeliveryTasks() {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            axios.post(main.Api_Url, {
                api_name: "GetDeliveryTasks"
            }).then(function (res) {
                let Final_array = res.data['data'];
                function GetOpenTasks(Task) {
                    if (Task['Task_Done'] == 0) {
                        return Task;
                    }
                }
                function GetDoneTasks(Task) {
                    if (Task['Task_Done'] == 1) {
                        return Task;
                    }
                }
                main.$store.state['LoaderIndex'] = 0;
                console.log(Final_array.filter(GetOpenTasks));
                main.$store.state['OpenDelivery'] = Final_array.filter(GetOpenTasks);
                main.$store.state['DoneDelivery'] = Final_array.filter(GetDoneTasks);
                main.Selections[4]['Index'] = Final_array.filter(GetOpenTasks).length;
                main.Selections[5]['Index'] = Final_array.filter(GetDoneTasks).length;
            });
        },
        LogOut() {
            localStorage.clear();
            this.$router.push({ name: 'login' });
        }
    },
    watch: {

    },
    computed: {

    },
}
</script>
  
<style lang="scss" scoped>
#SideMenu {
    display: flex;
    background: #131d26;
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;

    #MenuSelections {
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        padding: 1rem 2rem;
        align-items: flex-start;

        li {
            font-size: 1.4rem;
            list-style-type: none;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            color: white;
            padding: 1rem 0;
            justify-content: center;
            cursor: pointer;
            align-items: center;

            svg {
                color: grey;
            }

            p {
                font-size: 1rem;
                font-family: 'Alexandria', sans-serif;
                margin: 0 0.5rem !important;
                transition: all ease 300ms;
            }

            span {
                padding: 0 0.5rem;
                background-color: #389aed;
                border-radius: 7px;
                font-size: 0.8rem;
            }
        }

        li:hover>p {
            color: rgb(161, 161, 161);
        }

    }
}

.fade-enter-active,
.fade-leave-active {
    transition: width 350ms ease;
}

.fade-enter-from,
.fade-leave-to {
    width: 0;
}
</style>
  
  
  