import { createStore } from "vuex";
const Server_Url = "http://localhost/webform/public/";
// const Server_Url = "";
export default createStore({
  state: {
    Api_Url: Server_Url + "php/index.php",
    CurrentWidth: window.innerWidth,
    LoaderIndex: 0,
    SideMenuIndex: 0,
    ShowPopUp: 0,
    UserLang: 1,
    AppInfo: {
      AppLogo: "",
      AppName: "Pricing Gate",
    },
    CheckOut: {
      Index: 0,
      TheUserSelection: {},
      FinalData: [],
    },
    TheCalcResult: {},
    TheFinishPrice: [],
    TheWeights: [130, 170, 200, 250, 300, 350],
    TheUserSelection: {},
    AllProducts: [],
    TheTypes: [],
    TheProducts: [],
    TheSizes: [
      {
        name: "A1",
        width: 59.5,
        height: 84.1,
      },
      {
        name: "A2",
        width: 42,
        height: 59.4,
      },
      {
        name: "A3",
        width: 29.7,
        height: 42,
      },
      {
        name: "A4",
        width: 21,
        height: 29.7,
      },
      {
        name: "A5",
        width: 14.8,
        height: 21,
      },
      {
        name: "A6",
        width: 10.5,
        height: 14.8,
      },
      {
        name: "A7",
        width: 7.4,
        height: 10.5,
      },
      {
        name: "other",
        width: 0,
        height: 0,
      },
    ],
    TotalPiecesSize: [],
    PiecesSize: [],
    TheStyle: [
      {
        name: "One face",
        ArabicName: "وجه واحد",
      },
      {
        name: "Dobule face",
        ArabicName: "وجهان",
      },
    ],
    TheFinish: [],
    TheRawMaterial: [],
    TheCTPMaterial: [],
    TheTrajMaterial: [],
    TheCalcResult: {
      PaperPrice: 0,
      CtpPrice: 0,
      TrajPrice: 0,
    },
    TheFinishPrice: [],
  },
  getters: {},
  mutations: {
    OpenLoader() {
      this.state["LoaderIndex"] = 1;
    },
    CloseLoader() {
      this.state["LoaderIndex"] = 0;
    },
    OpenSideMenu() {
      this.state["SideMenuIndex"] = 1;
    },
    CloseSideMenu() {
      this.state["SideMenuIndex"] = 0;
    },
  },
  actions: {
    ResizePage: ({ state }, payload) => {
      state.CurrentWidth = payload["WindowWidth"];
    },
    OpenAdminSection: ({ state }, payload) => {
      state.AdminViewCurrent = payload["SectionToOpen"];
    },
    AddProductToCart: ({ state }, payload) => {
      let TheId = payload["ProductId"];
      let index = state.TheCart.findIndex((object) => {
        return object.ProductId == TheId;
      });
      if (index != -1) {
        state.TheCart.splice(index, 1);
      }
      state.TheCart.push(payload);
    },
  },
  modules: {},
});
