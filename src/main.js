import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap/dist/js/bootstrap";
import "animate.css";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import {
  faAppStore,
  faBlogger,
  faFacebook,
  faFacebookMessenger,
  faInstagram,
  faSquareFacebook,
  faSquareInstagram,
  faSquareTwitter,
  faSquareWhatsapp,
  faTwitter,
  faWhatsapp,
  faYoutube,
} from "@fortawesome/free-brands-svg-icons";
import {
  faDollarSign,
  faBoxOpen,
  faMoneyCheckDollar,
  faUserSecret,
  faComment,
  faCircleLeft,
  faUserTie,
  faPhone,
  faUser,
  faEye,
  faEyeSlash,
  faEnvelope,
  faGear,
  faMessage,
  faBoxArchive,
  faMagnifyingGlass,
  faCircle,
  faPlus,
  faArrowRightFromBracket,
  faCalendar,
  faWallet,
  faWarehouse,
  faGrip,
  faBell,
  faUsers,
  faTrash,
  faPen,
  faHouseChimneyMedical,
  faCircleCheck,
  faCircleDot,
  faStethoscope,
  faFilePrescription,
  faListCheck,
  faPaperclip,
  faSquareXmark,
  faSquarePlus,
  faCartShopping,
  faHeart,
  faHouse,
  faBars,
  faEarthAmerica,
  faRightLong,
  faLeftLong,
  faArrowLeft,
  faArrowRight,
  faCircleXmark,
  faHandHoldingHand,
  faPenToSquare,
  faCheck,
  faLaptopCode,
  faStore,
  faBlog,
  faX,
  faChevronDown,
  faPaperPlane,
  faDesktop,
} from "@fortawesome/free-solid-svg-icons";
library.add(
  faDollarSign,
  faListCheck,
  faBoxOpen,
  faMoneyCheckDollar,
  faDesktop,
  faCalendar,
  faPaperPlane,
  faYoutube,
  faX,
  faTwitter,
  faWhatsapp,
  faFacebookMessenger,
  faStore,
  faBlogger,
  faAppStore,
  faBlog,
  faLaptopCode,
  faCheck,
  faComment,
  faPenToSquare,
  faGear,
  faGrip,
  faCircle,
  faHandHoldingHand,
  faMessage,
  faCircleLeft,
  faCircleXmark,
  faCircleLeft,
  faArrowLeft,
  faArrowRight,
  faRightLong,
  faLeftLong,
  faFacebook,
  faInstagram,
  faEnvelope,
  faEarthAmerica,
  faPhone,
  faUserSecret,
  faUserTie,
  faUser,
  faEye,
  faEyeSlash,
  faEnvelope,
  faGear,
  faMessage,
  faBoxArchive,
  faMagnifyingGlass,
  faCircle,
  faPlus,
  faArrowRightFromBracket,
  faCalendar,
  faWallet,
  faWarehouse,
  faGrip,
  faBell,
  faUsers,
  faTrash,
  faPen,
  faHouseChimneyMedical,
  faCircleCheck,
  faCircleDot,
  faStethoscope,
  faFilePrescription,
  faListCheck,
  faPaperclip,
  faSquareXmark,
  faSquarePlus,
  faCartShopping,
  faHeart,
  faHouse,
  faMagnifyingGlass,
  faSquareFacebook,
  faSquareWhatsapp,
  faSquareTwitter,
  faSquareInstagram,
  faBars,
  faChevronDown
);
createApp(App)
  .component("font-awesome-icon", FontAwesomeIcon)
  .use(store)
  .use(VueSweetalert2)
  .use(router)
  .mount("#app");

// npm install axios
// npm install bootstrap
// npm install jquery
// npm install popper.js
// npm install @vuelidate/core @vuelidate/validators
// npm install @fortawesome/fontawesome-svg-core
// npm install @fortawesome/free-solid-svg-icons
// npm install @fortawesome/free-regular-svg-icons
// npm install @fortawesome/free-brands-svg-icons
// npm install @fortawesome/vue-fontawesome@latest-3
// npm install animate.css
// npm install sweetalert2
// npm install wow.js
// npm install wowjs
// npm install vue3-carousel
