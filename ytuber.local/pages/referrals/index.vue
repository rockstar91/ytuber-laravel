<template>
  <div class="card mt-4">
    <div class="content">
      <b-card-group desk v-if="referralsMode && referralList">
        <b-card v-for="user in referralList.data" :key="user.id" style="min-width:200px; max-width:200px">
          <b-row class="text-center">
            <img class="img-avatar" v-if="user.avatar"
                 :src="user.avatar" alt="" style="margin: 0 auto;">
            <i v-else class="icon-user 4x" style="font-size: 100px;margin: 0 auto;"></i>

          </b-row>
          <b-row class="text-center">
            <i v-bind:class="['icon-users2', user.class]"></i>
            {{user.username}}
          </b-row>
          <b-row class="text-center">
            <div class="font-w400 text-muted font-size-sm">
              <i class="icon-calendar"></i>
              {{user.created_at | moment("LLL")}}
            </div>
          </b-row>
          <b-row class="text-center">
            <div class="font-w400 font-size-sm text-muted">
              <i class="icon-coins"></i>
              {{user.earned}}
            </div>
          </b-row>
        </b-card>
      </b-card-group>
      <p v-else class="text-center mb-1">У вас еще нет рефералов.</p>
      <b-card v-if="referralList && referralList.total>10">
        <pagination :data="referralList" @pagination-change-page="fetchData" class="mb-4"></pagination>
      </b-card>
    </div>
  </div>
  <!-- END Friends -->
</template>

<script>
  export default {
    name: "LatestReferrals",
    data() {
      return {
        page:1,
        referralsMode: false,
      }
    },
    mounted() {
      let metadata ={
        title:'Рефералы',
        description:'приглашенные пользователи'
      };
      this.$store.dispatch('data/setMeta',metadata);
      this.fetchData();
    },
    methods: {
      async fetchData(page = 1) {
        let settings = {
          'page': page,
        };
        await this.$store.dispatch('ytuber/getUserRefferals',settings).then(() => {
          this.referralsMode = true;
        })
      },
    },
    computed: {
      referralListWithClasses() {
        return this.referralList.data.map(item => {
          item.class = item.isOnline ? 'text-success' : 'text-danger';
          return item;
        })
      },
      referralList() {
        return this.$store.state.ytuber.refferals;
      }
    },
  }
</script>

<style scoped>

</style>
