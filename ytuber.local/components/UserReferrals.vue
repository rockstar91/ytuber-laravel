<template>
  <div>
    <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
      <template #header>
        <h3><i class="icon-users4 mr-3 icon-2x"></i>Ваши рефералы</h3>
        <small>Последние 5 зарегистрированных по вашей реф. ссылке</small>
      </template>
      <b-card-text>
        <b-table columns="5" striped hover :items="referralList" :fields="fields">
          <template v-slot:cell(avatar)="data">
            <b-img thumbnails :src="data.value" style="max-height:40px" v-if="data.value"/>
            <i v-else class="icon-user 2x" style="font-size: 40px;margin: 0 auto;"></i>
          </template>
          <template v-slot:cell(username)="data">
          <span><i class="icon-users2 mr-2"></i>{{data.value}}</span>
          </template>
          <template v-slot:cell(earned)="data">
          <span><i class="icon-coins mr-2"></i>{{data.value}}</span>
          </template>
        </b-table>
      </b-card-text>
    </b-card>
  </div>
</template>
<script>
  export default {
    name: "LatestReferrals",
    data() {
      return {
        fields: [
          {key: 'avatar', label: ''},
          {key: 'username', label: 'Имя пользователя'},
          {key: 'earned', label: 'Заработал'},
        ],
        referralsMode: false,
      }
    },
    mounted() {
     // this.fetchData();
    },
    methods: {
/*      async fetchData() {
        await this.$store.dispatch('ytuber/getUserRefferalsLatest').then(() => {
          console.log('refferals');
          this.referralsMode = true;
        })
      },*/
    },
    computed: {
      referralListWithClasses() {
        return this.referralList.map(item => {
          item.class = item.isOnline ? 'text-success' : 'text-danger';
          return item;
        })
      },
      referralList() {
        return this.$store.state.ytuber.dashboard.refferals;
      }
    },
  }
</script>
