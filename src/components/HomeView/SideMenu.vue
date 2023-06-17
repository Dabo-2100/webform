<template>
  <transition name="fade" mode="out-in">
    <div
      id="SideMenu"
      v-if="
        this.$store.state['CurrentWidth'] > 767 ||
        (this.$store.state['CurrentWidth'] <= 767 &&
          this.$store.state['SideMenuIndex'] == 1)
      "
    >
      <ul id="MenuSelections">
        <li
          v-if="
            this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 1
          "
          @click="
            this.GetPricingTasks();
            this.$store.state['CurrentComponent'] = this.Selections[0]['TabView'];
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[0]['Icon']" />
          <p>{{ this.Selections[0]["ArabicName"] }}</p>
          <span v-if="this.Selections[0]['Index'] != false">{{
            this.Selections[0]["Index"]
          }}</span>
        </li>

        <li
          v-if="
            this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 1
          "
          @click="
            this.GetOpenProjects();
            this.$store.state['CurrentComponent'] = this.Selections[1]['TabView'];
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[1]['Icon']" />
          <p>{{ this.Selections[1]["ArabicName"] }}</p>
          <span v-if="this.Selections[1]['Index'] != false">{{
            this.Selections[1]["Index"]
          }}</span>
        </li>

        <li
          v-if="
            this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 1
          "
          @click="
            this.GetOpenProjects();
            this.$store.state['CurrentComponent'] = this.Selections[2]['TabView'];
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[2]['Icon']" />
          <p>{{ this.Selections[2]["ArabicName"] }}</p>
          <span v-if="this.Selections[2]['Index'] != false">{{
            this.Selections[2]["Index"]
          }}</span>
        </li>

        <li
          v-if="
            this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 1
          "
          @click="
            this.GetOpenProjects();
            this.$store.state['CurrentComponent'] = this.Selections[3]['TabView'];
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[3]['Icon']" />
          <p>{{ this.Selections[3]["ArabicName"] }}</p>
          <span v-if="this.Selections[3]['Index'] != false">{{
            this.Selections[3]["Index"]
          }}</span>
        </li>

        <li
          v-if="
            this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 2
          "
          @click="
            GetPurchaseTasks();
            this.$store.state['CurrentComponent'] = this.Selections[4]['TabView'];
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[4]['Icon']" />
          <p>{{ this.Selections[4]["ArabicName"] }}</p>
          <span v-if="this.Selections[4]['Index'] != false">{{
            this.Selections[4]["Index"]
          }}</span>
        </li>

        <li
          v-if="
            this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 2
          "
          @click="
            this.GetDeliveryTasks();
            this.$store.state['CurrentComponent'] = this.Selections[5]['TabView'];
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[5]['Icon']" />
          <p>{{ this.Selections[5]["ArabicName"] }}</p>
          <span v-if="this.Selections[5]['Index'] != false">{{
            this.Selections[5]["Index"]
          }}</span>
        </li>

        <li
          v-if="
            this.$store.state['User_Type'] == 0 || this.$store.state['User_Type'] == 2
          "
          @click="
            this.GetDeliveryTasks();
            this.$store.state['CurrentComponent'] = this.Selections[6]['TabView'];
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[6]['Icon']" />
          <p>{{ this.Selections[6]["ArabicName"] }}</p>
          <span v-if="this.Selections[6]['Index'] != false">{{
            this.Selections[5]["Index"]
          }}</span>
        </li>

        <li
          @click="
            this.LogOut();
            this.$store.state['SideMenuIndex'] = 0;
          "
        >
          <font-awesome-icon :icon="this.Selections[7]['Icon']" />
          <p>{{ this.Selections[7]["ArabicName"] }}</p>
          <span v-if="this.Selections[7]['Index'] != false">{{
            this.Selections[7]["Index"]
          }}</span>
        </li>
      </ul>
    </div>
  </transition>

  <div class="col-12" id="Upper_Bar" v-if="this.$store.state['CurrentWidth'] <= 767">
    <div class="col-6" id="TheSliderIcon" @click="this.$store.state['SideMenuIndex'] = 1">
      <font-awesome-icon icon="fa-solid fa-bars" />
      <p>القائمة</p>
    </div>
  </div>
