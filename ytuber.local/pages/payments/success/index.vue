<template>
  <div class="content">
    <b-card class="text-center">
      <b-img src="/success-payment.png" />
    </b-card>
  </div>
</template>
<script>
export default {
  data() {
    return {
      redirectTimer:false,
    }
  },
  created(){
  },
  mounted() {
    this.redirectPayments();
  },
  methods:{
    redirectPayments(){
      this.redirectTimer = setInterval(() => {
        this.$router.push('/payments');
        clearTimeout(this.redirectTimer);
      }, 7000);
    }
  }
}
</script>
