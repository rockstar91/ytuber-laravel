<template>
  <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
    <template #header>
      <h3><i class="icon-clipboard2 icon-2x mr-2"></i>Уведомления</h3>
    </template>
    <b-card-text>
        <b-list-group>
          <b-list-group-item class="d-flex justify-content-between" v-for="(l,i) in latestCompleted" :key="i" v-html="taskType(l.task_type.name,l.cost,l.created_at)">
          </b-list-group-item>
        </b-list-group>
    </b-card-text>
  </b-card>
</template>
<script>
  export default {
    data() {
      return {
        // news:{}
      }
    },
    mounted() {
      this.getLatestCompleted();
    },
    methods: {
      getLatestCompleted(){
        this.$store.dispatch('ytuber/getLatesCompleted')
      },
      taskType(type,cost,date){
        if(type == "YT Лайки"){
          return '<span class="text-success"><i class="text-dark icon-thumbs-up2 mr-2 icon"></i>+'+cost+'</span> <span class="ml-2">Вы поставили лайк&nbsp</span><span class="float-right pull-right text-muted small">'+date+'</span>'
        }
        if(type == "YT Комментарии"){
          return '<span class="text-success"><i class="text-dark icon-user-check mr-2 icon"></i>+'+cost+'</span><span class="ml-2">Вы выполнили комментарий&nbsp</span><span class="float-right pull-right text-muted small">'+date+'</span>'
        }
        if(type == "YT Просмотры"){
          return '<span class="text-success"><i class="text-dark icon-youtube mr-2 icon"></i>+'+cost+'</span><span class="ml-2">Вы посмотрели ролик&nbsp</span><span class="float-right pull-right text-muted small">'+date+'</span>'
        }
        if(type == "YT Подписчики"){
          return '<span class="text-success"><i class="text-dark icon-user-check mr-2 icon"></i>+'+cost+'</span><span class="ml-2">Вы подписали на канал&nbsp</span><span class="float-right pull-right text-muted small">'+date+'</span>'
        }
        if(type == "YT Лайки к комментам"){
          return '<span class="text-success"><i class="text-dark icon-bubbles10 mr-2 icon"></i>+'+cost+'</span><span class="ml-2">Вы выполнили лайк комментария&nbsp</span><span class="float-right pull-right text-muted small">'+date+'</span>'
        }
        if(type == "VK Поделиться "){
          return 'Вы выполнили поделились роликом в vk'
        }
        if(type == "Твитнуть"){
          return 'Вы выполнили twitter'
        }
        if(type == "YT Ответы к комментариям"){
          return '<span class="text-success"><i class="text-dark icon-user-check mr-2 icon"></i>+'+cost+'</span><span class="ml-2">Вы выполнили ответ к комментарию&nbsp</span><span class="float-right pull-right text-muted small">'+date+'</span>'
        }
      }
    },
    computed: {
      latestCompleted(){
        return this.$store.state.ytuber.dashboard.completions
      }
    },
  }
</script>
