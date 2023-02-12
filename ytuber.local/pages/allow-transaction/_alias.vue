<template>
  <div class="content">
    <b-card class="text-center">
      <h1 v-if="!!transaction">
      <span class="badge badge-flat badge-icon border-success text-success rounded-circle"><i class="icon-check icon-2x"></i></span> Вы успешно перевели {{transaction.sum}} баллов, пользователю {{transaction.recipient}}
      </h1>
    </b-card>
  </div>
</template>
<script>
export default {
  data() {
    return {
      completed: false,
      transaction:null,
      redirectTimer:false,
    }
  },
  created(){
  },
  mounted() {
    let metadata ={
      title:'Cтраница для одобрения перевода другому пользователю',
      description:'Подтверждение перевода'
    };
    this.$store.dispatch('data/setMeta',metadata);
    this.redirectTransactions();
  },
  methods:{
    redirectTransactions(){
      this.$axios.$get('/api/allow-transaction/'+this.$route.params.alias+'?hash='+this.$route.query.hash).then(res=>{
        console.log(res);
        if(res.status = 1) {
          this.transaction = res;
          this.redirectTimer = setInterval(() => {
            this.$router.push('/transactions');
            clearTimeout(this.redirectTimer);
          }, 7000);
        }
      })

    }
  }
}
</script>
