<template>
  <div>
    <div class="card mt-4">
      <div class="content">
        <b-tabs content-class="mt-3">
          <b-tab title="Аккаунты" active>
            <div>
              <div class="card">
                <div class="card-header header-elements-inline">
                  <h6 class="card-title">Добавленные аккаунты</h6>
                  <div class="header-elements">
                    <div class="list-icons">
                      <a class="list-icons-item" data-action="collapse"></a>
                      <a class="list-icons-item" data-action="reload" @click="fetchData"></a>
                      <a class="list-icons-item" data-action="remove"></a>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table text-nowrap" v-if="accounts && accounts.data && accounts.data.length>0">
                      <thead>
                      <tr>
                        <th>Изображение</th>
                        <th>Ссылка</th>
                        <th>Тип</th>
                        <th></th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr v-for="account in accounts.data" v-bind:class="accountBg(account.active)">
                        <td>

                          <div>
                            <img :src="fixImgPath(account.url)" class="rounded-circle"
                                 style="max-width: 40px;"/>
                          </div>
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <span class="text-semibold">{{ account.title }}</span>
                          </div>
                          <div class="d-flex align-items-center">
                            <span class="text-semibold">{{ account.url }}</span>
                          </div>
                        </td>
                        <td>
                          <div>
                            {{ account.account_type.name }}
                          </div>
                        <td/>
                        <td>
                          <div>
                            <button class="btn btn-danger btn-xs" @click="RemovePage(account)">
                              Удалить
                            </button>
                          </div>
                          <div v-if="account.active == 0">
                            <button class="mt-2 btn btn-success btn-xs" @click="ActivateAccount(account)">
                              Сделать активным
                            </button>
                          </div>
                        </td>
                      </tr>
                      </tbody>
                    </table>
                    <div v-else class="alert alert-primary border-0 alert-dismissible">
                      <button type="button" class="close" data-dismiss="alert"><span>×</span></button>
                      <!--                                    <span>Прежде чем работать на сервисе, убедитесь, что  <span class="font-weight-semibold">вы добавили хотя бы один канал YouTube в</span> <a
                                                                  href="" class="alert-link">настройках профиля</a>, ознакомтесь с правилами сервиса, во избежании непонимания и блокировок аккаунтов, подтвердите ваш <strong>Email</strong>, перейдя по ссылке в письме.</span>-->
                      <h3> Чтобы добавить канал, введите ссылку на ваш канал, затем оставить любой текст в ответ на
                        комментарий.
                        Прикрепленный комментарий тот который с заголовком "Комментарий открыт по ссылке".</h3>
                      <b-img src="/comment-how.png"></b-img>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header header-elements-inline">
                  <h6 class="card-title">Добавить аккаунт</h6>
                </div>

                <div class="card-body">
                  <ModerateAccount></ModerateAccount>
                </div>
              </div>
            </div>
          </b-tab>

          <b-tab title="Уведомления">
            <b-card>
              <NotificationsModule/>
            </b-card>
          </b-tab>

          <b-tab title="Настройки">
            <b-row>
              <b-col>
                <b-card>
                  <b-form-group
                      id="fieldset-horizontal"
                      label-cols-sm="4"
                      label-cols-lg="3"
                      content-cols-sm
                      content-cols-lg="7"
                      label="Ваш текущий пароль"
                      description="Оставьте пустым если не хотите менять"
                      label-for="input-horizontal">
                    <b-form-input id="input-horizontal" v-model="formSettings.password"></b-form-input>
                  </b-form-group>
                  <b-form-group
                      id="fieldset-horizontal"
                      label-cols-sm="4"
                      label-cols-lg="3"
                      content-cols-sm
                      content-cols-lg="7"
                      label="Задайте новый пароль"
                      label-for="input-horizontal">
                    <b-form-input id="input-horizontal" v-model="formSettings.newpassord"></b-form-input>
                  </b-form-group>
                  <b-form-group
                      id="fieldset-horizontal"
                      label-cols-sm="4"
                      label-cols-lg="3"
                      content-cols-sm
                      content-cols-lg="7"
                      label="Повторите новый пароль"
                      label-for="input-horizontal">
                    <b-form-input id="input-horizontal" v-model="formSettings.newpassord2"></b-form-input>
                  </b-form-group>
                  <b-form-group
                      id="fieldset-horizontal"
                      label-cols-sm="4"
                      label-cols-lg="3"
                      content-cols-sm
                      content-cols-lg="7"
                      label-for="input-horizontal">
                    <b-form-checkbox v-model="formSettings.allowtransaction">
                      Подтверждать переводы по почте?
                    </b-form-checkbox>
                  </b-form-group>
                  <div class="text-center" >
                  <b-button variant="outline-primary" @click="saveSettings">Сохранить</b-button>
                  </div>
                </b-card>
              </b-col>
            </b-row>
            <b-row>
              <b-col>
                <b-card title="Api Ключ: приложения">
                  <b-form-group>
                    <b-form-input style="font-size: 20px;" id="textarea-plaintext" plaintext :value="api"
                                  class="text-center">
                    </b-form-input>
                    <b-card class="text-center" v-if="api == null" title="Код генерируется единожды">
                      <b-button v-if="api == null" @click="generateApi()">Сгенерировать API ключ</b-button>
                    </b-card>
                  </b-form-group>
                </b-card>
              </b-col>
            </b-row>
          </b-tab>
        </b-tabs>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      site: process.env.site,
      Loading: true,
      form: {
        url: null,
      },
      formSettings: {
        password: null,
        newpassord: null,
        newpassword2: null,
        allowtransaction: true
      },
      File: {},
      api: null,
    }
  },
  mounted() {
    let metadata = {
      title: 'Профиль',
      description: 'Страница пользователя'
    };
    this.$store.dispatch('data/setMeta', metadata);
    this.fetchData();
  },
  methods: {
    saveSettings() {
      this.$axios.post('/api/change-settings',this.formSettings).then(res=>{
        this.$store.dispatch('ytuber/getUser');
        if(res.data=='updated'){
          this.$toasted.show('Настройки сохранены!', {
            'type': 'success',
            'duration': 5000
          });
        }
      })
    },
    fixImgPath(url) {
      if (url) {
        let img = url.replace('https://www.youtube.com/channel/', this.site + '/images/');
        return img + '.jpg';
      }
    },
    accountBg(a) {
      if (a == 1) {
        return "bg-success-300"
      } else {
        return ""
      }
    },
    ActivateAccount(a) {
      this.$axios.$post(process.env.site + '/api/account/activate', a)
          .then((res) => {
            this.$store.dispatch('ytuber/getUser');
            this.$toasted.show(res.message, {
              'type': res.type,
              'duration': 5000
            });
            this.fetchData()
          })
    },
    UploadAvatar() {
      this.file = this.$refs.file.files[0];
      let formData = new FormData();
      formData.append('file', this.file);
      this.$axios.$post('avatar', formData, {headers: {'Content-Type': 'multipart/form-data'}})
          .then(res => {
            if (res.data.type == 'info') {
              this.$toasted.show(res.data.status);
              this.fetchData()
            }
            if (res.data.type == 'success') {
              this.fetchData();
              this.$toasted.show(res.data.status);
            }
          })
          .catch(error => {
            this.errors = error.response.data.errors;
          });
    },
    AddPage() {
      this.$axios.$post(process.env.site + '/api/account/create', this.form)
          .then((res) => {
            this.$toasted.show(res.message, {
              'type': res.type,
              'duration': 5000
            });
            this.fetchData()
          })
          .catch((error) => {
            if (error.response.data.errors.url[0]) {
              this.$toasted.show('Такой аккаунт уже добавлен', {
                'type': 'error',
                'duration': 5000
              });
            }
          })
      //console.log(this.pages.task_types);
    },
    RemovePage(account) {
      this.$axios.$post(process.env.site + '/api/account/delete', account)
          .then(() => {
            this.fetchData();
            this.$toasted.show('Аккаунт успешно удален ' + account.url, {
              'type': 'success',
              'duration': 5000
            });
          })
          .catch((err) => {
            console.log(err)
          })
    },
    PageClassByStatus: function (id) {
      return {
        'badge badge-info': id == 'Waiting moderation',
        'badge badge-warning': id == 'Rejected',
        'badge badge-success': id == 'Accepted',
      }
    },
    async fetchData(page = 1) {
      this.$root.$emit('isLoading', true);
      await this.$store.dispatch('ytuber/getAccounts').then(() => {
        //this.accounts = this.$store.state.ytuber.accounts;
        this.$root.$emit('isLoading', false);
      }).catch(e => {
            this.$root.$emit('isLoading', false);
          }
      );

    },
    generateApi() {
      this.$axios.$get(process.env.site + '/api/generate-api')
          .then(res => {
            this.api = res;
          })
    },
  },
  computed: {
    accounts() {
      return this.$store.state.ytuber.accounts
    },
    user() {
      if (this.$store.state.ytuber.user.allow_transaction == 1) {
        this.formSettings.allowtransaction = true;
      } else {
        this.formSettings.allowtransaction = false;
      }
      return this.$store.state.ytuber.user
    }
  },
  components: {
    'Pagination': require('laravel-vue-pagination'),
  }
}
</script>
<style>
</style>
