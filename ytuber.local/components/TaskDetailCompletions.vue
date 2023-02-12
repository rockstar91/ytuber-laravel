<template>
  <div>
    <b-card-group deck>
      <b-card class="mb-2" style="min-width:210px; max-width: 210px;" v-for="(complete,index) in completions.data" :key="index">
        <template #header>
          <div class="text-center" v-if="complete.account">
            <a :href="complete.account.url" target="_blank"><b-avatar variant="info" :src="fixImgPath(complete.account.url)"></b-avatar></a>
          </div>
          <div class="text-center" v-else>
            <a :href="'https://youtube.com/channel/'+complete.user.channel" target="_blank"><b-avatar variant="info" :src="site + '/images/'+complete.user.channel"></b-avatar></a>
          </div>

          <div class="text-center mt-1" v-if="complete.account">
            <h5 class="ml-2">{{ complete.account.title }}</h5>
          </div>
          <div class="text-center mt-1" v-else>
            <h5 class="ml-2">{{ complete.user.username }}</h5>
          </div>
        </template>
        <b-card-text>
          <div v-if="complete.account">
            <h6><label>Видеороликов:</label> {{ complete.account.videoCount }}</h6>
          </div>
          <div v-else class="text-center">
            <h6>Канал удален</h6>
          </div>
          <div v-if="complete.account">
            <h6><label>Просмотров:</label> {{ complete.account.viewCount }}</h6>
          </div>
        </b-card-text>
        <template #footer>
          <div v-if="complete.account">
            <h6><label>Дата создания канала:</label> {{$moment(complete.account.publishedAt).format('Do MMMM YYYY')}}</h6>
          </div>
          <div v-else>
            <h6><label>Дата создания канала:</label> {{$moment(complete.user.created_at).format('Do MMMM YYYY')}}</h6>
          </div>
        </template>
      </b-card>
    </b-card-group>
    <b-card v-if="completions && completions.total>10">
      <pagination :data="completions" @pagination-change-page="getCompletions" class="mb-4"></pagination>
    </b-card>
  </div>
</template>
<script>
export default {
  props: [
    'task_id'
  ],
  mounted() {
    this.getCompletions();
  },
  data() {
    return {
      completions:[],
      site: process.env.site,
    }
  },
  methods: {
    getCompletions(page = 1) {
      this.$axios.get('/api/get-completions/'+this.task_id+'/?page='+page).then((res) => {
        this.completions = res.data
      })
    },
    fixImgPath(url) {
      if (url) {
        let img = url.replace('https://www.youtube.com/channel/', this.site + '/images/');
        return img + '.jpg';
      }
    },
    fixImgPathMain(url) {
      if (url) {
        let img = url.replace('https://www.youtube.com/channel/', this.site + '/images/');
        return img + '.jpg';
      }
    },
  }
}
</script>
