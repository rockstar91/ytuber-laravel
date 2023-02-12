<template>
  <b-container>
    <b-card class="mt-4">
      <h2 class="content-heading">{{ $route.meta.title }} <span v-if="isEditMode">- {{name}}</span></h2>
      <b-row>
        <b-col>
          <b-form-group label="Тип задания">
            <multiselect v-model="currentTaskType"
                         :options="taskTypeList" :searchable="false" :close-on-select="true"
                         :show-labels="false" placeholder="Выберите тип задачи"
                         noOptions="Не получены данные"
                         :preselect-first="true" :disabled="isEditMode">

              <template slot="singleLabel" slot-scope="props">
                        <span class="option__desc">
                            <span class="option__title"><strong>{{ props.option.name }}</strong></span>
                        </span>
              </template>
              <template slot="option" slot-scope="props">
                <div class="option__desc">
                  <span class="option__title">{{ props.option.name }}</span>
                </div>
              </template>
            </multiselect>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Категория">
            <multiselect v-model="currentTaskCategory" :options="taskCategoryList" :searchable="false"
                         :close-on-select="true"
                         :show-labels="false" placeholder="Выберите категорию задачи"
                         noOptions="Не получены данные" :preselect-first="true" :disabled="isEditMode">
              <template slot="singleLabel" slot-scope="props">
                        <span class="option__desc">
                            <span class="option__title">{{ props.option.name }}</span>
                        </span>
              </template>

              <template slot="option" slot-scope="props">
                <div class="option__desc">
                  <span class="option__title">{{ props.option.name }}</span>
                </div>
              </template>
            </multiselect>
            <!---  <pre class="language-json"><code>{{ categoryvalue  }}</code></pre> --->
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Ссылка">
            <b-form-input
                v-model="url" name="url"
                :placeholder="placeholderUrl"
                :disabled="isEditMode">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Название">
            <b-form-input v-model="name"
                          placeholder="Мой самый лучший ролик">
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>

      <div class="row">
        <div class="form-group col-sm-6" v-show="videoImg.length>1">
          <img class="img-fluid img-thumb" :src="videoImg"/>
          <p style="margin-left: 7px;margin-top: 7px;">{{name}}</p>
          <p v-if="ChannelDescription.length>1">{{ChannelDescription}}</p>
        </div>
      </div>

      <b-row>
        <b-col>
          <b-form-group label="Бюджет">
            <b-form-input type="number" v-model="totalCost"
                          placeholder="200" min="0" step="100" @change="completeCountChanger"
                          @input="completeCountChanger">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Цена">
            <b-form-input type="number" v-model="actionCost" placeholder="1"
                          min="0" step="1" @change="completeCountChanger" @input="completeCountChanger">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Ограничение выполнений (в час)">
            <b-form-input type="number" v-model="hourLimit"
                          placeholder="0" min="0">
            </b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Количество выполнений">
            <b-form-input type="number" v-model="completeCount"
                          placeholder="0" min="0">
            </b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <b-form-group label="Время просмотра">
            <b-form-input type="number" v-model="targetTime" placeholder="0"
                          min="0">
            </b-form-input>
            <div class="time-select mt-1 ml-3">
              <span @click="setTargetTime(45)">45</span>
              <span @click="setTargetTime(60)">60</span>
              <span @click="setTargetTime(90)">90</span>
              <span @click="setTargetTime(180)">180</span>
              <span @click="setTargetTime(300)">300</span>
              <span @click="setTargetTime(600)">600</span>
            </div>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Количество роликов на канале">
            <multiselect v-model="currentChannelVideosCount" :options="channelVideosCount"
                         :searchable="false" :close-on-select="true"
                         :show-labels="false"
                         placeholder="Выберите минимальное количество роликов на канале выполняющего"
                         noOptions="Выберите минимальное количество роликов" track-by="name"
                         label="name">
            </multiselect>
          </b-form-group>
        </b-col>
      </b-row>

      <b-form-group label="Тип лайка" v-if="likeMode">
        <multiselect v-model="currentLike" :options="likeType" :searchable="false" :close-on-select="true"
                     :show-labels="false" placeholder="Выберите тип лайка"
                     noOptions="Выберите тип лайка">
        </multiselect>
      </b-form-group>

      <div class="form-group" v-show="commentMode">
        <label>Напишите ваши комментарии здесь</label>
        <div class="input-group mb-2" v-for="(comment,k) in customComments" :key="k">
          <input type="text" class="form-control" placeholder="Ваш комментарий"
                 v-model="comment.comment_text" v-on:keyup.enter="addComment(k)" ref="customComments">

          <div class="input-group-append">
            <button type="button" class="btn btn-circle btn-alt-danger ml-2" @click="removeComment(k)"
                    v-show="k || ( !k && comment.length > 1)">
              <i class="icon-cross3 ml-2"></i>
            </button>
          </div>
          <div>
            <b-button variant="outline-primary" type="button" class="btn btn-circle btn-alt-success ml-2" @click="addComment(k)"
                      v-show="!k || k == comment.length-1">
              <i class="icon-plus3 mr-2"></i> Добавить еще комментарий
            </b-button>
          </div>
        </div>
      </div>

      <div class="form-group" v-if="commentAnswersMode">
        <label>Выберите комментарий</label>
        <multiselect :searchable="true" @search-change="fetchYoutubeCommentsList"
                     :custom-label="customLabel"
                     track-by="textDisplay" label="option" :loading="isLoading" v-model="currentComment"
                     :options="youtubeCommentsList" placeholder="Введите комментарий для поиска"
                     deselectLabel="Нажмите чтобы убрать коммент" selectLabel="Выберите коммент"
                     :option-height="104">

          <template slot="singleLabel" slot-scope="props">
            <img class="option__image" v-if="props.option.snippet"
                 :src="props.option.snippet.topLevelComment.snippet.authorProfileImageUrl"
                 alt="Автор комментария"/>
            <div class="option__desc">
              <span v-if="props.option.snippet" class="comment_author">{{ props.option.snippet.topLevelComment.snippet.authorDisplayName }}</span>
              <span v-if="props.option.snippet" class="comment_text">{{ props.option.snippet.topLevelComment.snippet.textDisplay }}</span>
            </div>
          </template>

          <template slot="option" slot-scope="props">
            <img class="option__image"
                 :src="props.option.snippet.topLevelComment.snippet.authorProfileImageUrl"
                 alt="Автор комментария"/>
            <div class="option__desc">
              <span class="comment_author">{{ props.option.snippet.topLevelComment.snippet.authorDisplayName }}</span>
              <span class="comment_text">{{ props.option.snippet.topLevelComment.snippet.textDisplay }}</span>
            </div>
          </template>

          <span slot="noResult">Извините, комментариев к ролику не обнаружено.</span>
        </multiselect>
      </div>

      <div class="form-group" v-if="commentAnswersMode">
        <b-button variant="outline-primary" @click="fetchYoutubeCommentsList"><i
            class="icon-comment-discussion"></i> Загрузить еще
        </b-button>
        <p>Всего комментов: {{youtubeCommentsList.length}}</p>
      </div>
      <div class="row">
        <div class="form-group form-group col-sm-6">
          <div>
            <label class="typo__label">Выберите источник трафика</label>
            <multiselect v-model="currentReferralSource" :options="referralSourceList" :multiple="true"
                         :close-on-select="false" :clear-on-select="true" placeholder="Выберите"
                         label="name" track-by="name" :preselect-first="true"
                         noOptions="Выберите тип трафика">
            </multiselect>
          </div>
        </div>
        <div class="form-group form-group col-sm-6">
          <label class="typo__label">Выберите возраст каналов</label>
          <multiselect v-model="currentChannelsAge" deselect-label="Can't remove this value"
                       track-by="name"
                       label="name"
                       placeholder="Выполнять каналами возрастом от" :options="channelAgeList"
                       :searchable="false" :allow-empty="false">
            <template slot="singleLabel" slot-scope="{ option }">{{ option.name }}</template>
          </multiselect>
        </div>
      </div>

      <div class="form-group">
        <b-button v-if="isEditMode" variant="outline-primary" @click="updateTask">Обновить задачу</b-button>
        <b-button v-else variant="primary" @click="createTask">Создать задачу</b-button>
      </div>
    </b-card>
  </b-container>
