<template>
  <div class="col-12" id="HomeView">
    <SideMenu />
    <WorkingArea />
  </div>
</template>

<script>
// @ is an alias to /src
import axios from 'axios';
import SideMenu from '@/components/HomeView/SideMenu.vue'
import WorkingArea from '@/components/HomeView/WorkingArea.vue'


export default {
  name: 'HomeView',
  components: { SideMenu, WorkingArea },
  data() {
    return {
      Api_Url: this.$store.state['Api_Url'],
    };
  },
  created() {
    let main = this;
    axios.post(main.Api_Url, {
      api_name: "GetServerDate",
    }).then(function (res) {
      main.$store.state['Server_Date'] = res.data['Server_Date'];
    });
  },
  methods: {
  },
  watch: {

  },
  computed: {

  },
}
</script>

<style lang="scss" scoped>
#HomeView {
  width: 100%;
  height: 100vh;
  display: flex;
  direction: rtl;
  flex-direction: row;
  flex-wrap: nowrap;
  overflow: auto;
  align-content: flex-start;

  @media screen and (max-width: 767px) {
    flex-wrap: wrap;
  }
}
</style>


