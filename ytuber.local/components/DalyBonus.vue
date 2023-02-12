<template>
  <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
    <template #header>
      <h3><i class="icon-gift mr-3 icon-2x"></i>Ежедневный бонус</h3>
      <small>Чтобы получить бонус выполните указанные задачи</small>
    </template>
    <b-card-text v-if="!competed_full">
      <div class="mb-2">
      <span>Посмотрите 45 видео</span>
      <b-progress show-value :value="views" variant="sucess" max="45"></b-progress>
      </div>
    <div class="mb-2">
      <span>Лайкните 15 видео</span>
      <b-progress show-value :value="likes" variant="info" max="15"></b-progress>
    </div>
      <div class="mb-2">
      <span>Подпишитесь на 3 канал</span>
      <b-progress show-value :value="subscribes" variant="warning" max="3"></b-progress>
    </div>
      <div class="mb-2">
      <span>Оставьте 5 комментариев</span>
      <b-progress show-value :value="comments" variant="danger" max="5"></b-progress>
    </div>
    </b-card-text>
    <b-card-text v-else class="text-center">
      <b-img src="/images/bonus.jpg" fluid alt="Fluid image"></b-img>
      <b-button class="mt-2" variant="outline-success" @click="getBonus">Получить бонус 1000 баллов</b-button>
    </b-card-text>
  </b-card>
</template>
<script>
  export default {
    data() {
      return {
        completed:[],
        likes:0,
        views:0,
        subscribes:0,
        comments:0,
        timer: null,
        competed_full:false,
      }
    },
    mounted() {
      this.getTodayCompleted();
    },
    methods:{
      getBonus(){
        this.$axios.$get('/api/get-daily-bonus').then(res=>{
          console.log(res);
          if(res =='not completed yet'){
            this.$toasted.show('Выполните условия бонуса для получения', {
              'type': 'error',
              'duration': 5000
            });
          }
          else if(res =='bonus already taken'){
            this.$toasted.show('Бонус уже полужен сегодня', {
              'type': 'error',
              'duration': 5000
            });
          }
          else if(res =='bonus taken'){
            this.$toasted.show('Вы получили бонус 1000 баллов', {
              'type': 'success',
              'duration': 5000
            });
          }
        });
      },
      checkCompleted(){
        if(this.likes>=15 && this.subscribes>=3 && this.comments>=5 && this.views>=45){
          this.competed_full = true;
        }
      },
      getTodayCompleted(){
        this.$axios.$get('/api/today-completed').then(res=>{
          this.completed = res;
          for(let key in this.completed){
            if(this.completed[key].task_type.name=="YT Лайки"){
              this.likes++;
            }
            if(this.completed[key].task_type.name=="YT Просмотры"){
              this.views++;
            }
            if(this.completed[key].task_type.name=="YT Подписчики"){
              this.subscribes++;
            }
            if(this.completed[key].task_type.name=="YT Комментарии"){
              this.comments++;
            }
          }
          this.checkCompleted();
        })
      }
    },
    beforeDestroy() {
      clearInterval(this.timer)
      this.timer = null
    }
  }
</script>
