<template>
  <div style="background: #FFF">
    <b-container>
      <div class="navbar navbar-expand-md navbar-light">
        <nav class="navbar navbar-expand-lg navbar-light">
          <div class="logo">
            <a href="/" class="navbar-brand">Y<span>Tube</span>r</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <nuxt-link to="/" :class="'navbar-nav-link '+checkActiveMain(this.$route.path)">Главная</nuxt-link>
              </li>
              <li class="nav-item">
                <nuxt-link to="/#about" class="navbar-nav-link">О нас</nuxt-link>
              </li>
              <li class="nav-item">
                <a @click="$router.push('/login')" :class="'navbar-nav-link '+checkActiveLogin(this.$route.path)">вход</a>
              </li>
              <li class="nav-item">
                <a @click="$router.push('/registration')"  :class="'navbar-nav-link '+checkActiveReg(this.$route.path)">регистрация</a>
              </li>
            </ul>
          </div>
        </nav>
      </div>
    </b-container>
  </div>
</template>
<script>
export default{
  data() {
    return {
      classActiveNav: false,
    }
  },
  mounted() {

  },
  methods: {
    checkActiveMain(link){
      if(link == '/'){
        return 'active';
      }
      else{
        return 'nav';
      }
    },
    checkActiveMain(link){
      if(link == '/#about'){
        return 'active';
      }
      else{
        return 'nav';
      }
    },
    checkActiveReg(link){
      if(link == '/registration'){
        return 'active';
      }
      else{
        return 'nav';
      }
    },
    checkActiveLogin(link){
      if(link == '/login'){
        return 'active';
      }
      else{
        return 'nav';
      }
    },
  }
}
</script>
<style>
.navbar-light button.navbar-toggler{

}
.logo {
}
nav.navbar.navbar-expand-lg.navbar-light{

}
#navbarSupportedContent.collapse.navbar-collapse ul.navbar-nav.mr-auto{

}
#navbarSupportedContent.collapse.navbar-collapse ul.navbar-nav.mr-auto li.nav-item{
  padding: 0px 20px;
}
@media (max-width: 991.98px) {
  .navbar-expand-lg .navbar-nav:first-child > .nav-item:first-child {
    margin-top: 0;
  }
}
.navbar {
  position: relative;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: justify;
  -ms-flex-pack: justify;
  justify-content: space-between;
  padding: .5rem 1rem;
  min-width: 99%;
  width: 99%;
}
</style>