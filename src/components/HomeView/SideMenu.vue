<template>
    <transition name="fade" mode="out-in">
        <div id="SideMenu"
            v-if="(this.$store.state['CurrentWidth'] < 767 && this.$store.state['SideMenuIndex'] == 1) || this.$store.state['CurrentWidth'] >= 767">
            <ul id="MenuSelections">
                <li v-for="Selection in this.Selections" :key="Selection"
                    @click="this.$store.state['CurrentComponent'] = Selection['TabView']">
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
                { name: "Pricing Tasks", Icon: "fa-solid fa-user", ArabicName: "مهام التسعير", Index: 0, TabView: "TasksView" },
                { name: "Pricing Tasks", Icon: "fa-solid fa-user", ArabicName: "المشاريع المفتوحة", Index: 40 },
                { name: "Pricing Tasks", Icon: "fa-solid fa-user", ArabicName: "المشاريع المنتهية", Index: false },
                { name: "Pricing Tasks", Icon: "fa-solid fa-user", ArabicName: "اسعار الخامات", Index: false, TabView: "MaterialView" },
            ],
        };
    },
    created() {
        let main = this;
        main.GetPricingTasks();
    },
    methods: {
        GetPricingTasks() {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            axios.post(main.Api_Url, {
                api_name: "GetPricingTasks"
            }).then(function (res) {
                let Final_array = res.data;
                main.Selections[0]['Index'] = Final_array.length;
                main.$store.state['Price_Tasks'] = Final_array;
                main.$store.state['LoaderIndex'] = 0;
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
  
  
  