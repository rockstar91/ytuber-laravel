<template>
  <div id="app">
    <NavBar/>
    <div class="page-content">
      <div v-bind:class="'sidebar sidebar-light '+siderbarClassNow+' sidebar-main sidebar-expand-md'">
        <Sidebar></Sidebar>
      </div>
      <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md">
        <SidebarSecondary></SidebarSecondary>
      </div>
      <FloatNav></FloatNav>
      <loadingBar/>
      <div class="content-wrapper bg-light">
        <div class="content">
          <Header></Header>
          <nuxt/>
          <Footer></Footer>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      class: '',
      themeSidebar: '',
      siderbarClassNow: '',
      windowWidth: ''
    }
  },
  mounted() {
    this.$nuxt.$on('sidebarClose', () => {
      siderbarClassNow : 'sidebar-mobile-main'
    })
    this.$nuxt.$on('sidebarClose2', () => {
      siderbarClassNow : 'sidebar-mobile-main'
    })
    window.addEventListener('resize', () => {
      this.windowWidth = window.innerWidth
    })
  },
  methods: {
    siderbarClassChanger() {
      console.log('siderbarClassChanger');
    }
  },
  computed: {
    /*    theme(){
          return this.$store.state.socbooster.theme
        }*/
  },
  watch: {
    windowWidth: {
      immediate: false,
      handler() {
        if (this.windowWidth <= 1360) {
          let data = {
            mode: false,
          };
          this.$store.dispatch('ytuber/setMaketMode', data);
        } else {
          let data = {
            mode: true,
          };
          this.$store.dispatch('ytuber/setMaketMode', data);
        }
      }
    },
  }
}
</script>
