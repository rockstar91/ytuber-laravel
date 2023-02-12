<template>
  <div>
    <div class="d-flex-row" v-if="progressbar">
      <b-progress :value="time" variant="success" :max="maxtime" striped :animated="animate"
                  class="mt-2">

      </b-progress>
    </div>
    <div>
      <youtube @playing="playing" @ready="ready" @paused="paused" :video-id="videoId" @change="change"
               player-width="100%"
               player-height="750" :player-vars="{autoplay: 1}"></youtube>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      hash:process.env.hash,
      videoId: 'videoId',
      isRunning: false,
      interval: null,
      time: 0,
      maxtime:'maxtime',
      animate: '',
      progressbar: 'true',
    }
  },
  methods: {
    hashMake() {
      let y = this.$moment.utc().format('YYYY');
      let m = this.$moment.utc().format('MM');
      let d = this.$moment.utc().format('DD');

      let plain_text = m+'#'+this.user.id+"#"+d+"#"+y+'#'+this.$store.state.ytuber.task.id+'#'+this.user.active_account.id;
      let passphrase = this.hash;
      var eklemento = this.$CryptoJS.lib.WordArray.random(256);
      var deteshpek = this.$CryptoJS.lib.WordArray.random(16);

      var key = this.$CryptoJS.PBKDF2(passphrase, eklemento, { hasher: this.$CryptoJS.algo.SHA512, keySize: 64/8, iterations: 999 });
      var encrypted = this.$CryptoJS.AES.encrypt(plain_text, key, {iv: deteshpek});

      var data = {
        zalypytra : this.$CryptoJS.enc.Base64.stringify(encrypted.ciphertext),
        eklemento : this.$CryptoJS.enc.Hex.stringify(eklemento),
        deteshpek : this.$CryptoJS.enc.Hex.stringify(deteshpek)
      }

      this.$axios.$post('/api/get-watch-complete/', data).then(res => {
        if(res == "recaptcha"){
          this.$router.push('/captcha');
        }
        if(res.name && res.url){
          this.$toast.success('<h1>Задача '+res.name+' успешно выполнена ' +
              '<img class="rounded float-left mr-2" ' +
              'src="https://img.youtube.com/vi/'+this.parseVideoIdFromUrl(res.url)+'/default.jpg"' +'width="120" '+ 'height="90"'+ ' />'+
              '</br> На баланс зачислено ' + res.points + ' баллов</h1>',
              {
                duration: 3000
              }
          );
        }

        this.$store.dispatch('ytuber/getUser');
      });


    },
    StartTimer() {
      this.progressbar = true;
      //console.log('clicked');
    },
    ready(event) {
      this.player = event.player;
      this.progressbar = true;
    },
    paused(event) {
      clearInterval(this.interval);
      this.isRunning = false;
      this.progressbar = false;
      //console.log('paused video');
    },
    change() {
      // console.log('changing...');
    },
    playing(event) {
      this.isRunning = true;
      this.maxtime = this.$store.state.ytuber.task.targetTime;
      this.progressbar = true;
      this.time = 0;
      this.toggleTimer();
    },
    OpenTask() {
      this.videoId = this.parseVideoIdFromUrl(this.$store.state.ytuber.task.url);
      this.time = 0;
    },
    toggleTimer() {
      if (!this.isRunning) {
        clearInterval(this.interval);
      } else {
        this.interval = setInterval(this.incrementTime, 1000);
      }
      this.isRunning = !this.isRunning
    },
    incrementTime() {
      this.time = parseInt(this.time) + 1;
    },
    parseVideoIdFromUrl(url) {
      let vid = '';
      let pos = url.toString().indexOf('?v=');
      let poz = url.toString().indexOf('.be/');
      let pox = url.toString().indexOf('shorts/');

      if (url.toString().length == 11)
        vid = url;
      else {
        if (pos !== -1)
          vid = url.toString().substring(pos + 3, pos + 14)
        else if (poz !== -1)
          vid = url.toString().substring(poz + 4, poz + 15)
      }
      if(url.toString().includes('shorts/')){
        vid = url.toString().substring(pox + 7, url.length)
      }
      if (vid.length == 11)
        return vid;
      else
        return null;
    },
  },
  mounted() {
    //this.$gtm.trackView('MyScreenName', 'currentpath');
  },
  computed:{
    user(){
      return this.$store.state.ytuber.user
    },
    video(){
      return this.$store.state.ytuber.task
    }
  },
  watch: {
    time: function () {
      let dataTime = {
        time: this.time,
        max: this.maxtime,
      };
      this.$store.dispatch('ytuber/setYtPlayerTimeIncrement',dataTime);
      this.progressbar = true;
      //console.log(this.maxtime);
      if (this.$store.state.ytuber.task) {
        this.videoId = this.parseVideoIdFromUrl(this.$store.state.ytuber.task.url);
      }
      if (this.time >= this.maxtime) {
        console.log('completed task!');
        this.hashMake();
        this.toggleTimer();
        this.progressbar = false;

      }
    },
    '$store.state.ytuber.task': {
      handler: function () {
        this.isRunning = false;
        this.progressbar = true;
        this.time = 0;
        clearInterval(this.interval);
        this.$store.dispatch('ytuber/setYtPlayerTimeIncrement',0);
        this.toggleTimer();
        this.OpenTask();
      },
      deep: true,
      immediate: true
    },
  },
  components: {
    //'VueYoutube': require('vue-youtube-embed'),
    //'BootstrapVue': require('bootstrap-vue'),
  }
}
</script>
