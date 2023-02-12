<template>
  <div>
    <div class="content">
      <b-card :title="title">
        <div v-html="html"></div>
      </b-card>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        Loading: true,
        title: '',
        html: '',
      }
    },
    props: {
      id: {
        type: Number,
        default: 0
      },
    },
    mounted() {
      let metadata ={
        title:'FAQ',
        description:'часто задаваемые вопросы'
      };
      this.$store.dispatch('data/setMeta',metadata);
      this.getArticle(1);
    },
    methods: {
      getArticle(id)
      {
        this.$root.$emit('isLoading', true);

        this.$axios.$get('/api/article/'+id)
          .then((res) => {

            this.title = res.name;
            this.html = res.html;

            // выключаем лоадер
            this.$root.$emit('isLoading', false);
          })
          .catch((err) => {
            console.log(err);
          });
      }
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
