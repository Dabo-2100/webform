<template>
  <MainLoader />
  <router-view />
</template>

<script>
import MainLoader from "@/components/MainLoader.vue";
import $ from "jquery";
window.$ = window.jQuery = require("jquery");
export default {
  name: "AppView",
  components: { MainLoader },
  data() {
    return {};
  },
  created() {
    let main = this;
    $(window).resize(function () {
      let payload = {
        WindowWidth: window.innerWidth,
      };
      main.$store.dispatch("ResizePage", payload);
    });
  },
};
</script>

<style lang="scss">
// @font-face {
//   font-family: LogoFont;
//   src: url("@/assets/bodoni bold condensed bt.ttf");
// }
@import url('https://fonts.googleapis.com/css2?family=Alexandria:wght@100;300;400;600;700;800;900&display=swap');
@import url("https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Oxygen:wght@300;400;700&family=Roboto:wght@400;700;900&family=Signika:wght@300;400;500;700&display=swap");

:root {
  --DarkBlue: #031926;
  --LightBlue: #9dbebb;
}

* {
  padding: 0;
  margin: 0;
  box-sizing: border-box;
  // font-family: "Montserrat", sans-serif;
  // font-family: "Oxygen", sans-serif;
  // font-family: "Roboto", sans-serif;
  // font-family: "Signika", sans-serif;
  font-family: 'Alexandria', sans-serif;
}

html {
  scroll-behavior: smooth;
}

p,
h1,
h2,
h3,
span,
ul {
  margin: 0 !important;
}

body {
  width: 100%;
  min-height: 100vh;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  direction: ltr;
  background-color: #f2f2f2 !important;
  align-content: flex-start;
  justify-content: flex-start;
  align-items: flex-start;

  #app {
    width: 100%;
    min-height: 100vh;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    padding: 0;
    align-content: space-between;
    justify-content: flex-start;
    align-items: flex-start;
    // @media screen and (max-width: 767px) {
    //   padding: 0 1rem;
    // }
  }
}

@media screen and (min-width: 100px) {

  /* font at xs */
  html {
    font-size: 12px !important;
  }
}

@media screen and (min-width: 767px) {

  /* font at sm */
  html {
    font-size: 14px !important;
  }
}

@media screen and (min-width: 991px) {

  /* font at md */
  html {
    font-size: 14px !important;
  }
}

@media screen and (min-width: 1200px) {

  /* font at lg */
  html {
    font-size: 16px !important;
  }
}

@media screen and (min-width: 1400px) {

  /* font at xl */
  html {
    font-size: 18px !important;
  }
}
</style>