</template>

<script>
// @ is an alias to /src
import axios from "axios";
export default {
  name: "SideMenu",
  components: {},
  data() {
    return {
      Api_Url: this.$store.state["Api_Url"],
      Selections: [
        {
          Icon: "fa-solid fa-money-check-dollar",
          ArabicName: "طلبات التسعير",
          Index: 0,
          TabView: "TasksView",
        },
        {
          Icon: "fa-solid fa-list-check",
          ArabicName: "مهام تحت التشغيل",
          Index: 0,
          TabView: "ProductionView",
        },
        {
          Icon: "fa-solid fa-dollar-sign",
          ArabicName: "المهام المنتهية",
          Index: false,
          TabView: "DoneView",
        },
        {
          Icon: "fa-solid fa-list-check",
          ArabicName: "اسعار الخامات",
          Index: false,
          TabView: "MaterialView",
        },
        {
          Icon: "fa-solid fa-list-check",
          ArabicName: "طلبات الشراء",
          Index: false,
          TabView: "PurchaseView",
        },
        {
          Icon: "fa-solid fa-list-check",
          ArabicName: "طلبات التوصيل",
          Index: false,
          TabView: "DeliveryView",
        },
        {
          Icon: "fa-solid fa-list-check",
          ArabicName: "المشاوير المنتهية",
          Index: false,
          TabView: "DoneDeliveryView",
        },
        {
          Icon: "fa-solid fa-power-off",
          ArabicName: "تسجيل الخروج",
          Index: false,
          TabView: false,
        },
      ],
    };
  },
  created() {
    let main = this;
    let token = localStorage.getItem("token");
    let email = localStorage.getItem("email");
    let Zoho_ID = localStorage.getItem("Zoho_ID");
    axios
      .post(this.Api_Url, {
        api_name: "CheckTheConnection",
        TheUserID: Zoho_ID,
      })
      .then(function (res) {
        if (res.data["code"] !== undefined && res.data["code"] == "INVALID_TOKEN") {
          window.location =
            "https://accounts.zoho.com/oauth/v2/auth?response_type=code&client_id=1000.R3K41GUMKFVW5K825Z6PZ6JU1HTQ3Q&scope=ZohoCRM.modules.ALL,ZohoCRM.users.ALL&redirect_uri=https://webform.designido.net&prompt=consent";
        } else {
          main.$store.state["LoaderIndex"] = 1;
          if (token != null && token != undefined) {
            axios
              .post(main.Api_Url, {
                api_name: "CheckToken",
                token: token,
                email: email,
              })
              .then(function (res) {
                if (res.data["user_id"] !== undefined) {
                  main.$store.state["User_Type"] = res.data["user_type"];
                  if (res.data["user_type"] == 1) {
                    main.GetPricingTasksIndex();
                    main.GetOpenProjectsIndex();
                  } else if (res.data["user_type"] == 0) {
                    main.GetPricingTasksIndex();
                    main.GetOpenProjectsIndex();
                    main.GetDeliveryTasks();
                    main.GetPurchaseTasks();
                  } else {
                    main.GetDeliveryTasks();
                    main.GetPurchaseTasks();
                  }
                  main.$store.state["LoaderIndex"] = 0;
                } else {
                  localStorage.clear();
                  main.$router.push({ name: "login" });
                }
              });
          } else {
            localStorage.clear();
            main.$router.push({ name: "login" });
          }
        }
      });
  },
  methods: {
    GetPricingTasks() {
      let main = this;
      main.$store.state["LoaderIndex"] = 1;
      let Zoho_ID = localStorage.getItem("Zoho_ID");
      if (localStorage.getItem("user_type") == 0) {
        Zoho_ID = 0;
      }
      axios
        .post(main.Api_Url, {
          api_name: "GetPricingTasks",
          Zoho_ID: Zoho_ID,
        })
        .then(function (res) {
          let Final_array = res.data;
          console.log(Final_array);
          main.Selections[0]["Index"] = Final_array.length;
          main.$store.state["Price_Tasks"] = Final_array;
          main.$store.state["LoaderIndex"] = 0;
        });
    },
    GetPricingTasksIndex() {
      let main = this;
      let Zoho_ID = localStorage.getItem("Zoho_ID");
      if (localStorage.getItem("user_type") == 0) {
        Zoho_ID = 0;
      }
      axios
        .post(main.Api_Url, {
          api_name: "GetPricingTasks",
          Zoho_ID: Zoho_ID,
        })
        .then(function (res) {
          let Final_array = res.data;
          main.Selections[0]["Index"] = Final_array.length;
          main.$store.state["Price_Tasks"] = Final_array;
        });
    },
    GetOpenProjects() {
      let main = this;
      main.$store.state["LoaderIndex"] = 1;
      function GetDoneTasks(Task) {
        if (Task["Task_Done"] == 1) {
          return Task;
        }
      }
      function GetOpenTasks(Task) {
        if (Task["Task_Done"] == 0) {
          return Task;
        }
      }
      let Zoho_ID = localStorage.getItem("Zoho_ID");
      if (localStorage.getItem("user_type") == 0) {
        Zoho_ID = 0;
      }
      axios
        .post(main.Api_Url, {
          api_name: "GetOpenProjects",
          Zoho_ID: Zoho_ID,
        })
        .then(function (res) {
          let Final_array = res.data;
          let OpenTasks = [];
          let DoneTasks = [];
          // console.log(Final_array);
          if (Final_array != null) {
            OpenTasks = Final_array.filter(GetOpenTasks);
            DoneTasks = Final_array.filter(GetDoneTasks);
          }
          main.Selections[1]["Index"] = OpenTasks.length;
          main.Selections[2]["Index"] = DoneTasks.length;
          main.$store.state["Production_Tasks"] = OpenTasks;
          main.$store.state["DoneTasks"] = DoneTasks;
          main.$store.state["LoaderIndex"] = 0;
        });
    },
    GetOpenProjectsIndex() {
      function GetDoneTasks(Task) {
        if (Task["Task_Done"] == 1) {
          return Task;
        }
      }
      function GetOpenTasks(Task) {
        if (Task["Task_Done"] == 0) {
          return Task;
        }
      }

      let main = this;
      let Zoho_ID = localStorage.getItem("Zoho_ID");
      if (localStorage.getItem("user_type") == 0) {
        Zoho_ID = 0;
      }
      axios
        .post(main.Api_Url, {
          api_name: "GetOpenProjects",
          Zoho_ID: Zoho_ID,
        })
        .then(function (res) {
          let Final_array = res.data;
          let OpenTasks = [];
          let DoneTasks = [];
          if (Final_array != null) {
            OpenTasks = Final_array.filter(GetOpenTasks);
            DoneTasks = Final_array.filter(GetDoneTasks);
          }
          main.Selections[1]["Index"] = OpenTasks.length;
          main.Selections[2]["Index"] = DoneTasks.length;
          main.$store.state["Production_Tasks"] = OpenTasks;
          main.$store.state["DoneTasks"] = DoneTasks;
        });
    },
    GetDeliveryTasks() {
      let main = this;
      main.$store.state["LoaderIndex"] = 1;
      function GetOpenTasks(Task) {
        if (
          Task["Task_Done"] == 0 &&
          Task["Task_Type"] == "Delivery" &&
          Task["PickUp_Location"] != null
        ) {
          return Task;
        }
      }
      function GetDoneTasks(Task) {
        if (
          Task["Task_Done"] == 1 &&
          (Task["Task_Type"] == "Delivery" || Task["Task_Type"] == "Purchase")
        ) {
          return Task;
        }
      }
      axios
        .post(main.Api_Url, {
          api_name: "GetDeliveryTasks",
        })
        .then(function (res) {
          let Final_array = res.data["data"];
          let OpenTasks = [];
          let DoneTasks = [];
          if (Final_array != null) {
            OpenTasks = Final_array.filter(GetOpenTasks);
            DoneTasks = Final_array.filter(GetDoneTasks);
          }
          main.Selections[5]["Index"] = OpenTasks.length;
          main.Selections[6]["Index"] = DoneTasks.length;
          main.$store.state["OpenDelivery"] = OpenTasks;
          main.$store.state["DoneDelivery"] = DoneTasks;
          main.$store.state["LoaderIndex"] = 0;
        });
    },
    GetPurchaseTasks() {
      let main = this;
      function GetOpenTasks(Task) {
        if (
          Task["Task_Done"] == "0" &&
          Task["Task_Type"] == "Purchase" &&
          Task["Purchase_Place"] != null
        ) {
          return Task;
        }
      }
      axios
        .post(main.Api_Url, {
          api_name: "GetDeliveryTasks",
        })
        .then(function (res) {
          let Final_array = res.data["data"];
          let OpenTasks = [];
          if (Final_array != null) {
            OpenTasks = Final_array.filter(GetOpenTasks);
          }
          main.Selections[4]["Index"] = OpenTasks.length;
          main.$store.state["OpenPurchase"] = OpenTasks;
          console.log(OpenTasks);
        });
    },
    LogOut() {
      localStorage.clear();
      this.$router.push({ name: "login" });
    },
  },
};
</script>

