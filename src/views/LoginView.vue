<template>
    <div class="col-12" id="LoginView">
        <div class="col-12 Filter">
            <div id="LoginForm">
                <div class="ImgContainer">
                    <img src="@/assets/TheLogo.png" id="TheLogo">
                </div>
                <div class="InputField">
                    <font-awesome-icon icon="fa-solid fa-user" class="InputIcon" />
                    <input @keypress.enter="this.Login()" type="text" placeholder="Username"
                        v-model="this.UserData['username']">
                </div>
                <div class="InputField">
                    <font-awesome-icon icon="fa-solid fa-lock" class="InputIcon" />
                    <input @keypress.enter="this.Login()" type="password" placeholder="Password"
                        v-model="this.UserData['password']">
                </div>
                <button class="btn btn-success" @click="this.Login()">Get Started</button>
            </div>
        </div>
    </div>
</template>
  
<script>
// @ is an alias to /src
import axios from 'axios';
export default {
    name: 'LoginView',
    components: {},
    data() {
        return {
            Api_Url: this.$store.state['Api_Url'],
            UserData: {
                username: "",
                password: "",
            },
            LoginIndex: 0,
        };
    },
    created() {
        let main = this;
        main.$store.state['LoaderIndex'] = 1;
        let ThePureURL = window.location.href;
        if (ThePureURL.indexOf("code") != -1) {
            let TheCode = ThePureURL.split('code=')[1].split('&')[0];
            axios.post(this.Api_Url, {
                api_name: "RefreshAccessToken",
                the_code: TheCode,
            }).then(function (res) {
                console.log(res.data);
                // main.$router.push({ name: 'login' });
            });
        }
        else {
            let token = localStorage.getItem("token");
            let email = localStorage.getItem("email");
            let Zoho_ID = localStorage.getItem("Zoho_ID");
            if (Zoho_ID != null) {
                if (token != null && token != undefined) {
                    axios.post(main.Api_Url, {
                        api_name: "CheckToken",
                        token: token,
                        email: email
                    }).then(function (res) {
                        if (res.data['user_id'] !== undefined) {
                            main.$router.push({ name: 'home' });
                            main.$store.state['CurrentComponent'] = "DashboardView";
                        }
                        else {
                            localStorage.clear();
                        }
                        main.$store.state['LoaderIndex'] = 0;
                    });
                }
                else {
                    localStorage.clear();
                    main.$store.state['LoaderIndex'] = 0;
                }
            }
            else {
                localStorage.clear();
                main.$store.state['LoaderIndex'] = 0;
            }
        }

    },
    methods: {
        Login() {
            let main = this;
            main.$store.state['LoaderIndex'] = 1;
            if (this.LoginIndex == 0) {
                this.LoginIndex = 1;
                if (main.UserData['username'] != '' && main.UserData['password'] != '') {
                    axios.post(main.Api_Url, {
                        api_name: "Login",
                        username: main.UserData['username'],
                        password: main.UserData['password'],
                    }).then(function (res) {
                        main.LoginIndex = 0;
                        main.$store.state['LoaderIndex'] = 0;
                        console.log(res.data);
                        if (res.data['Zoho_Error'] == true) {
                            if (res.data['Login_Error'] == false) {
                                localStorage.setItem("email", res.data['email']);
                                localStorage.setItem("token", res.data['token']);
                                localStorage.setItem("user_type", res.data['user_type']);
                                alert('Good Login No Zoho Connection');
                                window.location = 'https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id=1000.R3K41GUMKFVW5K825Z6PZ6JU1HTQ3Q&scope=ZohoCRM.modules.ALL,ZohoCRM.users.ALL&redirect_uri=https://webform.designido.net&prompt=consent';
                            }
                            else {
                                main.$swal.fire({
                                    title: 'Worng Username or Password',
                                    icon: 'error',
                                });
                            }
                        }
                        else {
                            if (res.data['Login_Error'] == true) {
                                main.$swal.fire({
                                    title: 'Worng Username or Password',
                                    icon: 'error',
                                });
                            }
                            else {
                                localStorage.setItem("Zoho_ID", res.data['Zoho_ID']);
                                localStorage.setItem("email", res.data['email']);
                                localStorage.setItem("token", res.data['token']);
                                localStorage.setItem("user_type", res.data['user_type']);
                                main.$store.state['User_Type'] = res.data['user_type'];
                                main.$router.push({ name: 'home' });
                                main.$store.state['CurrentComponent'] = "DashboardView";
                            }
                        }
                    });
                }
                else {
                    main.LoginIndex = 0;
                    main.$store.state['LoaderIndex'] = 0;
                    main.$swal.fire({
                        title: 'Please Enter Username and password',
                        icon: 'info',
                    });
                }
            }
        }
    },
    watch: {

    },
    computed: {
    },
}
</script>
  
<style lang="scss" scoped>
#LoginView {
    width: 100%;
    height: 100vh;
    overflow-y: auto;
    display: flex;
    direction: ltr;
    flex-direction: row;
    flex-wrap: nowrap;
    background-image: url('@/assets/login_bg.jpg');
    align-content: center;
    justify-content: center;
    align-items: center;
    background-size: cover;
    background-position: 50%;

    .Filter {
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.60);
        display: flex;
        flex-direction: row;
        align-content: center;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;

        #LoginForm {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            flex-wrap: nowrap;
            justify-content: center;

            .ImgContainer {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                align-content: center;
                justify-content: center;
                align-items: center;
                margin-bottom: 1rem;

                #TheLogo {
                    height: 8rem;
                }
            }

            .InputField {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                justify-content: center;
                align-items: center;
                position: relative;

                .InputIcon {
                    position: absolute;
                    left: 1rem;
                    top: 0.6rem;
                    height: 1.3rem;
                    color: #6d7575;
                }

                input {
                    border-radius: 5rem;
                    background-color: rgba(202, 202, 202, 0.205);
                    border: 0;
                    outline: 0;
                    padding: 0.4rem 1rem;
                    color: white;
                    font-size: 1.2rem;
                    margin-bottom: 1rem;
                    padding-left: 3rem;
                    // cursor: pointer;
                    transition: all ease 500ms;
                }

                input:hover {
                    background-color: rgba(138, 129, 129, 0.205);
                }

            }

            button {
                font-size: 1.2rem;
                background-color: #fb646a;
                border: 0;
                outline: 0;
                border-radius: 5rem;
                transition: all ease 500ms;
            }

            button:hover {
                background-color: #fb6469c5;
            }

            h1 {
                font-size: 2.2rem;
                text-align: center;
            }
        }
    }

}
</style>
  
  
  