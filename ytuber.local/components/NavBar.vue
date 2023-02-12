<template>
  <div class="navbar navbar-expand-md navbar-light">
    <div class="navbar-header">
      <NuxtLink class="navbar-brand" to="/">Y<span>Tube</span>r</NuxtLink>
    </div>

    <div class="d-md-none">
      <button class="navbar-toggler" type="button" @click="sidebarChangerMobile">
        <i class="icon-paragraph-justify3"></i>
      </button>
      <button class="navbar-toggler" type="button" @click="sidebarSecondaryMobile">
        <i class="icon-user"></i>
      </button>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
        <i class="icon-paragraph-justify3"></i>
      </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a href="#"  @click="sidebarChangerMobile" class="navbar-nav-link">
            <i class="icon-paragraph-justify3"></i>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="navbar-nav-link sidebar-control sidebar-secondary-toggle d-none d-md-block">
            <i class="icon-transmission"></i>
          </a>
        </li>
      </ul>
      <span class="badge bg-success ml-md-3 mr-md-auto">Online</span>
      <div>
        <ul class="navbar-nav">
          <li class="nav-item">
            <nuxt-link
                v-for="locale in availableLocales"
                :key="locale.code"
                :to="switchLocalePath(locale.code)"><img :src="site+'/images/flags/'+locale.code+'.png'"/>&nbsp{{ locale.name }}</nuxt-link>
          </li>
        </ul>
      </div>
      <ul class="navbar-nav">
        <li class="nav-item dropdown dropdown-user">
          <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle"
             data-toggle="dropdown">
            <div class="rounded-circle avatarheader"
                 :style="{'background-image': 'url(' + user.avatar + ')'}"></div>
            <span>&nbsp;&nbsp;&nbsp;{{user.name}}</span>
          </a>

          <div class="dropdown-menu dropdown-menu-right">
            <NuxtLink to="/profile" class="dropdown-item"><i class="icon-user-plus"></i> Профиль
            </NuxtLink>
            <nuxt-link to="payments" class="dropdown-item"><i class="icon-coins"></i> Баланс&nbsp;{{user.balance}}</nuxt-link>
            <NuxtLink to="/task/list" href="#" class="dropdown-item"><i
                    class="icon-comment-discussion"></i> Мои задачи<span v-if="$store.state.ytuber.tasks.data && $store.state.ytuber.tasks.data.length>0"
                    class="badge badge-pill bg-blue ml-auto">{{$store.state.ytuber.tasks.data.length}}</span></NuxtLink>
            <div class="dropdown-divider"></div>
            <a @click="logout" class="dropdown-item"><i class="icon-switch2"></i> Выйти</a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
  export default {
    data() {
      return {
        site:process.env.site,
        //user: [],
      }
    },
    mounted() {
      $('body').toggleClass('sidebar-xs');
      this.getData();
      this.$nuxt.$on('sidebarClose2',()=>{
        this.sidebarSecondary();
      });
      this.$root.$on('updateBalance', ()=>{
        this.$store.dispatch("ytuber/getUser");
      });

    },
    methods: {
      sidebarChangerMobile() {
        //this.$nuxt.$emit('sidebarClose');
        $(document).ready(function () {
          $('body').toggleClass('sidebar-xs sidebar-mobile-main').removeClass('sidebar-main-mobile sidebar-mobile-secondary');
        });

      },
      sidebarMobileClose() {
        this.$nuxt.$emit('sidebarMobileClose');
      },
      isLoading(b) {
        this.$nuxt.$emit('isLoading', b);
      },
      sidebarChanger() {
        $(document).ready(function () {
          $('body').toggleClass('sidebar-left').removeClass('sidebar-left');
        });
        this.$nuxt.$emit('sidebarClose');
      },
      sidebarSecondaryMobile(){
        $(document).ready(function () {
          $('body').toggleClass('sidebar-xs sidebar-mobile-secondary').removeClass('sidebar-secondary-hidden');
        });
      },
      sidebarSecondary(){
        $(document).ready(function () {
          $('body').toggleClass('sidebar-xs sidebar-secondary-hidden');
        });
      },
      async getData() {
        this.$nuxt.$emit('isLoading', true);
        await this.$store.dispatch("ytuber/getCategories");
        await this.$store.dispatch("ytuber/getReferralSources");
        await this.$store.dispatch("ytuber/getTaskTypes");
        await this.$store.dispatch("ytuber/getUser");
        this.$nuxt.$emit('isLoading', false);
      },
      RouteToTasks() {
        this.$nuxt.$emit('minestatus', true);
        this.$router.push({name: 'MyTasks', params: {'filter.mode': true}});
      },
      RouteToProfile() {
        this.$router.push({name: 'Profile'});
      },
      RouteToUsers() {
        this.$router.push('Users');
      },
      RouteToAllTasks() {
        this.$router.push('all_tasks');
      },
      logout() {
        this.$axios.$post('/api/authlogout').then(res => {
                  //this.$route.push('/');
                  window.location.href = 'https://ytuber.ru'
                }
        );
      }
    },
    computed: {
      user() {
        return this.$store.state.ytuber.user
      },
      availableLocales () {
        return this.$i18n.locales.filter(i => i.code !== this.$i18n.locale)
      }
    }
  }
</script>
<style>
  .avatarheader {
    max-width: 35px;
    width: 35px;
    max-height: 35px;
    height: 35px;
    background-size: cover;
  }

  .navbar-brand span {
    color: #fff;
    border-radius: 7px;
    padding: 2px;
    background: #e42b28;
    background: -moz-linear-gradient(top, #e42b28 0, #d12122 50%, #bf171d 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #e42b28), color-stop(50%, #d12122), color-stop(100%, #bf171d));
    background: -webkit-linear-gradient(top, #e42b28 0, #d12122 50%, #bf171d 100%);
    background: -o-linear-gradient(top, #e42b28 0, #d12122 50%, #bf171d 100%);
    background: -ms-linear-gradient(top, #e42b28 0, #d12122 50%, #bf171d 100%);
    background: linear-gradient(to bottom, #e42b28 0, #d12122 50%, #bf171d 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#e42b28', endColorstr='#bf171d', GradientType=0);
  }

  .navbar-brand {
    font-size: 25px;
    color: #000 !important;
    text-transform: uppercase;
  }

  .navbar-header {
    max-width: 240px;
    text-align: center;
  }
</style>
