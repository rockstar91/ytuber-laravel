<template>
  <div class="m2" style="padding:6px">
    <b-list-group>
      <b-list-group-item v-if="accounts && accounts.data && accounts.data.length>0" v-for="account in accounts.data" v-bind:class="accountBg(account.active)" @click="ActivateAccount(account)" :key="account.id">
        <b-col>
          <div>
            <img :src="fixImgPath(account.url)" class="rounded-circle"
                 style="max-width: 40px;"/>
          </div>
        </b-col>
        <b-col>
          <div class="d-flex align-items-center">
            <span class="text-semibold">{{account.title}}</span>
          </div>
<!--          <div class="d-flex align-items-center">
            <span class="text-semibold">{{account.url}}</span>
          </div>-->
        </b-col>
              </b-list-group-item>
            </b-list-group>
          </div>
        </template>
        <script>
        export default {
          data() {
            return {
              site:process.env.site
            }
          },
          methods: {
            fixImgPath(url){
              if(url){
                let img = url.replace('https://www.youtube.com/channel/',this.site+'/images/');
                return img+'.jpg';
              }
            },
            accountBg(a){
              if(a==1){
                return "bg-success-300"
              }
              else{
                return ""
              }
            },
            ActivateAccount(a){
              this.$axios.$post(process.env.site+'/api/account/activate', a)
                  .then((res) => {
                    this.$toasted.show(res.message, {
                      'type': res.type,
                      'duration': 5000
                    });
                    this.fetchData();
                    if(this.$route.fullPath == "/dashboard" || this.$route.fullPath == "/chat" ){
                      window.location.reload(true);
                    }
                  })
            },
            async fetchData(page = 1) {
              this.$root.$emit('isLoading',true);
              await this.$store.dispatch('ytuber/getAccounts').then(()=>{
                //this.accounts = this.$store.state.ytuber.accounts;
                this.$store.state.dispatch('ytuber/getUser');
                this.$root.$emit('isLoading',false);
              })

            },
          },
          mounted() {
            if(this.accounts.data && this.accounts.data.length <=0){
              this.fetchData();
            }
          },
          computed:{
            accounts(){
              return this.$store.state.ytuber.accounts
            }
          },
          watch: {
          },
          components: {
            //'VueYoutube': require('vue-youtube-embed'),
            //'BootstrapVue': require('bootstrap-vue'),
          }
        }
        </script>
