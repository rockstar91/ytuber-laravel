<template>
  <div v-click-outside="closeDropdowns">
    <div class="sidebar-mobile-toggler text-center">
      <a @click="sidebarChangerMobile">
        <i class="icon-arrow-left8"></i>
      </a>
      Навигация по сайту
      <a class="sidebar-mobile-expand" @click="sidebarFullScreen">
        <i class="icon-screen-full"></i>
        <i class="icon-screen-normal"></i>
      </a>
    </div>

    <div class="sidebar-content" v-bind:style="fullScreenSidebarStyle" v-if="user">
      <div class="card card-sidebar-mobile">
        <ul class="nav nav-sidebar" data-nav-type="accordion">

          <li class="nav-item-header">
            <div class="text-uppercase font-size-xs line-height-xs">{{$t('siderbar.main')}}</div>
            <i class="icon-menu" title="Main"></i></li>
          <li class="nav-item">
            <NuxtLink to="/" class="nav-link" v-b-tooltip.hover :title="$t('siderbar.mainpanel')">
              <i class="icon-home4"></i>
              <span>
									{{$t('siderbar.mainpanel')}}
								</span>
            </NuxtLink>
          </li>
          <li class="nav-item nav-item-submenu"  @click="switchMyTaskMenu" v-b-tooltip.hover :title="$t('siderbar.mytasks')">
            <a href="#" class="nav-link">
              <i class="icon-list3"></i>
              <span>{{$t('siderbar.mytasks')}}</span></a>
            <ul class="nav nav-group-sub" data-submenu-title="Мои Задачи" v-bind:style="myTaskMenu">
              <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.list-tasks')">
                <NuxtLink :to="{ path: '/task/list'}" class="nav-link">
                  {{$t('siderbar.list-tasks')}}
                </NuxtLink>
              </li>
              <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.add-task')">
                <NuxtLink :to="{ path: '/task/create'}" class="nav-link">
                  {{$t('siderbar.add-task')}}
                </NuxtLink>
              </li>
            </ul>
          </li>
          <li class="nav-item nav-item-submenu" @click="switchCompleteTaskMenu" v-b-tooltip.hover :title="$t('siderbar.complete-task')">
            <a to="#" href="#" class="nav-link">
              <i class="icon-compose"></i>
              <span>{{$t('siderbar.complete-task')}}</span>
            </a>

            <ul class="nav nav-group-sub" data-submenu-title="Выполнять задачи" v-bind:style="completeTaskMenu">
              <li class="nav-item" v-for="work in $store.state.ytuber.taskTypes" v-b-tooltip.hover :title="$t(work.name)">
                <a href="#" @click="WorkMenuClick(work)" class="nav-link">{{$t(work.name)}}</a>
              </li>
            </ul>
          </li>
          <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.my-refferals')">
            <NuxtLink class="nav-link" :to="{ path: '/referrals'}">
              <i class="icon-users4"></i>
              <span>{{$t('siderbar.my-refferals')}}</span>
            </NuxtLink>
          </li>
          <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.transactions')">
            <NuxtLink class="nav-link" :to="{ path: '/transactions'}">
              <i class="icon-transmission"></i>
              <span>{{$t('siderbar.transactions')}}</span>
            </NuxtLink>
          </li>
          <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.payments')">
            <NuxtLink class="nav-link" :to="{ path: '/payments/'}">
              <i class="icon-coins"></i>
              <span>{{$t('siderbar.payments')}}</span>
            </NuxtLink>
          </li>
          <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.faq')">
            <NuxtLink class="nav-link" :to="{ path: '/faq', params: {id: '1' }}">
              <i class="icon-help"></i>
              <span>{{$t('siderbar.faq')}}</span>
            </NuxtLink>
          </li>
<!--          <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.api')">
            <NuxtLink class="nav-link" :to="{ path: '/api'}">
              <i class="icon-sphere3"></i>
              <span>{{$t('siderbar.api')}}</span>
            </NuxtLink>
          </li>-->
          <li class="nav-item" v-b-tooltip.hover :title="$t('siderbar.rules')">
            <NuxtLink class="nav-link" :to="{ path: '/rules', params: {id: '2' }}">
              <i class="icon-alert"></i>
              <span>{{$t('siderbar.rules')}}</span>
            </NuxtLink>
          </li>
          <li class="nav-item" v-if="user.admin == 1" v-b-tooltip.hover :title="$t('siderbar.admin-panel')">
            <a href="http://api.ytuber.ru/nova" class="nav-link">
              <i class="icon-lock"></i>
              <span>{{$t('siderbar.admin-panel')}}</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

  </div>
</template>
<script>
  import axios from 'axios';

  export default {
    mounted() {
      this.$root.$on('sidebarClose', ()=>{
        this.closeDropdowns();
      });
      this.$root.$on('sidebarMobileClose', ()=>{
        this.sidebarChangerMobile();
      });
    },
    data() {
      return {
        joined_avatar: '/uploads/avatars/placeholder.jpg',
        id: '',
        loading: false,
        myTaskMenu:{
          display:'none'
        },
        completeTaskMenu:{
          display:'none'
        },
        fullScreenSidebarStyle:{
          width:'auto',
        }
      }
    },

    methods: {
      sidebarFullScreen(){
        $(document).ready(function () {
          $('.sidebar-main').toggleClass('sidebar-fullscreen');
        });
        if(this.fullScreenSidebarStyle.width == '100%'){
          this.fullScreenSidebarStyle.width = 'auto';
        }
        else{
          this.fullScreenSidebarStyle.width = '100%';
        }
      },
      async WorkMenuClick(work){
        let settings = {
          'task_type': work.id,
          'page': 1,
        };
        this.$router.push('/work/list');
        await this.$store.dispatch('ytuber/getWork',settings);
      },
      sidebarChangerMobile(){
        $(document).ready(function () {
          $('body').toggleClass('sidebar-xs').removeClass();
        });
        this.fullScreenSidebarStyle.width = 'auto';
      },
      closeDropdowns(){
        this.myTaskMenu.display = 'none';
        this.completeTaskMenu.display = 'none'
      },
      switchMyTaskMenu(){
        if(this.myTaskMenu.display == 'block'){
          this.myTaskMenu.display = 'none'
        }
        else{
          this.myTaskMenu.display = 'block'
        }
      },
      switchCompleteTaskMenu(){
        if(this.completeTaskMenu.display == 'block'){
          this.completeTaskMenu.display = 'none'
        }
        else{
          this.completeTaskMenu.display = 'block'
        }
      },
    },
    computed: {
      user: function(){
        return this.$store.state.ytuber.user;
      }
    }
  }
</script>
