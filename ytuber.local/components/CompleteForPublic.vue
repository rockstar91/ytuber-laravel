<template>
  <div>
    <div style="z-index: 2;" class="card text-center media mt-2 mr-1 p-2 float-left" v-for="(message,i) in this.whoCompleteNow" :key="i" v-if="whoCompleteNow && whoCompleteNow.length>0">

      <b-card-text>
        <a :href="message.yt_channel" target="_blank"><b-img :src="fixImgPath(message.yt_channel)" style="max-height: 44px"></b-img></a>
      </b-card-text>

      <small>{{message.text}}&nbsp <i :class="message.icon"></i></small>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      whoCompleteNow:[],
      site:process.env.site
    }
  },
  mounted() {
    this.$echo.channel('CompleteForPublic').listen('CompleteForPublic',({message})=>{
      this.whoCompleteNow.push(message);
      if(this.whoCompleteNow.length>14){
        this.whoCompleteNow = [];
      }
    })
  },
  methods: {
    fixImgPath(url){
      if(url){
        let img = url.replace('https://www.youtube.com/channel/',this.site+'/images/');
        return img+'.jpg';
      }
    },
  }
}
</script>