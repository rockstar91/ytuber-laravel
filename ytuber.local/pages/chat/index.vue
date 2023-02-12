<template>
  <div>
    <div class="content">
    <YtuberChat/>
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
      title:'Онлайн общение для наших пользователей',
      description:'Чат онлайн'
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
