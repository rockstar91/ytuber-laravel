<template>
  <b-container>
  <b-card class="text-center" body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
    <template #header>
      <h3><i class="icon-newspaper icon-2x mr-2"></i>Новости проекта</h3>
      <small class="text-center">последние новости проекта...</small>
    </template>
    <b-card-text>
      <div class="list-feed">
        <div class="list-feed-item text-left"  v-for="(n,i) in news" :key="i">
          <div>
          <span>{{$moment(n.date).format('Do MMMM YYYY')}}</span>
          <div v-html="n.text">
          </div>
          </div>
        </div>

      </div>
    </b-card-text>
  </b-card>
  </b-container>
</template>
<script>
  export default {
    data() {
      return {
       // news:{}
      }
    },
    mounted() {
      //this.form.email = this.$store.state.ytuber.user.email;
      //this.getNews();
    },
    methods: {
/*      getNews(){
      this.$store.dispatch('ytuber/getNews');
      }*/
    },
    computed: {
      news(){
        return this.$store.state.ytuber.dashboard.news
      }
    },
  }
</script>
