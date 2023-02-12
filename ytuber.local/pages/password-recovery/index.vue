<template>

  <div class="page-content">

    <div class="content-wrapper">

      <div class="content d-flex justify-content-center align-items-center">

        <!-- Registration form -->

          <div class="col-md-6">
            <div class="col-lg-6 offset-lg-3">
              <div class="card mb-0">
                <div class="card-body">
                  <div class="text-center mb-3">
                    <i class="icon-plus3 icon-2x text-success border-success border-3 rounded-round p-3 mb-3 mt-1"></i>
                    <h5 class="mb-0">Сбросить пароль от аккаунта</h5>
                    <span class="d-block text-muted">Введите email вашего аккаунта, на почту придет ссылка для сброса пароля.</span>
                  </div>

                  <div class="form-group form-group-feedback form-group-feedback-right">
                    <input v-model="form.email" type="text" class="form-control" placeholder="Ваш Email">
                    <div class="form-control-feedback">
                      <i class="icon-user-plus text-muted"></i>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                    <div class="mb-2" id="v2-normal"
                         @error="onError"
                         @success="onSuccess"
                         @expired="onExpired"
                    ></div>
                  </div>
                  </div>

                  <button @click="resetPassword" class="btn bg-teal-400 btn-labeled btn-labeled-right"><b><i class="icon-plus3"></i></b> Сбросить пароль</button>
                </div>
              </div>
            </div>
          </div>
        <!-- /registration form -->

      </div>
    </div>
  </div>
</template>

<script>
export default {
  auth:false,
  middleware: 'guest',
  layout:'guest',
  data: () => ({
    form: {
      email:null,
      recaptcha_site_key:process.env.recaptcha_site_key,
    },
  }),
  async mounted() {
    await this.$recaptcha.init()
    this.widgetId = this.$recaptcha.render('v2-normal', {
      sitekey: this.recaptcha_site_key
    });
    let metadata ={
      title:'Ютубер',
      description:'Добро пожаловать на ютубер'
    };
    this.$store.dispatch('data/setMeta',metadata);
  },
  methods:{
   async resetPassword(){
      const token = await this.$recaptcha.getResponse();
      if(!this.form.email){
        this.$toast.error("Поле E-Mail пользователя обязательно для заполнения.", {
          'duration': 3000
        });
      }
      try {
        this.$axios.$post(process.env.site+'/api/login-captcha-validate',{'g-recaptcha-response':token}).then(res=>{
          console.log(res)
          if(res == "validated"){

              this.$axios.$post('/api/reset-password',this.form).then(res=>{
                console.log(res);
              })
                  .catch(error => {
                    if (error.response && error.response.status == 422) {
                      for (let key in error.response.data.errors) {
                        //console.log(error.response);
                        console.log(error.response.data.errors[key]);
                        this.$toast.error(error.response.data.errors[key], {
                          'duration': 3000
                        });
                      }
                    }
                  })
              console.log('reg');
            }
          else{
            this.$toast.error("Решите капчу перед отправкой...", {
              'duration': 3000
            });
          }

        });
        this.$recaptcha.reset()
      } catch (error) {
        this.$toast.error("Решите капчу перед входом...", {
          'duration': 3000
        });
        console.log('Login error:', error);
      }
    },
    onSuccess (token) {
      console.log('Succeeded:', token)
    },
    onExpired () {
      console.log('Expired');
      this.$toast.error("Капча устарела..", {
        'duration': 3000
      });
    },
    onError (error) {
      this.$toast.error("Ошибочка вышла...", {
        'duration': 3000
      });
      console.log('Error happened:', error)
    },
  }
}
</script>
