<template>
  <div>
    <GuestHeader></GuestHeader>
    <div class="alert alert-danger mb-2" role="alert" v-if="validateForm == false">
      Не верный логин или пароль.
    </div>
<!--    <div class="alert alert-primary border-0 alert-dismissible">
      <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
      <h4> Уважаемые пользователи, старых ваших доступов в аккаунт нет, ресурс был заброшен админом и был восстановлен с нуля новый.
        Остались ваши платежи, если вы покупали баллы у нас, напишите нам форму обратной связи, вернем вам на ваш новый аккаунт.
        Делаем все необходимое чтобы создать для вас самый лучший ресурс, ожидается множество обновлений и интересных функций.</h4>
    </div>-->
    <div class="content d-flex justify-content-center align-items-center">
      <!-- Login form -->
      <b-form-group class="login-form">
        <div class="card mb-0">
          <div class="card-body">
            <div class="text-center mb-3">
              <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
              <h5 class="mb-0">Войти на сайт</h5>
              <span class="d-block text-muted">Ytuber</span>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <b-form-input type="text" class="form-control" placeholder="Email" v-model="form.email"
                            required>
                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>
              </b-form-input>
            </div>

            <div class="form-group form-group-feedback form-group-feedback-left">
              <b-form-input class="form-control" placeholder="Password" v-model="form.password" required
                            type="password">
                <div class="form-control-feedback">
                  <i class="icon-lock2 text-muted"></i>
                </div>
              </b-form-input>
            </div>

            <div class="form-group d-flex align-items-center">
              <div class="form-check mb-0">
                <label class="form-check-label">
                  <input type="checkbox" v-model="form.remember" class="form-input-styled" checked
                         data-fouc>
                  Запомнить
                </label>
              </div>

              <a href="/password-recovery" class="ml-auto">Забыли пароль?</a>
            </div>

            <div class="form-group">
              <button @click="AccessLogin" class="btn btn-primary btn-block">Вход <i
                class="icon-circle-right2 ml-2"></i></button>
            </div>
<!--
            <div class="form-group text-center text-muted content-divider">
              <span class="px-2">Авторизация через соц. сети</span>
            </div>

            <div class="form-group text-center">
              <a href="/googleauth/">
                <button type="button"
                        class="btn btn-outline bg-indigo border-indigo text-indigo btn-icon rounded-round border-2">
                  <i class="icon-google"></i></button>
              </a>
              <button type="button"
                      class="btn btn-outline bg-pink-300 border-pink-300 text-pink-300 btn-icon rounded-round border-2 ml-2">
                <i class="icon-dribbble3"></i></button>
              <button type="button"
                      class="btn btn-outline bg-slate-600 border-slate-600 text-slate-600 btn-icon rounded-round border-2 ml-2">
                <i class="icon-github"></i></button>
              <button type="button"
                      class="btn btn-outline bg-info border-info text-info btn-icon rounded-round border-2 ml-2">
                <i class="icon-twitter"></i></button>
            </div>-->

            <div class="form-group text-center text-muted content-divider">
              <span class="px-2">Еще нет аккаунта?</span>
            </div>

            <div class="form-group">
              <a href="/registration" class="btn btn-light btn-block">Регистрация</a>
            </div>
<!--              <div id="v2-normal"
                   @error="onError"
                   @success="onSuccess"
                   @expired="onExpired"
              ></div>-->
              <br />
          </div>
        </div>
      </b-form-group>
      <!-- /login form -->
    </div>
  </div>
</template>
<script>
  export default {
    //middleware: 'authenticated',
    layout:'guest',
    data() {
      return {
        recaptcha_site_key:process.env.recaptcha_site_key,
        isAuth: false,
        validateForm: true,
        form: {
          email: '',
          password: '',
          remember: '',
          checked: [],
        },
        errors: [],
      }
    },
    async mounted() {
      await this.$axios.$get('/sanctum/csrf-cookie');
/*      await this.$recaptcha.init()
      this.widgetId = this.$recaptcha.render('v2-normal', {
        sitekey: this.recaptcha_site_key
      })*/
    },
    methods: {
      async AccessLogin(){
/*        try {
          const token = await this.$recaptcha.getResponse();
          await this.$axios.$post('/api/login-captcha-validate',{'g-recaptcha-response':token}).then(res=>{
            if(res == "validated"){*/
              //вход
              try {
               this.$auth.loginWith('laravelSanctum', {
                  data: this.form,
                })
                if (this.$auth.user.depth > 1) {
                  this.goTo('/dashboard/')
                } else {
                  this.goTo('/login')
                }
              } catch (error) {
                if (error.response) {
                  this.$toast.error(error.response.errors, {
                    'duration': 3000
                  });
                }
/*                if(!!error){
                  this.$toast.error("Не верный логин или пароль...", {
                    'duration': 3000
                  });
                }*/
              }
/*
            }
          });

          await this.$recaptcha.reset()
        } catch (error) {
          this.$toast.error("Решите капчу перед входом...", {
            'duration': 3000
          });
          console.log('Login error:', error);
          await this.$axios.$get('/sanctum/csrf-cookie');
        }*/
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
    },
    components: {
      // 'AddRatingPage': require('./AddRatingPage.vue').default,
    }
  }
</script>
