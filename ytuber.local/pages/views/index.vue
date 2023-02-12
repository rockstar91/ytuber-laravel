<template>
  <div>
    <div class="content">
     <YtPlayer></YtPlayer>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
    }
  },
  mounted() {
    let metadata ={
      title:'Просмотры роликов',
      description:'Смотрите ролики зарабатывайте баллы'
    };
    this.$store.dispatch('data/setMeta',metadata);
  },
  methods: {
  },
  watch: {
    /*      '$route.params.id'(newId, oldId) {
            this.getArticle(newId)
          }*/
  },
  components: {
    //'Pagination': require('laravel-vue-pagination'),
  }
}
</script>
<style>
</style>
