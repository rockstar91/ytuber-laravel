<template>
  <div>
    <div v-if="!this.$store.state.auth.loggedIn">
      <GuestHeader></GuestHeader>
      <a id="login" class="anchor"></a>

      <div class="top-header" style="background: url('/index/office.png'); padding: 20px 0;">
        <div class="container">
          <div class="row">
            <div class="col-sm-6">
              <div class="pt-2 pb-2 text-center text-white">
                <h1 style="font-size: 2.3em; line-height: 1.3em; font-weight: 200;">накрутка <strong>подписчиков</strong> <span style="font-size: 0.88em;">и <strong>просмотров</strong> на YouTube</span></h1>
                <hr class="top-divider">
                <p class="text-left text-white">Ytuber — лучший сервис для бесплатного продвижения вашего канала на Youtube. Абсолютно безопасный и
                  анонимный способ накрутки подписчиков, лайков, просмотров и комментариев YouTube.</p>
                <p class="text-left text-white">Сервис работает по принципу взаимообмена между реальными пользователями, предоставляя широкий спектр
                  задач для гибкого продвижения вашего канала на ютуб. Наш сервис поможет вам стать популярнее и вывести
                  ваши ролики в тренды.</p>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="login-form">
                <div class="text-center mb-3 mt-4">
                  <i class="icon-youtube2 icon-2x text-white border-white border-3 rounded-round p-3 mb-3 mt-1"></i>
                  <br />
                  <b-button @click="$router.push('/login')" variant="outline-danger"><h5 class="mb-0">Войти на сайт</h5></b-button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <a id="about" class="anchor"></a>
      <div class="wrap-content wrap-content1 capability bg-white">
        <div class="container">
          <CompleteForPublic/>
          <div class="clearfix"></div>
          <h2 class="text-center">Возможности системы</h2>
          <div class="row">
            <div class="col-sm-6">
              <img src="/index/icon_view.png" width="60" alt="Увеличение просмотров на ютубе"/>
              <h3>Накрутка просмотров на YouTube</h3>
              <p class="lead">Увеличение просмотров поможет вашим роликам стать популярнее и получить дополнительный
                трафик из поиска YouTube.</p>
              <div class="clearfix"></div>
            </div>
            <div class="col-sm-6">
              <img src="/index/icon_like.png" width="60" alt="Накрутка лайков на YouTube"/>
              <h3>Накрутка лайков</h3>
              <p class="lead">Увеличение лайков совершенно бесплатно повышает популярность ваших роликов и выводит в топ
                по просмотрам.</p>
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <img src="/index/icon_comment.png" width="60" alt="Увеличение комментариев"/>
              <h3>Накрутка комментариев</h3>
              <p class="lead">Накрутка комментариев повышает активность ролика, привлекает внимание пользователей.</p>
              <div class="clearfix"></div>
            </div>
            <div class="col-sm-6">
              <img src="/index/icon_subscribe.png" width="60" alt="Увеличение подписчиков на ютуб"/>
              <h3>Накрутка подписчиков</h3>
              <p class="lead">Накрутка ютуб-подписчиков поможет привлечь реальных пользователей и повысить популярность
                канала.</p>
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <img src="/index/icon_replies.png" width="60" alt="Увеличение ответов к комментариям на ютубе"/>
              <h3>Накрутка ответов</h3>
              <p class="lead">Увеличение ответов к комментариям помогает продвигать ваши ролики в тренды ютуба.</p>
              <div class="clearfix"></div>
            </div>
            <div class="col-sm-6">
              <img src="/index/icon_comment_like.png" width="60" alt="Увеличение лайков к комментариям на YouTube"/>
              <h3>Накрутка лайков к комментариям</h3>
              <p class="lead">Накрутка лайков к комментариям – уникальная возможность поднять ваш коммент в лидеры.</p>
              <div class="clearfix"></div>
            </div>
          </div>
        </div>
      </div>
      <a id="about" class="anchor"></a>
      <div class="wrap-content wrap-gallery bg-1">
        <b-container>
          <div class="clearfix"></div>
          <h2 class="text-center text-dark pt-4">Интерфейс системы</h2>

          <div class="row">
            <div class="col-lg-12">

              <b-carousel
                  id="carousel-1"
                  :interval="2000"
                  img-width="1000"
                  img-height="680"
                  style="text-shadow: 1px 1px 2px #333;">
                <b-carousel-slide
                    caption="Настройки"
                    img-src="/index/Screenshot_1.png"
                ></b-carousel-slide>
                <b-carousel-slide
                    caption="Настройки"
                    img-src="/index/Screenshot_2.png"
                ></b-carousel-slide>
                <b-carousel-slide
                    caption="Перевод средств"
                    img-src="/index/Screenshot_2.png"
                ></b-carousel-slide>
                <b-carousel-slide
                    caption="Добавление новой задачи"
                    img-src="/index/Screenshot_3.png"
                ></b-carousel-slide>
                <b-carousel-slide
                    caption="Выполнение задач"
                    img-src="/index/Screenshot_4.png"
                ></b-carousel-slide>
              </b-carousel>
              <div class="clearfix"></div>
            </div>
          </div>
        </b-container>
      </div>
      <div class="wrap-content" style="background: url('/index/office.png'); padding: 20px 0;">
        <div class="container">
          <div class="row">
            <div class="col-sm-5" style="text-align: center;">
              <img class="img-responsive" src="/index/plus_25.png" alt="25 баллов бонус новым пользователям"
                   style="width: 80%; margin: 0 auto;"/>
            </div>
            <div class="col-sm-7 text-white">
              <hr class="heading-spacer">
              <div class="clearfix"></div>
              <h2>Бонус новым пользователям</h2>
              <p class="lead">Зарегистрируйтесь сегодня и получите <strong>25 баллов</strong> в подарок.</p>
              <p class="lead">Все что нужно сделать – зарегистрироваться на сайте используя рабочий email-адрес и
                подтвердить его, пройдя по ссылке в письме.</p>
              <p class="lead">Бонусные баллы будут начислятся по мере выполнения первых 50 заданий.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="wrap-content wrap-content1 bg-white pt-4 pb-4">
        <div class="container">
          <div class="row">
            <div class="col-sm-6 pt-4 pb-4">
              <hr class="heading-spacer">
              <div class="clearfix"></div>
              <h2>Бонусы за приглашенных друзей</h2>
              <p class="lead">В нашей системе начисляются бонусные баллы за приглашенных друзей.</p>
              <p class="lead">Вы получите до <strong>15%</strong> от всех заработанных баллов вашим рефералом.</p>
            </div>
            <div class="col-sm-6">
              <img class="img-responsive" src="/index/referaly.jpg" alt="">
            </div>
          </div>
        </div>
      </div>


      <div class="wrap-content wrap-content1 wrap-signup bg-1">
        <div class="container">
          <div class="row">
            <div class="col-lg-12 text-center pt-4 pb-4">
              <b-button variant="outline-danger">Зарегистрироваться сейчас</b-button>
            </div>
          </div>
        </div>
      </div>

      <div class="wrap-content wrap-content1 wrap-counters">
        <div class="container">
          <div class="row" style="text-align: center;">
            <div class="col-sm-3">
              <span class="num">{{counter1}}</span>
              <span class="text">пользователей</span>
            </div>
            <div class="col-sm-3">
              <span class="num">{{counter2}}</span>
              <span class="text">онлайн</span>
            </div>
            <div class="col-sm-3">
              <span class="num">{{counter3}}</span>
              <span class="text">всего заданий</span>
            </div>
            <div class="col-sm-3">
              <span class="num">{{counter4}}</span>
              <span class="text">выполнено заданий</span>
            </div>
          </div>
        </div>
      </div>

      <div class="wrap-content wrap-contacts">
        <a id="contact" class="anchor"></a>
        <div class="container team">
          <div class="row">
            <div class="col-lg-12 mt-4">
              <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
                <template #header>
                  <h3><i class="icon-envelop3 icon-2x mr-2"></i>Связаться с нами</h3>
                </template>
                <b-card-text>
                  <b-form-group>
                    <b-form-input
                        id="input-1"
                        v-model="form.mail"
                        type="email"
                        placeholder="Введите email"
                        required
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group>
                    <b-form-input
                        id="input-1"
                        v-model="form.title"
                        type="email"
                        placeholder="Тема сообщения"
                        required
                    ></b-form-input>
                  </b-form-group>
                  <b-form-group>
                    <b-form-textarea
                        id="textarea"
                        v-model="form.message"
                        placeholder="Сообщение..."
                        rows="3"
                        max-rows="6"
                    ></b-form-textarea>
                  </b-form-group>
                  <b-button variant="outline-primary" class="mt-2">Отправить</b-button>
                </b-card-text>
              </b-card>
            </div>
          </div>
        </div>
      </div>
      <footer>
        <div class="container">
          <div class="row">
            <div class="col-lg-12">

              <!--              <div class="yashare" style="text-align: center; margin-bottom: 15px;">
                              <div class="ya-share2"
                                   data-services="vkontakte,facebook,odnoklassniki,twitter,tumblr,viber,whatsapp,telegram"></div>
                            </div>-->

              <p class="links text-center">
                <nuxt-link to="/login">Вход</nuxt-link>
                <a href="#about">О нас</a>
                <span>Связь с нами: info@ytuber.ru </span>
                <nuxt-link to="/terms_of_use">Пользовательское соглашение</nuxt-link>
                <nuxt-link to="/privacy_policy">Политика конфиденциальности</nuxt-link>
              </p>
              <p class="copyright text-center medium">
                &copy; 2013-2022 <a href="/">Ytuber</a> — увеличение подписчиков на YouTube.</span>
              </p>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <dashboard v-else></dashboard>
  </div>
