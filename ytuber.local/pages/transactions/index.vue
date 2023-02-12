<template>
    <div>
        <div class="content">
            <b-card>

                <b-row style="max-width:400px">
                    <b-col>
                        <b-form-group label="Сумма баллов">
                            <b-form-input v-model="form.balance" type="number" placeholder="500"></b-form-input>
                        </b-form-group>
                    </b-col>
                  <b-col>
                    <i class="icon-arrow-right16 mr-3 ml-3 icon-2x" style="position: relative;top: 30px;left: 15px;"></i>
                  </b-col>
                    <b-col>
                        <b-form-group label="Куда переводить">
                            <b-form-input v-model="form.id" type="text" placeholder="ID"></b-form-input>
                        </b-form-group>
                    </b-col>
                </b-row>
                <b-row style="max-width:400px">
                    <b-col>
                        <b-button variant="success" @click="makeTransaction">Отправить</b-button>
                    </b-col>
                </b-row>
                <b-row>
                    <b-col class="mt-4">
                        <b-card title="История переводов">
                        <b-table :items="transactions.data" :fields="tableFields">
                          <template v-slot:cell(status)="data">
                          <b-badge v-if="data.item.status" variant="success">Отправлено</b-badge>
                          <b-badge v-else variant="warning">Ожидает подтверждения</b-badge>
                          </template>
                          <template v-slot:cell(time)="data">
                            {{$moment(data.item.time).format('Do MMMM YYYY в HH:mm:ss')}}
                          </template>
                        </b-table>
                        </b-card>
                    </b-col>
                </b-row>
                <b-row v-if="transactions.data && transactions.data.length>0">
                    <pagination class="mt-2 mb-2" :data="transactions" @pagination-change-page="getUserTransactions"></pagination>
                </b-row>
            </b-card>
        </div>
    </div>
</template>
<script>
    export default {
      created () {
        let lang = this.$i18n.locale;
        this.$moment.locale(lang);
      },
        data() {
            return {
                user: [],
                form: {
                    id: '',
                    balance: '',
                },
                transactions: {},
                tableFields: [
                    {key: 'id', sortable: true},
                    {key: 'sender', sortable: true, label:'Отправитель'},
                    {key: 'recipient', sortable: true, label:'Получатель'},
                    {key: 'sum', sortable: true, label:'Сумма'},
                    {key: 'status', sortable: true, label:'Статус'},
                    {key: 'time', sortable: true, label:'Дата'},
                ],
            }
        },
        mounted() {
          let metadata ={
            title:'Переводы',
            description:'Перевести баллы'
          };
          this.$store.dispatch('data/setMeta',metadata);
            this.getUserTransactions();
        },
        methods: {
            getUserTransactions(page = 1) {
                this.$axios.$get(process.env.site+'/api/get-lates-transactions'+ '?page=' + page)
                    .then(res => {
                        this.transactions = res;
                    })
            },
            makeTransaction() {
                this.$axios.$post('/api/transaction-send', this.form)
                    .then(res => {
                        if(res.includes('allow-transaction')){
                          this.$router.push(res);
                        }
                        if(res=='sended'){
                          this.$toast.info('Трансзакция создана, подтвердите по почте, согласие на перевод баллов...', {
                            'duration': 3000
                          });
                          this.getUserTransactions();
                        }
                      if(res=='no money') {
                        this.$toast.error('Не хватает баллов для перевода', {
                          'duration': 3000
                        });
                           }
                      if(res=='user not founded') {
                        this.$toast.error('Пользователь не найден', {
                          'duration': 3000
                        });
                           }
                    })
            }
        },
        components: {
            //'NavBar' : require('./Elements/NavBar.vue').default
        }
    }
</script>