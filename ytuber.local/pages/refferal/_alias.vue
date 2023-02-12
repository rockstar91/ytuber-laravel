<template>
  <div>
  </div>
</template>

<script>
export default {
  auth:false,
  middleware: 'guest',
  layout:'guest',
  data() {
    return {
      Loading: true,
    }
  },
  mounted() {
    this.getRefferal()
  },
  methods: {
    getRefferal() {
      console.log(this.$route.params.alias);
      localStorage.setItem('refferal', this.$route.params.alias);
      setInterval(() => {
        this.$router.push('/registration');
      }, 1000);

    }
  },
  watch: {
  },
  components: {
  }
}
</script>
<style>
</style>
