<template>
  <div class="sidebar-content">
    <div class="sidebar-user">
      <div class="card-body">
        <div class="media">
            <b-col>
              <b-row cols="3">
                <b-col sm>
                  <div class="media">
                    <a href="#" class="mr-3">
                      <img v-if="user && user.channel" :src="fixImgPath('https://www.youtube.com/channel/'+user.channel)" width="80" height="80" class="rounded-circle" alt="">
                      <img v-else src="/user.png" width="80" height="80" class="rounded-circle" alt="">
                    </a>
                  </div>
                </b-col>
                <b-col sm style="max-width: 54px;" v-if="user">
                  <div style="font-size: 14px;margin-top: 18px;">
                    <b-row>
                      <b-col>
                        <div class="media-title font-weight-semibold">{{user.username}}</div>
                      </b-col>
                    </b-row>
                    <b-row>
                      <b-col style="font-size:13px;min-width: 112px;" @click="$router.push('/payments')">
                        <span>{{user.balance}}&nbsp;<i class="icon-plus-circle2 color-green font-size-sm text-green"></i></span>
                      </b-col>
                    </b-row>
                  </div>
                </b-col>
                <b-col sm style="max-width: 54px;">
                  <ul style="list-style: none;">
                    <li>
                      <nuxt-link to="/profile">
                        <i class="icon-cog3"></i>
                      </nuxt-link>
                    </li>
                    <li>
                      <a href="#" @click="logout">
                        <i class="icon-exit3"></i>
                      </a>
                    </li>
                  </ul>
                </b-col>
              </b-row>
            </b-col>
        </div>
      </div>
    </div>
    <div class="card">
      <div class="card-header bg-transparent header-elements-inline">
        <span class="text-uppercase font-size-sm font-weight-semibold">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <div class="header-elements">
          <div class="list-icons">
            <button class="navbar-toggler sidebar-mobile-secondary-toggle" @click="sidebarChanger()" type="button">
              <i class="icon-transmission"></i>
            </button>
          </div>
        </div>
      </div>

      <div class="card-body">
        <b-form-group
            id="fieldset-1"
            :description="'Ваш id для переводов баллов:'+user.id"
            label="Реферальная ссылка">
        <b-form-input type="text" disabled :value="'https://ytuber.ru/refferal/'+user.id"></b-form-input>
        </b-form-group>
      </div>
    </div>
    <div class="card">
      <div class="card-header bg-transparent header-elements-inline">
        <span class="text-uppercase font-size-sm font-weight-semibold">Меню</span>
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col">
            <nuxt-link to="/dashboard" type="button" class="btn bg-indigo-400 btn-block btn-float"
                       style="min-height: 90px; margin-top: 5px;">
              <i class="icon-home icon-2x"></i>
              <span>Главная</span>
            </nuxt-link>

            <nuxt-link to="/task/list" type="button" class="btn bg-green-800 btn-block btn-float"
                       style="min-height: 90px; margin-top: 5px;">
              <i class="icon-git-branch icon-2x"></i>
              <span>Мои задачи</span>
            </nuxt-link>

          </div>

          <div class="col">
            <nuxt-link to="/profile" type="button" class="btn bg-orange-400 btn-block btn-float"
                       style="min-height: 90px; margin-top: 5px;">
              <i class="icon-stats-bars icon-2x"></i>
              <span>Профиль</span>
            </nuxt-link>

            <nuxt-link to="/work/list" type="button" class="btn bg-warning-600 btn-block btn-float"
                       style="min-height: 90px; margin-top: 5px;">
              <i class="icon-collaboration icon-2x"></i>
              <span>Все задачи</span>
            </nuxt-link>
          </div>
        </div>
      </div>
    </div>
    <div class="card" v-if="$store.state.ytuber.accounts.data && $store.state.ytuber.accounts.data.length>0">
      <div class="card-header bg-transparent header-elements-inline">
        <span class="text-uppercase font-size-sm font-weight-semibold">Выберите активный канал</span>
        <div class="header-elements">
          <div class="list-icons">
            <a class="list-icons-item" data-action="collapse"></a>
          </div>
        </div>
      </div>

      <div class="card-body">
        <AccountChooser></AccountChooser>
      </div>
    </div>

  </div>

</template>
<script>
  import axios from 'axios';

  export default {
    data() {
      return {
        //user: [],
        keywords: [],
        site:process.env.site
      }
    },
    mounted() {
      //this.isAuth = localStorage.getItem('auth');
     // this.getUser();
     // this.getBalance();
    },
    computed:{
      user(){
        return this.$store.state.ytuber.user;
      }
    },
    methods: {
      fixImgPath(url){
        if(url){
          let img = url.replace('https://www.youtube.com/channel/',this.site+'/images/');
          return img+'.jpg';
        }
      },
      logout() {
        this.$axios.$post(process.env.site+'/api/authlogout').then(res => {
            //this.$route.push('/');
            window.location.href = '/';
            this.$store.dispatch('auth/loggedIn',false);
          }
        );
      },
      getBalance() {
        this.$root.$on('user', (current_user) => {
          this.user = current_user;
        });
      },
      getUser() {
      },
      sidebarChanger(){
        this.$nuxt.$emit('sidebarClose2');
      }

    },
    watch: {
      keywords: function () {
        this.$root.$emit('tasksearch', this.keywords);
      }
    },
    computed:{
      user(){
        return this.$store.state.ytuber.user
      }
    }
  }
</script>