<style lang="scss" scoped>
#SideMenu {
  display: flex;
  background: #131d26;
  min-height: 100vh;
  overflow: auto;

  @media screen and (max-width: 767px) {
    height: 100vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    align-content: center;
    justify-content: flex-start;
    align-items: center;
    position: fixed;
    padding: 1rem;
    top: 0;
    left: 0;
  }

  #MenuSelections {
    display: flex;
    flex-wrap: nowrap;
    flex-direction: column;
    padding: 1rem 2rem;
    align-items: flex-start;

    @media screen and (max-width: 767px) {
      flex-wrap: nowrap;
      flex-direction: column;
      justify-content: flex-start;
      padding: 0.5rem;
      padding-bottom: 1.4rem;
      padding-top: 0;
      display: flex;
      align-items: center;
    }

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
      position: relative;

      @media screen and (max-width: 767px) {
        justify-content: flex-start;
        flex-direction: column;
        padding: 0;
        padding-top: 4.4rem;
      }

      svg {
        color: grey;
      }

      p {
        font-size: 1rem;
        // @media screen and (max-width: 767px){
        //     font-size: 1.1rem;
        // }
        font-family: "Alexandria", sans-serif;
        margin: 0 0.5rem !important;
        transition: all ease 300ms;

        @media screen and (max-width: 767px) {
          font-size: 1.5rem;
          padding-top: 0.25rem;
        }
      }

      span {
        padding: 0 0.6rem;
        background-color: #389aed;
        border-radius: 7px;
        font-size: 1.5rem;
        top: 3rem;

        @media screen and (max-width: 767px) {
          position: absolute;
          left: 1rem;
        }
      }
    }

    li:hover > p {
      color: rgb(161, 161, 161);
    }
  }
}

#Upper_Bar {
  background: #1e4a84;
  padding: 1rem;

  #TheSliderIcon {
    color: white;
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-content: center;
    justify-content: flex-start;
    align-items: center;
    font-size: 1.8rem;

    p {
      font-size: 1.7rem;
      padding: 0 1rem;
    }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: width 500ms ease;
}

.fade-enter-from,
.fade-leave-to {
  width: 0;
}
</style>
