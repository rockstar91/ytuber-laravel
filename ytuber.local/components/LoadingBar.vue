<template>
  <div class="loading-overlay" v-if="loading">
  <div class="loading-page">
    <EllipsisLoader/>
  </div>
  </div>
</template>
<script>
  export default {
    data: () => ({
      loading: false,
    }),
    mounted() {
      this.$root.$on('isLoading',(param)=>{
        if(param){
          this.loading = true
        }
        else{
          this.loading = false
        }
      })
    },
    methods: {
      start() {
        this.loading = true
      },
      finish() {
        this.loading = false
      },
    },
  }
</script>
<style scoped>
  .loading-overlay{
    position: absolute;
    top: 50%;
    left: 50%;
    margin-right: -50%;
    transform: translate(-50%, -50%);
    z-index: 100;
    background: #000;
    opacity: 50%;
  }
  .loading-page {
    position: absolute;
    top: 50%;
    right: 50%;
    z-index: 1000;
    padding: 1rem;
    text-align: center;
    font-size: 3rem;
    font-family: sans-serif;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .loading {
    display: inline-block;
    width: 1.5rem;
    height: 1.5rem;
    border: 4px solid rgba(9, 133, 81, 0.705);
    border-radius: 50%;
    border-top-color: #158876;
    animation: spin 1s ease-in-out infinite;
  }
  @keyframes spin {
    to {
      -webkit-transform: rotate(360deg);
    }
  }
</style>