</template>

<script>
export default {
  head: {
    title: 'Накрутка YouTube, накрутка просмотров, увеличение и накрутка подписчиков на ютубе, обмен лайками и комментариями.',
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {
        hid: 'description',
        name: 'description',
        content: 'Наш сервис поможет вам увеличить количество просмотров, лайков, подписчиков и комментариев на ютубе. Накрутка YouTube, накрутка просмотров, увеличение и накрутка подписчиков на ютубе, обмен лайками и комментариями.'
      },
      {
        hid: 'keywords',
        name: 'keywords',
        content: 'Ytuber, накрутка ютуб, накрутка подписчиков на youtube, накрутка youtube, накрутка лайков на youtube, накрутка просмотров на youtube, накрутка ответов youtube, накрутка комментариев youtube, накрутка лайков комментариев, накрутить youtube, накрутка vk, накрутка twitter, продвижение youtube, накрутка youtube бесплатно, youtube подписчики купить, youtube просмотры заказать, youtube продвижение стоимость, накрутка стримов ютуб'
      }
    ],
    link: [{rel: 'icon', type: 'image/x-icon', href: '/favicon.ico'}]
  },
  auth: false,
  /*  async asyncData({store}) {
     await store.dispatch('ytuber/getWatchVideo');
    },*/
  middleware({store}) {
    if (!store.state.auth.loggedIn) {
      return 'guest'
    } else {
      return "authenticated"
    }
  },
  layout({store}) {
    if (!store.state.auth.loggedIn) {
      return 'guest'
    } else {
      return "authenticated"
    }
  },
  data() {
    return {
      counter1: 0,
      counter2: 0,
      counter3: 0,
      counter4: 0,
      form:{
        email:null,
        title:null,
        message:null
      }
    }
  },
  mounted() {
    let metadata = {
      title: 'Ютубер',
      description: 'Добро пожаловать на ютубер'
    };
    this.$store.dispatch('data/setMeta', metadata);
    this.setCounter1(120000);
    this.setCounter2(1500);
    this.setCounter3(10000);
    this.setCounter4(2500000);
  },
  methods:{
    setCounter1 (val){
      const obj = { n: this.counter1 }
      this.$anime({
        targets: obj,
        n: val,
        round: 1,
        duration: 150000,
        easing: 'linear',
        update: () => {
          this.counter1 = obj.n
        }
      })
    },
    setCounter2 (val){
      const obj = { n: this.counter2 }
      this.$anime({
        targets: obj,
        n: val,
        round: 1,
        duration: 150000,
        easing: 'linear',
        update: () => {
          this.counter2 = obj.n
        }
      })
    },
    setCounter3 (val){
      const obj = { n: this.counter3 }
      this.$anime({
        targets: obj,
        n: val,
        round: 1,
        duration: 150000,
        easing: 'linear',
        update: () => {
          this.counter3 = obj.n
        }
      })
    },
    setCounter4 (val){
      const obj = { n: this.counter4 }
      this.$anime({
        targets: obj,
        n: val,
        round: 1,
        duration: 150000,
        easing: 'linear',
        update: () => {
          this.counter4 = obj.n
        }
      })
    }
  }
}
</script>

<style>
footer p.links a, footer p.links span {
  margin: 0 5px;
}

.wrap-counters {
  background: #fff;
  color: #1e1e1e;
  padding: 40px 0;
}

.wrap-counters .col-sm-3 {
  margin: 10px 0;
}

.wrap-counters .num {
  font-size: 25px;
  /* display: block; */
  font-weight: 200;
  color: #fff;
  background: #c2191e;
  padding: 5px;
  border-radius: 15px;
}

.wrap-counters .text {
  font-size: 20px;
  font-weight: 200;
  display: block;
}

.capability {
  padding: 40px 0;
}

.capability .col-sm-6 {
  margin-bottom: 15px;
}

.capability img {
  float: left;
  margin-right: 15px;
  margin-top: 5px;
  opacity: 0.9;
}

.capability h3, .capability p.lead {
  margin-left: 75px;
}

.login-form p a {
  text-transform: uppercase;
  font-weight: bold;
  text-shadow: 1px 1px 0px #000;
}
div.wrap-content.wrap-content1.bg-white.pt-4.pb-4 div.container div.row div.col-sm-6 img.img-responsive{
  margin: 0 auto;
  max-width: 340px;
}

ul.navbar-nav li.nav-item{

}

ul.navbar-nav{

}
</style>