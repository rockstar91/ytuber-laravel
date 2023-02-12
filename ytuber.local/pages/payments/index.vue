<template>
    <div>
        <b-container>
            <div class="content">
                <b-card>
            <b-row class="text-center">
                <b-col></b-col>
                <b-col class="mt-4">
<!--                  <b-img src="/yoomoney.jpg" fluid alt="yandex money"></b-img>
                    <br />-->

                  <b-form-group label="Выберите тип оплаты" v-slot="{ ariaDescribedby }" v-if="!payment_created">
                    <br />
                    <b-card>
                    <b-form-radio-group
                        class="text-left"
                        v-model="selectedMethod"
                        :options="paymentOptions"
                        :aria-describedby="ariaDescribedby"
                        name="radios-stacked"
                        stacked
                    ></b-form-radio-group>
                  </b-card>
                    <b-form-group class="text-left" label="Введите сумму">
                        <b-form-input @input="youWillGetPoits"
                                id="input-1"
                                v-model="summa"
                                type="number"
                                required
                                placeholder="Введите сумму"
                                class="mt-2 mb-2"
                        ></b-form-input>
                    </b-form-group>
                    <b-form-group class="text-left" label="Вы получите баллов">
                      <b-form-input type="text" disabled v-model="points"></b-form-input>
                    </b-form-group>
                    <b-button @click="createPayment" variant="outline-primary" type="submit">Создать</b-button>
                  </b-form-group>
                  <b-card  v-if="payment_created">
                    <span class="text-success">Обработка платежей происходит в автоматическом режиме, баллы будут зачислены сразу после оплаты.</span>
                    <b-list-group class="mt-2">
                      <b-list-group-item class="text-dark" variant="light">Номер заказа: {{payment.id}}</b-list-group-item>
                      <b-list-group-item class="text-dark">Id пользователя: {{payment.user_id}}</b-list-group-item>
                      <b-list-group-item class="text-dark" variant="light">Метод оплаты: {{payment.type}}</b-list-group-item>
                      <b-list-group-item class="text-dark">Количество баллов: {{points}}</b-list-group-item>
                      <b-list-group-item class="text-dark" variant="success">Сумма платежа: {{payment.sum}}</b-list-group-item>
                    </b-list-group>
                  <b-form-group>
                        <!--Атрибут action ссылается на API Яндекс Денег-->
                        <form method="POST" action="https://yoomoney.ru/quickpay/confirm.xml">

                            <!--Номер кошелька в системе Яндекс Денег-->
                            <input type="hidden" name="receiver" :value="yoomoney">

                            <!--Название платежа, будет отображаться при переходе на форму оплаты в системе Яндекс Деньги-->
                            <input type="hidden" name="formcomment" value="На подарок">

                            <!--Параметр, который после успешной оплаты передается в наш скрипт. Тут может быть ID покупки. Мне нужно было передать несколько параметров, я делал это так - ID:123|account:123 и потом в скрипте, который принимает этот параметр разрезал с помощью функции explode-->
                            <input type="hidden" name="label" :value="payment.id">

                            <!--Тип формы, может принимать значения shop (универсальное), donate (благотворительная), small (кнопка)-->
                            <input type="hidden" name="quickpay-form" value="shop">

                            <!--Назначение платежа, это покупатель видит на сайте Яндекс Денег при вводе платежного пароля (длина 150 символов)-->
                            <input type="hidden" name="targets" value="На подарок">

                            <!--Сумма платежа, валюта - рубли по умолчанию, лучше указывать с копейками.-->
                            <input type="hidden" name="sum" :value="summa*1.01" data-type="number">

                            <!--Должен ли Яндекс запрашивать ФИО покупателя-->
                            <input type="hidden" name="need-fio" value="false">

                            <!--Должен ли Яндекс запрашивать email покупателя-->
                            <input type="hidden" name="need-email" value="false">

                            <!--Должен ли Яндекс запрашивать телефон покупателя-->
                            <input type="hidden" name="need-phone" value="false">

                            <!--Должен ли Яндекс запрашивать адрес покупателя-->
                            <input type="hidden" name="need-address" value="false">

                            <!--Метод оплаты, PC - Яндекс Деньги, AC - банковская карта. Если оставить пустым, то пользователь будет сам выбирать способ оплаты.-->
                            <input type="hidden" name="paymentType" value="" />

                            <!--Куда перенаправлять пользователя после успешной оплаты платежа. Тут можно сделать страничку "Спасибо за Ваш платеж"-->
                            <input type="hidden" name="successURL" value="https://ytuber.ru/payments/success/">
                            <br/>
                            <b-button variant="outline-primary" type="submit">Оплатить</b-button>
                        </form>
                    </b-form-group>
                  </b-card>
                </b-col>
                <b-col></b-col>
            </b-row>
                </b-card>
            </div>
        </b-container>
        <b-container class="mt-4">
            <b-card>
            <b-row class="text-center">
                <b-col>
                    <b>Сумма</b>
                </b-col>
                <b-col>
                    <b>Метод оплаты</b>
                </b-col>
                <b-col>
                    <b>Баллов</b>
                </b-col>
                <b-col>
                    <b>Дата</b>
                </b-col>
                <b-col>
                    <b>Статус</b>
                </b-col>
            </b-row>
            <b-row v-if="payments && payments.data" class="text-center" v-for="payment in payments.data" :key="payment.id">
                <b-col>
                    {{payment.sum}}
                </b-col>
                <b-col>
                    {{payment.type}}
                </b-col>
                <b-col>
                    {{payment.amount}}
                </b-col>
                <b-col>
                  <span v-if="payment.created_at!='0000-00-00'">{{$moment(payment.created_at).format('Do MMMM YYYY')}}</span>
                </b-col>
                <b-col>
                  <span v-if="payment.status" class="text-success">Оплачен</span>
                  <span v-else class="text-warning">Ожидает оплаты</span>
                </b-col>
            </b-row>
                <br />
                <b-card-footer>
                    <pagination v-if="payments && payments.data>0" class="mt-4" :data="payments" @pagination-change-page="getPayments"></pagination>
                </b-card-footer>
            </b-card>
        </b-container>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                yoomoney:process.env.yoomoney,
                site: process.env.site,
                user: [],
                payment_created:false,
                payment_id: [],
                payments: {},
                payment: null,
                selectedMethod:'',
                paymentOptions: [],
                summa:100,
                page:1,
                points:2250,
            }
        },
        created () {
          let lang = this.$i18n.locale;
          this.$moment.locale(lang);
       },
        mounted() {
          let metadata ={
            title:'Оплаты',
            description:'пополнить баланс'
          };
          this.$store.dispatch('data/setMeta',metadata);
            this.getUser();
            this.getPayments();
            this.getPaymentMethonds();
        },
        methods: {
            getPaymentMethonds(){
              this.$axios.$get(this.site+'/api/get-methods-payment').then(res=>{
                this.paymentOptions = [];
                for(let key in res){
                  if(res[key].name != 'WalletOne' && res[key].name != 'Webmoney'&& res[key].name != 'UnitPay'){
                    this.paymentOptions.push({
                      text:res[key].name,
                      value:res[key].system
                    })
                  }
                }
              });
            },
            youWillGetPoits(){
              let factor = 10.5;
              if (this.summa >= 100)
              {
                factor = 12.5;
              }
              if (this.summa >= 300)
              {
                factor = 15.5;
              }
              if (this.summa >= 490)
              {
                factor = 22.5;
              }
              if (this.summa >= 1200)
              {
                factor = 26.25;
              }
              if (this.summa >= 2100)
              {
                factor = 34;
              }
              if (this.summa >= 3100)
              {
                factor = 34;
              }
              if (this.summa >= 5100)
              {
                factor = 38;
              }
              if (this.summa >= 9100)
              {
                factor = 42;
              }
              if (this.summa >= 11000)
              {
                factor = 44;
              }
              this.points = this.summa*factor;
            },
            createPayment(){
              let data = {
                'summa':this.summa,
                'points':this.points,
                'method':this.selectedMethod
              };
              this.$axios.$post(this.site+'/api/create-payment',data).then(res=>{
                this.payment = res;
                this.payment_created = true;
              });
            },
            getUser(){
                this.$store.dispatch('ytuber/getUser').then(()=>{
                  this.user = this.$store.state.ytuber.user;
                })
            },
            test(){
                this.$axios.$get(this.site+'/api/test').then(res=>{
                   console.log(res);
                });
            },
            getPayments(page = 1){
                this.$axios.$get(this.site+'/api/get-payments'+'?page=' + page).then(res=>{
                    this.payments = res;
                })
            }
        },
        components:{
            //'NavBar' : require('./Elements/NavBar.vue').default
        }
    }
</script>
