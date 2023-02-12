<template>
  <b-card>
    <h2>Разгадайте капчу</h2>
      <div id="v2-normal"
        @error="onError"
        @success="onSuccess"
        @expired="onExpired"
    ></div>
    <br />
      <b-button @click="onSubmit">Отправить</b-button>
<!--      <b-button @click="onSubmit2">Отправить v3</b-button>-->
  </b-card>
</template>

<script>
  export default {
      data(){
        return{
          recaptcha_site_key:process.env.recaptcha_site_key,
          site:process.env.site
        }
      },
    async mounted() {
      await this.$recaptcha.init()
      this.widgetId = this.$recaptcha.render('v2-normal', {
        sitekey: this.recaptcha_site_key
      })
    },
    methods: {
      onError (error) {
        console.log('Error happened:', error)
      },
      async onSubmit() {
        try {
          const token = await this.$recaptcha.getResponse();
          await this.$axios.$post(this.site+'/api/captcha-validate',{'g-recaptcha-response':token}).then(res=>{
              console.log(res)
            if(res == "validated"){
              this.$router.push('/work/list')
            }
          });
          await this.$recaptcha.reset()
        } catch (error) {
          console.log('Login error:', error)
        }
      },
      async onSubmit2() {
        try {
          const tokenV2 = await this.$recaptcha.getResponse(this.widgetId)
          console.log('V2 ReCaptcha token:', tokenV2)
          const token = await this.$recaptcha.execute('login')
          console.log('V3 ReCaptcha token:', token)
          this.$recaptcha.reset(this.widgetId)
        } catch (error) {
          console.log('Login error:', error)
        }
      },
      onSuccess (token) {
        console.log('Succeeded:', token)
      },
      onExpired () {
        console.log('Expired')
      }
    },
  }
</script>
