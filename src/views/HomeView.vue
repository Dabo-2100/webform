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
    };
  },
  created() {
    let main = this;
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
  display: flex;
  direction: rtl;
}
</style>


