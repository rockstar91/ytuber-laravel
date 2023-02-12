<template>
  <div style="min-width:340px">
    <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light"
            class="text-center" v-if="agreeVideoBtn">
      <template #header>
        <h3><i class="icon-youtube2 mr-3 icon-2x"></i>Просмотр роликов</h3>
        <small>Смотрите ролики зарабатывайте баллы</small>
      </template>
      <b-card-text>
        <button variant="outline-primary" @click="play">
          <h1>Нажмите чтобы запустить просмотры</h1>
          <i class="icon-play3 icon-4x" style="font-size: 240px;"></i>
        </button>
        <b-button variant="outline-primary" @click="videoSize" class="float-left">
          <i class="icon-screen-full 2x"></i>
        </b-button>
      </b-card-text>
    </b-card>

    <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light"
            class="text-center" v-if="!agreeVideoBtn">
      <div class="d-flex-row mb-2" v-if="progressbar">
        <b-progress ref="limit" :value="time" variant="success" :max="maxtime" striped :animated="animate"
                    class="mt-2"></b-progress>
      </div>
      <template #header>
        <h3><i class="icon-youtube2 mr-3 icon-2x"></i>Просмотр роликов</h3>
        <small>Смотрите ролики зарабатывайте баллы</small>
      </template>
      <b-card-text>
        <div>
          <h3 v-if="videoName">{{videoName}}</h3>
          <youtube ref="videoplayer" @playing="playing" @ready="ready" @paused="paused" :video-id="videoId"
                   @change="change"
                   player-width="100%"
                   :player-height="playerHeight" :player-vars="{autoplay: 1}"></youtube>
        </div>
        <b-card-text>
          Осталось выполнить {{ maxtime - time }}
        </b-card-text>
        <b-button variant="outline-primary" @click="videoSize" class="float-left">
          <i class="icon-screen-full 2x"></i>
        </b-button>
        <b-button variant="outline-success" @click="play">
          <i class="icon-play 2x"></i> Запустить
        </b-button>
        <b-button variant="outline-warning" @click="stop">
          <i class="icon-pause 2x"></i>  Пауза
        </b-button>
      </b-card-text>
    </b-card>
  </div>
</template>
<script>
export default {
  data() {
    return {
      videoName:null,
      hash:process.env.hash,
      playCount:0,
      agreeVideoBtn: true,
      videoId: '',
      video: '',
      isRunning: false,
      playingVid: false,
      interval: null,
      time: 0,
      maxtime: 15,
      animate: '',
      progressbar: 'true',
    }
  },
  methods: {
    videoSize(){
      this.$store.dispatch('ytuber/setVideoSize');
    },
    hashMake() {
      if(this.user.active_account && this.user.active_account.id){
        let y = this.$moment.utc().format('YYYY');
        let m = this.$moment.utc().format('MM');
        let d = this.$moment.utc().format('DD');

        let plain_text = m+'#'+this.user.id+"#"+d+"#"+y+'#'+this.video.id+'#'+this.user.active_account.id;
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
            this.$toast.success('<h1>Задача ' + res.name + ' успешно выполнена ' +
                '<img class="rounded float-left mr-2" ' +
                'src="https://img.youtube.com/vi/' + this.parseVideoIdFromUrl(res.url) + '/default.jpg"' + 'width="120" ' + 'height="90"' + ' />' +
                '</br> На баланс зачислено ' + res.points + ' баллов</h1>',
                {
                  duration: 3000
                }
            );
          }
          this.$store.dispatch('ytuber/getUser');
        });
      }
      else{
        this.$toast.info('Чтобы заработать баллы с просмотров, сначала добавьте канал',
            {duration: 3000}
        );
      }

    },
    async getVideo() {
      this.$store.dispatch('ytuber/getWatchVideo');
    },
    play() {
      if(this.videos.length == 0){
        this.$toast.error('В данный момент нет заданий для просмотра, попробуйте позже!',
            {duration: 3000}
        );
        this.stop();
        return "no video";
      }
      this.agreeVideoBtn = false;
      let numToPlay = this.randomInteger(0, this.videos.length - 1);
      this.videoId = this.parseVideoIdFromUrl(this.videos[numToPlay].url);
      this.video = this.videos[numToPlay];
      this.videoName = this.videos[numToPlay].name;
      this.maxtime = this.videos[numToPlay].targetTime;
      this.time = 0;
      this.playingVid = true;
      this.progressbar = true;
      this.playCount++;
      clearInterval(this.interval);
      this.toggleTimer();
    },
    stop() {
      this.$refs.videoplayer.player.stopVideo();
      clearInterval(this.interval);
      this.isRunning = false;
      this.toggleTimer();
      this.time = 0;
    },
    randomInteger(min, max) {
      return Math.floor(Math.random() * (max - min + 1)) + min;
    },
    ready(event) {
      this.player = event.player;
    },
    paused(event) {
      clearInterval(this.interval);
      this.isRunning = false;
      this.toggleTimer();
      this.time = 0;
    },
    change() {
      // console.log('changing...');
    },
    playing(event) {
      this.toggleTimer();
      this.isRunning = true;
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
    this.getVideo();
  },
  computed: {
    videos() {
      return this.$store.state.ytuber.watchClient
    },
    user() {
      return this.$store.state.ytuber.user
    },
    playerHeight(){
      if(this.$store.state.ytuber.videoSize == "25%"){
        return "320px";
      }
      else{
        return "640px";
      }
    }
  },
  watch: {
    time: function () {
      if (this.time >= this.maxtime && this.playCount>0) {
        this.hashMake();
        if(this.playCount>4){
          this.getVideo();
        }
        this.play();
      }
    },
  },
  components: {
    //'VueYoutube': require('vue-youtube-embed'),
    //'BootstrapVue': require('bootstrap-vue'),
  }
}
</script>
