<template>
<div>
  <GuestHeader></GuestHeader>
  <div class="page-content">

    <div class="content-wrapper">

      <div class="content d-flex justify-content-center align-items-center">

      <div class="login-form" v-if="!formSubmited">
        <div class="card mb-0">
          <div class="card-body">
            <div class="text-center mb-3">
              <i class="icon-youtube2 icon-2x text-danger border-danger border-3 rounded-round p-3 mb-3 mt-1"></i>
              <h5 class="mb-0">Регистрация на сайте</h5>
              <span class="d-block text-muted">Все поля обязательные для заполнения...</span>
            </div>

            <div class="form-group text-center text-muted content-divider">
              <span v-if="form.refferal" class="px-2">Ваш реферал {{form.refferal}}...</span>
              <span v-else class="px-2">регистрационные данные</span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input v-model="form.username" type="text" class="form-control" placeholder="Имя пользователя">
              <div class="form-control-feedback">
                <i class="icon-user-check text-muted"></i>
              </div>
            </div>
            <div class="form-group text-center text-muted content-divider">
              <span class="px-2">Email</span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input v-model="form.email" type="email" class="form-control" placeholder="Ваш email">
              <div class="form-control-feedback">
                <i class="icon-mention text-muted"></i>
              </div>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input v-model="form.email2" type="email" class="form-control" placeholder="Повторите email">
              <div class="form-control-feedback">
                <i class="icon-mention text-muted"></i>
              </div>
            </div>
            <div class="form-group text-center text-muted content-divider">
              <span class="px-2">Придумайте пароль</span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <input v-model="form.password" type="password" class="form-control" placeholder="Пароль">
              <div class="form-control-feedback">
                <i class="icon-user-lock text-muted"></i>
              </div>
            </div>
            <div class="form-group form-group-feedback form-group-feedback-left">
              <input v-model="form.password2" type="password" class="form-control" placeholder="Повторите пароль">
              <div class="form-control-feedback">
                <i class="icon-user-lock text-muted"></i>
              </div>
            </div>
            <span class="form-text text-center text-muted">Регистрируясь на сайте вы соглашаетесь с правилами <a
                href="/terms_of_use" target="_blank">Пользовательское Соглашение</a> и <a href="/privacy_policy" target="_blank">Политикой конфиденциальности</a></span>
            <div class="mb-2" id="v2-normal"
                 @error="onError"
                 @success="onSuccess"
                 @expired="onExpired"
            ></div>
            <button @click="registration" type="submit" class="btn bg-green-600 btn-block">Регистрация <i class="icon-circle-right2 ml-2"></i></button>
          </div>
        </div>
      </div>
      </div>
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
    recaptcha_site_key:process.env.recaptcha_site_key,
    formFilled:false,
    formSubmited:false,
    form: {
      username:null,
      email:null,
      email2:null,
      password:null,
      password2:null,
      refferal:null,
    },
  }),
  async mounted() {
    await this.$axios.$get('/sanctum/csrf-cookie');
    this.form.refferal = localStorage.getItem('refferal');
    await this.$recaptcha.init();
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
   async registration(){
     const token = await this.$recaptcha.getResponse();
        if(!this.form.username){
          this.$toast.error("Поле Имя пользователя обязательно для заполнения.", {
            'duration': 3000
          });
        }
        if(!this.form.email || !this.form.email2 ){
          this.$toast.error("Поле E-Mail обязательно для заполнения.", {
            'duration': 3000
          });
        }
      if(!this.form.email2 && this.form.email!=this.form.email2){
        this.$toast.error("Поле E-Mail заполнено некорректно.", {
          'duration': 3000
        });
      }
      if(!this.form.password){
        this.$toast.error("Поле Пароль обязательно для заполнения.", {
          'duration': 3000
        });
      }
      if(!this.form.password && this.form.password!=this.form.password2){
        this.$toast.error("Поле Пароль обязательно для заполнения.", {
          'duration': 3000
        });
      }
      if(this.form.username && this.form.email && this.form.email2 && this.form.password && this.form.password2){
        this.formFilled = true;
      }
      try {
         this.$axios.$post(process.env.site+'/api/login-captcha-validate',{'g-recaptcha-response':token}).then(res=>{
          console.log(res)
          if(res == "validated"){
            if(this.formFilled){
              this.$axios.$post('/api/registration',this.form).then(res=>{
               if(res=='registered'){
                 this.$toast.success("Вы успешно зарегистрировались, вам отправлено письмо с подтверждением...", {
                   'duration': 3000
                 });
                 this.$router.push('/login');
               }
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
          }
          else{
            this.$toast.error("Обновите страницу и попробуйте снова...", {
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
