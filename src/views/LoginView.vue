<template>
    <div class="col-12" id="LoginView">
        <div class="col-12 col-md-4 col-lg-3" id="LoginForm">
            <h1>Login</h1>
            <input type="text" placeholder="Username" v-model="this.UserData['username']">
            <input type="password" placeholder="Password" v-model="this.UserData['password']">
            <button class="btn btn-success" @click="this.Login()">Login</button>

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
        };
    },
    created() {
        let main = this;
        axios.post(this.Api_Url, {
            api_name: "CheckTheConnection",
            TheUserID: 123456789,
        }).then(function (res) {
            if (res.data['code'] !== undefined && res.data['code'] == 'INVALID_TOKEN') {
                window.location = 'https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id=1000.R3K41GUMKFVW5K825Z6PZ6JU1HTQ3Q&scope=ZohoCRM.modules.ALL,ZohoCRM.users.ALL&redirect_uri=https://webform.designido.net&prompt=consent';
            }
            else {
                let token = localStorage.getItem("token");
                let email = localStorage.getItem("email");
                if (token != null && token != undefined) {
                    axios.post(main.Api_Url, {
                        api_name: "CheckToken",
                        token: token,
                        email: email
                    }).then(function (res) {
                        if (res.data['user_id'] !== undefined) {
                            main.$store.state['User_Type'] = res.data['user_type'];
                            main.$router.push({ name: 'home' });
                        }
                        else {
                            localStorage.clear();
                        }
                    });
                }
            }
        });
    },
    methods: {
        Login() {
            let main = this;
            if (main.UserData['username'] != '' && main.UserData['password'] != '') {
                axios.post(main.Api_Url, {
                    api_name: "Login",
                    username: main.UserData['username'],
                    password: main.UserData['password'],
                }).then(function (res) {
                    if (res.data['Zoho_ID'] !== undefined) {
                        localStorage.setItem("Zoho_ID", res.data['Zoho_ID']);
                        localStorage.setItem("email", res.data['email']);
                        localStorage.setItem("token", res.data['token']);
                        main.$router.push({ name: 'home' });
                    }
                    else {
                        alert("Worng Username or Password");
                    }
                });
            }
            else {
                alert('Please Enter Username and password');
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
    background-color: antiquewhite;
    align-content: center;
    justify-content: center;
    align-items: center;

    #LoginForm {
        background-color: grey;
        padding: 1rem 5%;
        display: flex;
        flex-direction: column;
        flex-wrap: nowrap;
        align-content: center;
        justify-content: center;
        align-items: center;

        h1 {
            font-size: 2.2rem;
            text-align: center;
        }

    }
}
</style>
  
  
  