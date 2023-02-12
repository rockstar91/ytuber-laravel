<template>
    <div>
        <div class="content">
            <b-card>
                <!-- Input labels -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h6 class="card-title">Информация о пользователе</h6>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                        <a class="list-icons-item" data-action="reload"></a>
                                        <a class="list-icons-item" data-action="remove"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-3">Для получения информации о текущим пользователи используется  метод <strong>get</strong> запрос по протоколу HTTP <code>https://api.ytuber.ru/api/user_balance/</code> option
                                    запрос вернёт информацию о пользователи и баланс <code>json</code> объект</p>
                                <div id="alpaca-input-label"></div>
                                <a @click="getUserJson" href="#alpaca-input-label-source" data-toggle="collapse"><i class="icon-embed2 mr-2"></i> Показать пример ответа</a>
                                <div class="collapse mt-2" id="alpaca-input-label-source">
									<pre class="language-json">
                                        <code v-html="UserJson">

                                        </code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                  <div class="col-md-6">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h6 class="card-title">Дамп пользователя</h6>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                        <a class="list-icons-item" data-action="reload"></a>
                                        <a class="list-icons-item" data-action="remove"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-3">Для получения информации о текущим пользователи используется  метод <strong>get</strong> запрос по протоколу HTTP <code>https://api.ytuber.ru/api/user/</code> option
                                    запрос вернёт информацию о пользователи и баланс <code>json</code> объект</p>
                                <div id="alpaca-input-label"></div>
                                <a @click="getUserDump" href="#alpaca-input-label-source" data-toggle="collapse"><i class="icon-embed2 mr-2"></i> Показать пример ответа</a>
                                <div class="collapse mt-2" id="alpaca-input-label-source">
									<pre class="language-json">
                                        <code v-html="UserDump">

                                        </code></pre>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header header-elements-inline">
                                <h6 class="card-title">Источники выполнения</h6>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                        <a class="list-icons-item" data-action="reload"></a>
                                        <a class="list-icons-item" data-action="remove"></a>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <p class="mb-3">Для получения информации о источниках выполнения используется  метод <strong>get</strong> запрос по протоколу HTTP <code>https://api.ytuber.ru/api/referral-source/getList</code>
                                    запрос вернёт информацию в <code>json</code> объект</p>
                                <div id="alpaca-input-label2"></div>
                                <a @click="getReferralSourceJson" href="#alpaca-input-label-source2" data-toggle="collapse"><i class="icon-embed2 mr-2"></i> Показать пример ответа</a>
                                <div class="collapse mt-2" id="alpaca-input-label-source2">
									<pre class="language-json">
                                        <code v-html="ReferralSourceJson">

                                        </code></pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /input labels -->
            </b-card>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                Loading: true,
                UserJson:[],
                UserDump:[],
                ReferralSourceJson:[]
            }
        },
        props: {},
        mounted() {
          let metadata ={
            title:'API ytuber',
            description:'информация для разработчиков'
          };
          this.$store.dispatch('data/setMeta',metadata);
        },
        methods: {
            async getUserJson(){
                await this.$axios.$get('/api/user/')
                .then(res=>{
                    this.UserJson = res.data;
                })
            },
            getUserDump(){
                axios.get('/api/user/')
                .then(res=>{
                    this.UserDump = res.data;
                })
            },
            getReferralSourceJson(){
                axios.get('/api/referral-source/getList')
                .then(res=>{
                    this.ReferralSourceJson = res.data;
                })
            }
        },
        watch: {
            '$route.params.id'(newId, oldId) {
            }
        },
        components: {
            //'Pagination': require('laravel-vue-pagination'),
        }
    }
</script>
<style>
</style>
