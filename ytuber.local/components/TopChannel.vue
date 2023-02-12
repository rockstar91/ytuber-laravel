<template>
  <div>
    <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
      <template #header>
        <h3><i class="icon-trophy2 mr-3 icon-2x"></i>Лидер сайта</h3>
      </template>
      <b-card-text>
        <div class="text-center" style="padding: 2px 2px 4px 2px;min-height: 170px;">
          <h3>Феодор Кранштадцкий</h3>
          <a href="#" class="mr-3"><img src="http://localhost/images/UCjjYs6OPD1O53ZyW8qBiESA.jpg" alt="" width="88" height="88"></a>
        </div>
      </b-card-text>
    </b-card>
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
/*    this.$echo.channel('CompleteForPublic').listen('CompleteForPublic',({message})=>{
      this.whoCompleteNow.push(message);
      if(this.whoCompleteNow.length>10){
        this.whoCompleteNow = [];
      }
    })*/
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