</template>

<script>
export default {
  //mixins: [Youtube],
  props: {
    youtubeApiKey: String,
    taskId: {
      type: [Number, String],
      default: 0
    },
    likeType: {
      type: Array,
      default: function () {
        return ['Положителный (Лайк)', 'Отрицательный (Дизлайк)'];
      }
    },
    channelAgeList: {
      type: Array,
      default: function () {
        return [
          {name: 'Любой', value: 0},
          {name: 'Не менее 1 месяца', value: 30},
          {name: 'Не менее 3 месяцев', value: 90},
          {name: 'Не менее полугода', value: 180},
          {name: 'Не менее 1 года', value: 320},
          {name: 'Не менее 2 лет', value: 640},
          {name: 'Не менее 3 лет', value: 960}
        ];
      }
    },
    channelVideosCount: {
      type: Array,
      default: function () {
        return [
          {name: 'Любое количество', value: 0},
          {name: 'Не менее 1 ролика', value: 1},
          {name: 'Не менее 3 роликов', value: 3},
          {name: 'Не менее 5 роликов', value: 5},
          {name: 'Не менее 10 роликов', value: 10},
          {name: 'Не менее 15 роликов', value: 15},
          {name: 'Не менее 25 роликов', value: 25}
        ];
      }
    }
  },

  data() {
    return {
      site:process.env.site,
      videoId: '',
      isEditMode: '',
      completeCount: '',
      youtubeCommentsList: [],
      currentTaskType: [],
      currentTaskCategory: [],
      currentReferralSource: [],
      currentComment: [],
      currentLike: ['Положителный (Лайк)'],
      currentChannelsAge: {name: 'Любой', value: 0},
      currentChannelVideosCount: {name: 'Любой', value: 0},
      url: '',
      name: '',
      ChannelDescription: '',
      totalCost: 100,
      actionCost: 5,
      targetTime: 30,
      hourLimit: 0,

      customComments: [
        {comment_text: ''}
      ],

      videoImg: '',
      nextPageToken: '',

      // statements
      isLoading: true,
      channelInfo: '',
    }
  },
  mounted() {
    if (this.$route.params.id) {
      if (this.$store.state.ytuber.edit && this.$store.state.ytuber.edit.length > 0) {
        this.isEditMode = true;
        this.setEditedTask(this.$store.state.ytuber.edit);
      } else {
        this.fetchEditedTask();
      }

    } else {
      this.isEditMode = false;
    }
    this.getTaskTypes();


  },
  methods: {
    clearFormData() {
      this.currentLike = ['Положителный (Лайк)'];
      this.currentChannelsAge = {name: 'Любой', value: 0};
      this.currentChannelVideosCount = {name: 'Любой', value: 0};
      this.url = '';
      this.name = '';
      this.ChannelDescription = '';
      this.totalCost = 100;
      this.actionCost = 5;
      this.targetTime = 30;
      this.hourLimit = 0;
      this.customComments = [{comment_text: ''}];
      this.currentTaskType = '';
      this.completeCount = Math.floor(this.totalCost / this.actionCost);
      this.youtubeCommentsList = [];
      this.videoImg = '';
    },
    async getTaskTypes(){
      if (!this.$store.state.ytuber.taskTypes) {
        await this.$store.dispatch('ytuber/getTaskTypes').then(() => {
          this.currentTaskType = this.taskTypeList[0];
        });
      }
    },
    async fetchEditedTask() {
      this.isEditMode = true;
      this.$root.$emit('isLoading', true);
      let params = {
        'id': this.$route.params.id
      }
      await this.$store.dispatch('ytuber/setEdit', params).then(() => {

        this.setEditedTask(this.$store.state.ytuber.edit);
        this.$route.meta.title = 'Редактировать';
        this.$root.$emit('isLoading', false);
        this.completeCount = Math.floor(this.totalCost / this.actionCost);

      })


    },
    completeCountChanger() {
      this.completeCount = Math.floor(this.totalCost / this.actionCost);
    },
    setEditedTask(data) {
      //this.videoId = this.parseVideoId(data.url);
      this.currentTaskType = data.task_type;
      this.currentTaskCategory = data.task_category;
      //this.currentReferralSource = data.referral_sources;
      this.url = data.url;
      this.name = data.name;
      //this.ChannelDescription ='';
      this.totalCost = data.total_cost;
      this.actionCost = data.action_cost;
      this.targetTime = data.targetTime;
      this.hourLimit = data.hour_limit;

      this.setCurrentChannelAgeList(data.channelAgeLimit);
      this.setCurrentChannelVideosCount(data.videosCountLimit);
      this.setCurrentReferralSource(data.referral_sources);
      //console.log('refs updated');

      //добавляем комментарии
      this.customComments = [];
      for (let item in data.comments) {
        this.customComments.push(data.comments[item]);
      }
      if (this.customComments.length == 0) {
        this.customComments.push({comment_text: ''});
      }
    },
    setCurrentChannelAgeList(data) {
      for (let item in this.channelAgeList) {
        if (this.channelAgeList[item].value == data) {
          this.currentChannelsAge = this.channelAgeList[item];
        }
      }
    },
    setCurrentChannelVideosCount(data) {
      for (let item in this.channelVideosCount) {
        if (this.channelVideosCount[item].value == data) {
          this.currentChannelVideosCount = this.channelVideosCount[item];
        }
      }
    },

    setCurrentReferralSource(data) {
      this.currentReferralSource = [];
      setTimeout(() => {

        for (let key in this.referralSourceList) {
          for (let keyData in data) {
            if (this.referralSourceList[key].id == data[keyData].referral_source_id) {
              this.currentReferralSource.push(this.referralSourceList[key]);
            }
          }
        }

      }, 2000);
    },
    /**/
    setTargetTime(targetTime) {
      this.targetTime = targetTime;
    },

    async fetchYoutubeChannelInfo() {
      // let apiUrl = 'https://www.googleapis.com/youtube/v3/channels?part=snippet';
      let settings =
          {
            'channelName': '',
            'channelId': ''
          };

      if (this.channelName != null) {
        settings.channelName = this.channelName;
        //apiUrl += '&forUsername=' + this.channelName + '&key=' + process.env.api_key;
      } else if (this.channelId != null) {
        settings.channelId = this.channelId;
        //apiUrl += '&id=' + this.channelId + '&key=' + process.env.api_key;
      } else {
        return null;
      }

      if(settings.channelId != ''){
        await this.$store.dispatch('youtube/getChannelFromId', settings).then(() => {
          this.channelInfo = this.$store.state.youtube.channel;
        });
      }
      if(settings.channelName != '') {
        this.$toasted.show('Не верная ссылка на канал<br/> укажите ссылку с id каналом', {
          'type': 'error',
          'duration': 5000
        });
        // await this.$store.dispatch('youtube/getChannelFromName', settings).then(() => {
        //   this.channelInfo = this.$store.state.youtube.channel;
        // });
      }

      if (!this.isEditMode) {
        this.name = this.channelInfo['snippet']['title'];
      }
      this.ChannelDescription = this.channelInfo['snippet']['description'];
      this.videoImg = this.site+'/images/'+this.channelInfo['id']+'.jpg';
    },
    async fetchYoutubeVideoInfo() {
      this.getvideoId();
      if (!this.videoId) {
        this.videoImg = '';
        return null;
      }

      let settings = {'videoId': this.videoId};
      await this.$store.dispatch('youtube/getVideoFromId', settings).then(() => {
        let item = this.$store.state.youtube.video;
        if (!this.isEditMode) {
          this.name = item['snippet']['title'];
        }
        this.videoImg = item['snippet']['thumbnails']['default']['url'];
      })

    },

    fetchYoutubeCommentsList() {
      this.getvideoId();
      if (!this.commentAnswersMode || !this.videoId) {
        return null;
      }

      this.$axios.$get('/api/get-video-comments/?videoId='+this.videoId+'&token='+ this.nextPageToken)
          .then((res) => {
            this.youtubeCommentsList = this.youtubeCommentsList.concat(res.items);
            this.nextPageToken = res.nextPageToken;
            this.currentComment = [];
            console.log(this.youtubeCommentsList[0]);
            this.currentComment = this.youtubeCommentsList[0];
          });


    },

    fetchDataListTo(url, successCallback) {
      this.$axios.$get(url)
          .then((res) => {
            successCallback(res);
            //console.log(url + ' is read');
          })
          .catch((err) => {
            console.log(err);
            this.loading = false;
          });

      return this;
    },

    // загрузка списка типов задач
    async fetchTaskTypeList() {
      if (!this.$store.state.ytuber.taskTypes) {
        await this.$store.dispatch('ytuber/getTaskTypes');
      }
      if (!this.isEditMode && this.taskTypeList) {
        this.currentTaskType = this.taskTypeList[0];
      }
    },

    // загрузка списка категорий задач
    async fetchTaskCategoryList() {
      if (!this.taskCategoryList) {
        await this.$store.dispatch('ytuber/getCategories');
      }
      if (!this.isEditMode) {
        this.currentTaskCategory = this.taskCategoryList[0];
      }
    },
    // загрузка списка источников трафика
    async fetchReferralSourceList() {
      if (!this.referralSourceList) {
        await this.$store.dispatch('ytuber/getReferralSources');
      }
      if (!this.isEditMode) {
        this.currentReferralSource.push(this.referralSourceList[0]);
      }

    },

    // получение данных
    fetchData() {
      this.fetchReferralSourceList();
      this.fetchTaskTypeList();
      this.fetchTaskCategoryList();
    },

    async createTask() {
      let data = {
        name: this.name,
        url: this.url,
        type_id: this.typeId,
        video_id: this.videoId,
        channel_id: this.channelId,
        channel_name: this.channelName,
        category_id: this.categoryId,
        total_cost: this.totalCost,
        action_cost: this.actionCost,
        hour_limit: this.hourLimit,
        targetTime: this.targetTime,
        referralSources: this.currentReferralSource,
        channelAgeLimit: this.currentChannelsAge.value,
        videosCountLimit: this.currentChannelVideosCount.value,
        customCommentList: this.customComments,
        currentComment: this.currentComment,
        likeType: this.currentLike,
      };


      await this.$axios.$post(process.env.site + '/api/task/create', data)
          .then((response) => {
            this.$toast.success('<h1>Задача '+response.name+' успешно добавлена ' +
                '<img class="rounded float-left mr-2" ' +
                'src="https://img.youtube.com/vi/'+this.parseVideoIdFromUrl(response.url)+'/default.jpg"' +'width="120" '+ 'height="90"'+ ' />'+
                '</br> С баланса задачи взято ' + response.total_cost + ' баллов</h1>',
                {
                  duration: 3000
                }
            );
            this.$root.$emit('updateBalance');
            this.updateUserEmit();
            this.clearFormData();
          })
          .catch(error => {
            if (error.response && error.response.status == 422) {
              for (let key in error.response.data.errors) {
                //console.log(error.response);
                console.log(error.response.data.errors[key]);
                this.$toast.error(error.response.data.errors[key], {
                  'duration': 3000
                });
              }
            }
          })

    },

    updateTask() {
      let data = {
        task_id: this.$route.params.id,
        name: this.name,
        url: this.url,
        type_id: this.typeId,
        category_id: this.categoryId,
        total_cost: this.totalCost,
        action_cost: this.actionCost,
        hour_limit: this.hourLimit,
        targetTime: this.targetTime,
        referralSources: this.currentReferralSource,
        channelAgeLimit: this.currentChannelsAge.value,
        videosCountLimit: this.currentChannelVideosCount.value,
        customCommentList: this.customComments,
        likeType: this.currentLike,

      };
      this.$axios.$post(process.env.site + '/api/task/update', data).then(res => {
        //console.log(res.data);
        this.$root.$emit('updateBalance');
        this.$toast.success("<img src='" + this.videoImg + "' />'" + "&nbsp;<h1>Задача обновлена !!!</h1>", {
          'duration': 2500
        });
        this.$router.push('/task/list');
      })
          .catch(error => {
            if (error.response && error.response.status == 422) {
              for (let key in error.response.data.errors) {
                console.log(error.response.data.errors[key]);
                this.$toast.error(error.response.data.errors[key]);
              }
            }
          })
    },
    parseVideoIdFromUrl(url) {
      let vid = '';
      let pos = url.toString().indexOf('?v=');
      let poz = url.toString().indexOf('.be/');
      let pox = url.toString().indexOf('shorts/');

      if (url.toString().length == 11)
        vid = url;
      else {
        if (pos !== -1)
          vid = url.toString().substring(pos + 3, pos + 14)
        else if (poz !== -1)
          vid = url.toString().substring(poz + 4, poz + 15)
      }
      if(url.toString().includes('shorts/')){
        vid = url.toString().substring(pox + 7, url.length)
      }
      if (vid.length == 11)
        return vid;
      else
        return null;
    },
    getvideoId() {
      this.videoId = this.parseVideoIdFromUrl(this.url);
    },
    addComment(index) {
      let k = this.customComments.push({comment_text: ''});

      this.$nextTick(() => {
        this.$refs.customComments[k - 1].focus()
      });
      //console.log(this.$refs.customComments);
    },

    removeComment(index) {
      this.customComments.splice(index, 1);
    },

    customLabel(option) {
      //console.log(option);
      return `${option.snippet.topLevelComment.snippet.authorDisplayName} — ${option.snippet.topLevelComment.snippet.textDisplay}`
    },

    updateUserEmit() {
      this.$root.$emit('UPDATE_USER', true);
    },
  },
  watch: {
    url: {
      immediate: false,
      handler() {
        let posVideo = null;
        if(this.url.includes('shorts')){
          let posVideo = this.url.indexOf('shorts/');
        }
        else{
          this.url.indexOf('?v=');
        }
        let posChannel = this.url.indexOf('/channel/');
        let posChannelName = this.url.indexOf('/c/');
        if (posVideo !== -1){
          this.fetchYoutubeVideoInfo()
        }
        if (posChannel !== -1 || posChannelName !== -1) {
          this.fetchYoutubeChannelInfo()
        }
      }
    },
    taskCategoryList: {
      immediate: false,
      handler() {
        // получение категорий
        this.fetchTaskCategoryList()
      }
    },
    referralSourceList: {
      immediate: false,
      handler() {
        // получение источников просмотров
        this.fetchReferralSourceList()
      }
    },
    currentTaskType: {
      immediate: false,
      handler() {
        if (this.videoId && this.currentTaskType.name == 'YT Ответы к комментариям' || this.currentTaskType.name == 'YT Лайки к комментам') {
          this.fetchYoutubeCommentsList();
        }
      }
    },
    taskTypeList: {
      immediate: false,
      handler() {
        // получение типов задач
        this.fetchTaskTypeList()
      }
    },
    channelId: {
      immediate: false,
      handler() {
        // информация о канале
        this.fetchYoutubeChannelInfo();
      }
    },
    completeCount: {
      immediate: false,
      handler() {
        // количество выполнений
        this.totalCost = Math.floor(this.completeCount) * Math.floor(this.actionCost);

      }
    },
    isEditMode: {
      immediate: false,
      handler() {
        if (!this.isEditMode) {
          this.clearFormData();
        }
      }
    },
    taskId: {
      immediate: true,
      handler() {
        if (this.taskId > 0)
          this.fetchEditedTask();
      }
    },
  },
  computed: {
    channelId: function () {
      let posChannel = this.url.indexOf('/channel/');
      if (posChannel !== -1) {
        return this.parseChannelId(this.url);
      }
    },
    placeholderUrl(){
      if(this.currentTaskType.name == 'YT Подписчики') {
        return "https://www.youtube.com/channel/UCsaxk8Tn9rcUNhKhJwmbP0g";
      }
      else{
        return "https://www.youtube.com/watch?v=rtR2eJXb4U0"
      }

    },
    referralSourceList: function () {
      return this.$store.state.ytuber.refSources;
    },
    taskTypeList: function () {
      return this.$store.state.ytuber.taskTypes;
    },
    taskCategoryList: function () {
      return this.$store.state.ytuber.categories;
    },

    typeId: function () {
      return this.currentTaskType.id;
    },

    categoryId: function () {
      return this.currentTaskCategory.id;
    },


    channelName: function () {
      let posChannelName = this.url.indexOf('/c/');
      if (posChannelName !== -1) {
        return this.parseChannelName(this.url);
      }
    },

    // режим лайков
    likeMode: function () {
      if (this.currentTaskType) {
        return (this.currentTaskType.name == 'YT Лайки');
      }
    },
    // режим комментов
    commentMode: function () {
      if (this.currentTaskType) {
        return (this.currentTaskType.name == 'YT Ответы к комментариям' || this.currentTaskType.name == 'YT Комментарии');
      }
    },
    // режим ответов комментов
    commentAnswersMode: function () {
      if (this.currentTaskType) {
        return (this.currentTaskType.name == 'YT Ответы к комментариям' || this.currentTaskType.name == 'YT Лайки к комментам');
      }
    },
  },
  components: {
    //'Multiselect': require('vue-multiselect').default,
  }
}
</script>
<style>

.comment_author {
  font-size: 11px;
}

.comment_text {
  position: relative;
  top: -34px;
  left: 24px;
}

.time-select span {
  margin-right: 3px;
  color: #337ab7;
  border-bottom: 1px dotted #337ab7;
  cursor: pointer;
}

.multiselect__placeholder {
  margin-bottom: 7px !important;
  padding-top: 0px !important;
}

.multiselect__tag {
  margin-bottom: 3px !important;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
