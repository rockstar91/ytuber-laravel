<template>
  <div class="m2" style="padding:6px">
    <div class="card-body">
      <div class="form-group row">
        <div class="col-lg-10">
          <label v-if="!moderateData">Введите ссылку на ваш канал...</label>
          <div class="form-group form-group-feedback form-group-feedback-left">
            <input @change="ModerateChannel" v-model="url" type="text" class="form-control input-xlg"
                   placeholder="https://youtube.com/channel/id">
            <div class="form-control-feedback">
              <i class="icon-youtube3 2x"></i>
            </div>
          </div>
          <div v-if="channel" class="form-group form-group-feedback form-group-feedback-left">
            <h4>{{channel.snippet.title}}</h4>
            <img class="img-fluid img-thumb" :src="channel.snippet.thumbnails.default.url"/>
            <small>{{channel.snippet.description}}</small>
            </div>
        </div>
        <div class="col-lg-10 mt-4">
          <b-button variant="primary" @click="AddChannelByGoogle">Или добавить через &nbsp;<i class="icon-youtube"> </i></b-button>
        </div>
      </div>
<!--      <button class="btn btn-primary" @click="AddPage">Добавить аккаунт<i
          class="icon-arrow-right14 position-right"></i></button>-->
      <button class="btn btn-primary" v-if="moderateData && commentOpen" @click="CompleteModerate">Подтвердить<i
          class="icon-arrow-right14 position-right"></i></button>
      <button class="btn btn-danger" v-if="moderateData" @click="OtherComment">Другой коммент<i
          class="icon-cross position-right"></i></button>
      <div v-if="moderateData" class="mt-2">
        <span><b-button variant="outline-primary" class="mr-2"><a @click="commentOpen = true" :href="moderateData.url" target="_blank">Перейдите по ссылке <i
            class="icon-arrow-right14 position-right"></i></a> </b-button> Оставьте любой ОТВЕТ на прикрепленный комментарий, <b v-if="moderateData.data && moderateData.data.topLevelComment">"{{moderateData.data.topLevelComment.snippet.textDisplay}}"</b>, затем нажмите подтвердить.</span>
        <br />
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      url:null,
      channel:null,
      moderateData:null,
      commentOpen:false,
    }
  },
  methods: {
    OtherComment(){
      this.$axios.$post('/api/account/get-moderate-again',{url:this.url}).then(res=>{
        if(res.data =="moderation deleted" || res=="moderation deleted"){
          this.ModerateChannel();
        }
        this.$root.$emit('isLoading',false);
      }).catch(e=>{
        this.$root.$emit('isLoading',false);
      });
    },
    async CompleteModerate(){
      this.$root.$emit('isLoading',true);
     await this.$axios.get('/api/account/complete-moderate/'+this.moderateData.id).then(res=>{
       if(res.data=='success'){
         this.$toasted.show('Вы успешно добавили канал', {
           'type': 'success',
           'duration': 5000
         });
         this.$root.$emit('isLoading',true);
         this.$store.dispatch('ytuber/getAccounts').then(()=>{
           this.$root.$emit('isLoading',false);
         }).catch(e=>{
           this.$root.$emit('isLoading',false);
         });
       }
       console.log(res);
       if(res.data=='not founded'){
         this.$toasted.show('Ответ к комментарию не найден', {
           'type': 'error',
           'duration': 5000
         });
       }
       this.$root.$emit('isLoading',false);
     })
    },
    ModerateChannel(){
      this.$root.$emit('isLoading',true);
      if(this.url.length>=50){
      this.$axios.$post('/api/account/get-moderate',{url:this.url}).then(res=>{
        this.moderateData = res;
        this.$root.$emit('isLoading',false);
      }).catch(e=>{
        this.$root.$emit('isLoading',false);
      });
      }
    },
    AddChannelByGoogle(){
      this.$root.$emit('isLoading',true);
      this.$axios.$get('/api/account/get-moderate-google').then(res=>{
        //this.channel = res;
        console.log(res);
        this.$root.$emit('isLoading',false);
      }).catch(e=>{
        this.$root.$emit('isLoading',false);
      });
    },
    AddPage(){
      this.$root.$emit('isLoading',true);
    this.$axios.$post('/api/get-сhannel-url',{url:this.url}).then(res=>{
      this.channel = res;
      this.$root.$emit('isLoading',false);
    }).catch(e=>{
      this.$root.$emit('isLoading',false);
    });
    },
    accountBg(a){
      if(a==1){
        return "bg-success-300"
      }
      else{
        return ""
      }
    }
  },
  mounted() {
  },
  watch: {
  },
  components: {
    //'VueYoutube': require('vue-youtube-embed'),
    //'BootstrapVue': require('bootstrap-vue'),
  }
}
</script>
