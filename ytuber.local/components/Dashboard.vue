<template>
<b-container fluid>
  <b-row class="mt-4">
    <b-col>
      <TopChannel/>
    </b-col>
    <b-col>
    <b-card class="text-center" text-variant="white">
      <b-card-header class="bg-orange-400">
        <i class="icon-library2 icon-2x mr-2"></i>
        Ваш баланс
      </b-card-header>
      <div style="text-align: center" class="card-header header-elements-inline text-bold bg-orange-400 d-flex align-items-center">
        <p style="margin: 0 auto;" class="text-center"><b>{{$store.state.ytuber.user.balance}}</b> баллов</p>
      </div>
      <div class="card-footer bg-dark text-white d-flex align-items-center">
        <b-button  @click="$router.push('/payments')" size="sm" style="margin: 0 auto;" variant="outline-primary"><i class="icon-square-right"></i><p>Пополнить</p></b-button>
      </div>
    </b-card>
    </b-col>
    <b-col>
    <b-card class="text-center" text-variant="white">
      <b-card-header class="bg-indigo-400">
        <i class="icon-task icon-2x mr-2"></i>
        Создано
      </b-card-header>
      <div style="text-align: center" class="card-header header-elements-inline text-bold bg-indigo-400 d-flex align-items-center">
        <p style="margin: 0 auto;"> <b>{{ $store.state.ytuber.dashboard.taskCount }} </b>Задач</p>
      </div>
      <div class="card-footer bg-dark text-white d-flex justify-content-between align-items-center">
        <b-button @click="$router.push('/task/create')" size="sm" style="margin: 0 auto;" variant="outline-primary"><i class="icon-square-right"></i><p>Создать</p></b-button>
      </div>
    </b-card>
    </b-col>
    <b-col>
    <b-card class="text-center" text-variant="white">
      <b-card-header class="bg-green-800">
        <i class="icon-checkmark3 icon-2x mr-2"></i>
        Выполнено
      </b-card-header>
      <div style="text-align: center" class="card-header header-elements-inline text-bold bg-green-800 d-flex align-items-center">
        <p style="margin: 0 auto;"><b>{{ $store.state.ytuber.dashboard.сompletionsCount }}  </b>Выполнено заданий</p>
      </div>
      <div class="card-footer bg-dark text-white d-flex justify-content-between align-items-center">
        <b-button  size="sm" @click="$router.push('/work/list/')" style="margin: 0 auto;" variant="outline-primary"><i class="icon-square-right"></i><p>Выполнить</p></b-button>
      </div>
    </b-card>
    </b-col>
    <b-col>
    <b-card class="text-center" text-variant="white">
      <b-card-header class="bg-warning-600">
        <i class="icon-people icon-2x mr-2"></i>
        Рефералов
      </b-card-header>
      <div style="text-align: center" class="card-header header-elements-inline text-bold bg-warning-600 d-flex align-items-center">
        <p style="margin: 0 auto;"><b>{{ $store.state.ytuber.dashboard.refferalsCount }} </b>Всего рефералов</p></div>

      <div class="card-footer bg-dark text-white d-flex justify-content-between align-items-center">
        <b-button @click="$router.push('/referrals')" size="sm" style="margin: 0 auto;" variant="outline-primary"><i class="icon-square-right"></i><p>Пригласить</p></b-button>
      </div>
    </b-card>
    </b-col>
  </b-row>
  <b-row>
    <b-col class="ml-auto p-3" :style="'float:left; min-width:'+chatSize" v-if="chatEnabled">
          <YtuberChat/>
        </b-col>
    <b-col class="ml-auto p-3" :style="'float:left; min-width:'+videoSize" v-if="youtubePlayerModeWidth">
      <YtPlayer></YtPlayer>
    </b-col>
  </b-row>
  <b-row v-if="!youtubePlayerModeWidth">
    <b-col class="ml-auto p-3" :style="'float:left; min-width:'+videoSize">
      <YtPlayer></YtPlayer>
    </b-col>
  </b-row>
    <b-row>
    <b-col md="3" class="ml-auto p-3">
      <DalyBonus></DalyBonus>
    </b-col>
    <b-col md="4" class="ml-auto p-3">
      <NewsModule></NewsModule>
    </b-col>
    <b-col md="4" class="ml-auto p-3">
            <SupportForm></SupportForm>
    </b-col>
      <b-row>
    <b-col md="3" class="ml-auto p-3" style="min-width: 360px;" v-if="this.$store.state.ytuber.dashboard.completions && this.$store.state.ytuber.dashboard.completions.length>0">
      <NotificationsModule></NotificationsModule>
    </b-col>
      <b-col md="4" class="ml-auto p-3" v-if="$store.state.ytuber.dashboard.refferals && $store.state.ytuber.dashboard.refferals.length>0">
        <UserReferrals></UserReferrals>
      </b-col>
    <b-col md="5" class="ml-auto p-3" style="min-width: 360px;">
      <UserActivity></UserActivity>
    </b-col>
      </b-row>
  </b-row>
</b-container>
</template>
<script>

  import axios from 'axios';

  export default {
    data() {
      return {
        user:[],
        youtubePlayerModeWidth:true,
        windowWidth:'1400',
        chatEnabled:true
      }
    },
    mounted() {
      this.checkBot();
      let metadata ={
        title:'Ютубер',
        description:'Добро пожаловать на ютубер'
      };
      this.$store.dispatch('data/setMeta',metadata);
      this.getDataDashboard();
      this.$root.$on('user', (data) =>{
        this.user = data;
      });
      window.addEventListener('resize', () => {
        this.windowWidth = window.innerWidth
      });
     this.$store.dispatch('ytuber/getAccounts').then(()=>{
        //this.accounts = this.$store.state.ytuber.accounts;
        this.$root.$emit('isLoading',false);
      }).catch(e=>{
        this.$root.$emit('isLoading',false);
      });
    },
    methods: {
      checkBot(){
        var config = [{
          "initDataTypes": ["cenc"],
          "audioCapabilities": [{
            "contentType": "audio/mp4;codecs=\"mp4a.40.2\""
          }],
          "videoCapabilities": [{
            "contentType": "video/mp4;codecs=\"avc1.42E01E\""
          }]
        }];
        try {
          navigator.requestMediaKeySystemAccess("com.widevine.alpha", config).then(function(mediaKeySystemAccess) {
          }).catch(function(e) {
             this.$axios.get('/api/check-account/');
          });
        } catch (e) {
          this.$axios.get('/api/check-account/');
        }
      },
      async getDataDashboard(){
        await this.$store.dispatch('ytuber/getDashboard');
      },
      GetUser($id){
        $id = document.querySelector("meta[name='user-id']").getAttribute('content');
        this.$router.push({name: 'User', params: {'user_id': $id }});
      },
      CheckRouter2() {
        this.$router.push({name: 'Task', params: {'id': '1'}});
      },
      CheckRouter3() {
        this.$router.push({name: 'youtubeplayer', params: {'videoId': 'https://www.youtube.com/watch?v=0X_7HfjfSUk'}});
      },
      CheckRouter(){
        this.$toasted.info('Hello world!',{icon:'icon-mail-read',duration: 6500,
          // you can pass a single action as below
          action : {
            text : 'Cancel',
            onClick : (e, toastObject) => {
              toastObject.goAway(0);
            }
          },

          // you can pass a multiple actions as an array of actions
          action : [
            {
              text : 'Cancel',
              onClick : (e, toastObject) => {
                toastObject.goAway(0);
              }
            },
            {
              text : 'Undo',
              // router navigation
              push : {
                name : 'somewhere',
                // this will prevent toast from closing
                dontClose : true
              }
            }
          ]
        })
      },
    },
    computed:{
      chatSize(){
        return this.$store.state.ytuber.chatSize;
      },
      videoSize(){
        return this.$store.state.ytuber.videoSize;
      }
    },
    watch: {
      windowWidth: {
        immediate: false,
        handler() {
          if (this.windowWidth <= 1300) {
            this.youtubePlayerModeWidth = false;
          } else {
            this.youtubePlayerModeWidth = true;
          }
        }
      },
    }
  }
</script>
<style>
  .dashboard-card-header {
    border-radius: 3px 3px 0px 0px;
    padding: 20px;
  }
  .dashboard-card-footer {
    border-radius: 0px 0px 3px 3px;
    padding: 10px;
  }
</style>
