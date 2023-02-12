<template>
  <div>
    <div class="content">
      <TaskDeleteModal v-if="tasks" :removable-task.sync="removableTask"
                       :after-deleted="afterDeleted"></TaskDeleteModal>
      <div class="block">
        <div class="block-content">
          <b-card>
          <b-container v-if="youtubemode">
            <button class="float-right" @click="closeVideo"><i class="icon-cross"></i></button>
            <youtubePlayer></youtubePlayer>
          </b-container>
          <TaskFilter></TaskFilter>

            <div v-if="noActiveChannel" class="md-6 text-center"><nuxt-link to="/profile">Добавьте сначала канал</nuxt-link> чтобы начать выполнение задач.</div>

          <b-table v-if="maketMode && !noActiveChannel" striped hover :tbody-tr-class="rowClass" :items="tasks.data" responsive="sm"
                   :fields="fields" show-empty empty-text="Задачи не найдены" @change="">
            <template v-slot:cell(url)="row">
                    {{row.name}}
              <img style="cursor: pointer;" v-if="row.item.type_id !=4" :src="'https://img.youtube.com/vi/'+parseVideoIdFromUrl(row.item.url)+'/default.jpg'" alt=""
                   width="60" height="45"
                   @click="OpenTask(row.item)"/>

              <img style="cursor: pointer;" v-else :src="fixImgPath(row.item.url)" alt=""
                   width="88px" height="88px"
                   @click="OpenTask(row.item)"/>
              <div v-if="row.item.task_type.name == 'YT Лайки'">
                <span v-if="row.item.extend.type == 1">Выполните Лайк <i class="icon-thumbs-up2 ml-3"></i></span>
                <span v-if="row.item.extend.type == 2">Выполните ДизЛайка <i class="icon-thumbs-down2 ml-3"></i></span>
              </div>
              <div v-if="row.item.task_type.name == 'YT Лайки к комментам'">
                Выполните лайк к комментарию...
                <div>{{row.item.extend.snippet.topLevelComment.snippet.textDisplay}}</div>
              </div>
              <div v-if="row.item.task_type.name == 'YT Ответы к комментариям'">
                Напишите ответ к этому комментарию...
                <div>{{row.item.extend.snippet.topLevelComment.snippet.textDisplay}}</div>
                <p class="font-w600 title" v-if="activeTaskId ==  row.item.id && currentWorkComment != null">Выполните комментарий: <b class="text-primary"
                                                                     @click="copyClipboardComment">{{currentWorkComment}}</b></p>
              </div>
            </template>
            <template v-slot:cell(name)="data" @click="OpenTask(data.item)" style="cursor: pointer;">
              <span style="cursor: pointer;" @click="OpenTask(data.item)">{{data.value}}</span>
              <b-progress v-if="windowModeProgressBar && activeTaskId ==  data.item.id" :value="time"
                          variant="success" :max="max" striped :animated="animate"
                          class="mt-2"></b-progress>
              <div v-if="data.item.type_id == 3 && activeTaskId == data.item.id && currentWorkComment != null">
                <p class="font-w600 title">Выполните комментарий: <b class="text-primary"
                                                                     @click="copyClipboardComment">{{currentWorkComment}}</b>
                </p>
              </div>
            </template>
            <template v-slot:cell(action_cost)="row">
              {{newActionCost(row.item.action_cost)}}
            </template>
            <template v-slot:cell(buttons)="row">
              <div>
              <b-button-group>
                <b-button class="ml-1" v-if="$store.state.ytuber.user.admin"  type="button"
                          @click="setEditTask(row.item)"
                          data-toggle="tooltip"
                          title="" data-original-title="Edit" variant="outline-primary">
                  <i class="fa fa-pencil"></i>
                  Редактировать
                </b-button>
                <b-button class="ml-1" @click="getTaskComment(row.item.id)" v-if="row.item.type_id == 3 || row.item.type_id == 201">
                  Скопировать
                </b-button>
              <b-button class="mt-2 ml-1" v-if="$store.state.ytuber.user.admin" size="lg" data-toggle="modal" data-target="#deleteModal" @click="deleteTask(row.item)"
                        variant="danger" title="" data-original-title="Удалить">
                <i class="icon-cross"></i>
              </b-button>
              <b-button class="mt-2 ml-1" v-if="$store.state.ytuber.user.admin" size="lg" @click="RepeatTaskJob(row.item.id)"
                        variant="primary" title="" data-original-title="Обновить">
                <i class="icon-loop3"></i>
              </b-button>
              </b-button-group>
              </div>
              <div class="mt-2">
                <b-badge variant="warning" v-if="row.item.completions[0] && row.item.completions[0].status == 2">Ожидает проверки</b-badge>
                <b-badge variant="danger" v-if="row.item.completions[0] && row.item.completions[0].status == 3">Просрочено</b-badge>
                <b-badge variant="success" v-if="row.item.completions[0] && row.item.completions[0].status == 4">Выполнено</b-badge>
                <b-badge variant="danger" v-if="row.item.completions[0] && row.item.completions[0].status == 5">Не Выполнено</b-badge>
              </div>
            </template>
            <template v-slot:cell(tasks_type)="data">
              <span>{{data.item.task_type.name}}</span>
            </template>
          </b-table>
            <div v-else class="row">
              <div v-if="tasks.data && !noActiveChannel" class="col-md-4" v-for="(task,index) in tasks.data" :key="index">
                <div style="cursor: pointer;" :class="'card border-left-3 border-left-'+divClass(task)+ ' rounded-left-0'" @click="OpenTask(task)">
                  <div class="card-body">
                    <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                      <div class="mr-4">
                        <img v-if="task.type_id !=4" :src="'https://img.youtube.com/vi/'+parseVideoIdFromUrl(task.url)+'/default.jpg'"
                             class="img-fluid img-thumbnail pull-left mr-4"
                             width="60" height="45" alt="">
                        <img v-else-if="task.youtube_extend && task.youtube_extend.snippet && task.youtube_extend.snippet.thumbnails" :src="fixImgPath(task.url)"
                             class="img-fluid img-thumbnail pull-left mr-4"
                             width="88" height="88" alt="">
                        <h6>{{task.name}}</h6>

                        <b-progress v-if="windowModeProgressBar && activeTaskId ==  task.id" :value="time"
                                    variant="success" :max="max" striped :animated="animate"
                                    class="mt-2"></b-progress>
                        <p>Бюджет: {{task.total_cost}}</p>
                      </div>
                      <ul class="list list-unstyled mb-0 mt-3 mt-sm-0 ml-auto" style="min-width: 90px;">
                        <li>
                          {{$t('task.price')}}: {{ newActionCost(task.action_cost)}}
                        </li>
                        <li>
                     <span >
                      {{$t(task.task_type.name)}}
                    </span>
                        </li>
                        <li>
                          <span class="badge badge-info">{{task.targetTime}} {{$t('sec')}}</span>
                        </li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
                    <b-button-group>
                    <b-button size="lg" @click="setEditTask(task)"
                              variant="outline-primary" data-toggle="tooltip" title="" data-original-title="Редактировать">
                      <i class="icon-pencil"></i>
                    </b-button>
                    <b-button size="lg" data-toggle="modal" data-target="#deleteModal" @click="deleteTask(task)"
                              variant="danger" title="" data-original-title="Удалить">
                      <i class="icon-cross"></i>
                    </b-button>
                    </b-button-group>
                    <b-button-group><b-button class="ml-1" @click="getTaskComment(task.id)" v-if="task.type_id == 3 || task.type_id == 201">
                      Скопировать
                    </b-button></b-button-group>
                    <div v-if="task.task_type.name == 'YT Лайки'">
                      <span v-if="task.extend.type == 1">Выполните Лайк <i class="icon-thumbs-up2 ml-3"></i></span>
                      <span v-if="task.extend.type == 2">Выполните ДизЛайка <i class="icon-thumbs-down2 ml-3"></i></span>
                    </div>
                    <div v-if="task.task_type.name == 'YT Лайки к комментам'">
                      Выполните лайк к комментарию...
                      <div>{{task.extend.snippet.topLevelComment.snippet.textDisplay}}</div>
                    </div>
                    <div v-if="task.task_type.name == 'YT Ответы к комментариям'">
                      Напишите ответ к этому комментарию...
                      <div>{{task.extend.snippet.topLevelComment.snippet.textDisplay}}</div>
                      <p class="font-w600 title" v-if="activeTaskId ==  task.id && currentWorkComment != null">Выполните комментарий: <b class="text-primary" @click="copyClipboardComment">{{currentWorkComment}}</b></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </b-card>
          <br/>
          <pagination v-if="tasks.data" :data="tasks" @pagination-change-page="fetchDataTasks"></pagination>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  //import Youtube from "@/mixins/youtube.js";
  //import TaskFilter from "../Elements/TaskFilter";

  export default {
    layout: 'default',
    //mixins: [Youtube],
/*    props: {
      initialPage: {
        type: Number,
        default: 1,
      }
    },*/
    data() {
      return {
        noActiveChannel:false,
        site: process.env.site,
        fields: [
          {key: 'url', label: ''}, ,
          {key: 'name', label: 'Название'},
          {key: 'action_cost', label: 'Цена', sortable: true},
          {key: 'targetTime', label: 'Время',sortable: true},
          {key: 'tasks_type', label: 'Тип задачи'},
          {key: 'buttons', label: ''},
        ],
        taskStatusClass: 'bg-white',
        page:1,
        fullPage: true,
        openedWindow: {},
        youtubemode: false,
        activeTaskId: '',
        windowModeProgressBar: false,
        interval: null,
        time: 0,
        animate: '',
        progress: false,
        max: 0,
        closed: true,
        isRunning: false,
        currentFilterTaskType: 0,
        currentWorkComment: null,
        removableTask:[],
        activeTask:null,
       /* maketMode:false,*/
      }
    },
    mounted() {
      this.fetchDataTasks();
      let metadata ={
        title:'Все задачи',
        description:'Выполнить задачи'
      };
      this.$store.dispatch('data/setMeta',metadata);
      this.$store.dispatch('ytuber/getAccounts');
      this.$store.dispatch('ytuber/setTaskType',1);
      this.$nuxt.$on('currentFilterTaskType', (data) => {
        if($nuxt.$route.path == "/work/list") {
          this.currentFilterTaskType = data;
          this.fetchDataTasks(1);
        }
      });
      this.isMobile();
    },
    methods: {
      newActionCost(price){
        return price * 0.75;
      },
      fixImgPath(url){
        if(url){
          let img = url.replace('https://www.youtube.com/channel/',this.site+'/images/');
          return img+'.jpg';
        }
      },
      isMobile() {
        if (window.innerWidth <= 1200) {
          let data = {
            mode: false,
          };
          this.$store.dispatch('ytuber/setMaketMode', data);
        } else {
          let data = {
            mode: true,
          };
          this.$store.dispatch('ytuber/setMaketMode', data);
        }
      },
      afterDeleted(){
        this.fetchDataTasks();
      },
      deleteTask(task) {
        this.removableTask = task;
      },
      async setEditTask(item) {
        await this.$store.dispatch('ytuber/setEdit', item).then(()=>{
          this.$router.push('/task/edit/' + item.id);
        });
      },
      async fetchDataTasks(page = 1) {
        this.$root.$emit('isLoading',true);
        this.currentWorkComment = null;
        if(!this.$store.state.ytuber.user.active_account){
          this.noActiveChannel = true;
        }
        //this.page = page;
        let settings = {
          'task_type': this.currentFilterTaskType,
          'page': page,
          'mine':false
        };

        await this.$store.dispatch('ytuber/getWork', settings).then(()=>{
          this.$root.$emit('isLoading',false);
        })

      },
      toggleTimer() {
        if (!this.isRunning) {
          clearInterval(this.interval);
        } else {
          this.interval = setInterval(this.incrementTime, 1000);
        }
        this.isRunning = !this.isRunning;
      },
      async ClearStatusTasks(task) {
        await this.$store.commit('ytuber/clearWorkStatus',task);
        this.windowModeProgressBar = false;
      },
      incrementTime() {
        this.time = parseInt(this.time) + 1;
        try {
          if (this.openedWindow.closed) {
            this.progress = false;
            if (this.closed == true) {
              if (this.max >= this.time) {
                if (this.youtubemode == false) {
                  this.$toasted.show('Не закрывайте окно, раньше времени!', {
                    'type': 'error',
                    'duration': 5000
                  });
                } else {
                  this.$toasted.show('Пожалуйста посмотрите ролик ' + this.max + ' sec', {
                    'type': 'info',
                    'duration': 5000
                  });
                }
                clearInterval(this.interval);
                this.time = 0;
                this.progress = false;
                this.ClearStatusTasks(this.activeTask);
              } else {
                this.CheckTaskJob();
                clearInterval(this.interval);
                this.time = 0;
                this.progress = false;
                this.ClearStatusTasks(this.activeTask);
              }
            }
            this.closed = false;
          }
        } catch {
        }
      },
      CheckTaskJob(){
        console.log('проверяем задачу');
        this.$root.$emit('isLoading',true);
        this.$axios.$get('/api/task-check/' + this.activeTaskId).then(res=>{
          //this.$toasted.show("Задача отправлена на проверку", {'type': 'info', 'duration': 5000});
          this.$toasted.show('<h1>Задача '+this.activeTask.name+' </br> отправлена на проверку! ' +
              '<img class="rounded float-left mr-2" ' +
              'src="https://img.youtube.com/vi/'+this.parseVideoIdFromUrl(this.activeTask.url)+'/default.jpg"' +'width="120" '+ 'height="90"'+ ' />'+
              '</br></h1>',
              {'type': 'info', 'duration': 5000}
          );
          this.ClearStatusTasks(this.activeTask);
          this.fetchDataTasks(this.page);
        }).then(()=>{
          this.$root.$emit('isLoading',false);
        })
      },
      RepeatTaskJob(task_id){
        console.log('проверяем задачу');
        this.$axios.$get('/api/task-check/' + task_id).then(res=>{
          //this.$toasted.show("Задача отправлена на проверку", {'type': 'info', 'duration': 5000});
          this.$toasted.show('<h1>Задача '+this.activeTask.name+' </br> отправлена на проверку! ' +
              '<img class="rounded float-left mr-2" ' +
              'src="https://img.youtube.com/vi/'+this.parseVideoIdFromUrl(this.activeTask.url)+'/default.jpg"' +'width="120" '+ 'height="90"'+ ' />'+
              '</br></h1>',
              {'type': 'info', 'duration': 5000}
          );
          this.ClearStatusTasks(this.activeTask);
          this.fetchDataTasks(this.page);
        });
      },
      OpenTask(task) {
        if(task.type_id == 3 || task.type_id == 201){
          if(!this.currentWorkComment){
            this.$toasted.show("Сначала скопируйте комментарий", {'type': 'info', 'duration': 3000});
            return "ok";
          }
        }
        this.$store.dispatch('ytuber/setTask',task);
        this.max = task.targetTime;
        this.time = 0;
        this.toggleTimer();
        this.progress = true;
        this.closed = true;
        let mode = false;
        this.activeTaskId = task.id;
        this.activeTask = task;
        if (task.type_id == 1) {
          mode = true;
        }
        if (task.url.includes('youtu') && mode) {
          this.youtubemode = true;
          this.$route.params.videoId = task.url;
          this.$route.params.maxtime = task.targetTime;
          this.changeStatusToPrimary(task);
          this.windowModeProgressBar = true;
          this.toggleTimer();
        } else {
          this.$axios.$get('/api/task-status/' + task.id).then(res=>{
              if(res == "recaptcha"){
                this.$router.push('/captcha');
              }
          else if(res == 'taskIsBusy'){
              this.$toasted.show("Задача временно не доступна", {'type': 'error', 'duration': 5000});
              clearInterval(this.interval);
              this.time = 0;
              this.progress = false;
              this.ClearStatusTasks(task);
              this.interval = null;
              this.closed = false;
              this.youtubemode = false;
              this.windowModeProgressBar = false;
              this.isRunning = false;
            }
            else if(res == 'taskIsCompeleted'){
              this.$toasted.show("Задача уже выполнена", {'type': 'success', 'duration': 5000});
              clearInterval(this.interval);
              this.time = 0;
              this.progress = false;
              this.ClearStatusTasks(task);
              this.interval = null;
              this.closed = false;
              this.youtubemode = false;
              this.windowModeProgressBar = false;
              this.isRunning = false;
            }
            else if(res == 'taskIsWaiting'){
              this.$toasted.show("Задача ожидает проверки", {'type': 'info', 'duration': 5000});
              clearInterval(this.interval);
              this.time = 0;
              this.progress = false;
              this.ClearStatusTasks(task);
              this.interval = null;
              this.closed = false;
              this.youtubemode = false;
              this.windowModeProgressBar = false;
              this.isRunning = false;
            }
            else if(res == 'taskIsDeleted'){
              this.$toasted.show("Задача не найдена", {'type': 'error', 'duration': 5000});
              clearInterval(this.interval);
              this.time = 0;
              this.progress = false;
              this.ClearStatusTasks(task);
              this.interval = null;
              this.closed = false;
              this.youtubemode = false;
              this.windowModeProgressBar = false;
              this.isRunning = false;
              this.fetchDataTasks(this.page);
            }
            else{
              this.youtubemode = false;
              var width = 1120;
              var height = 700;
              var top = 100;
              var left = ($(document).width() - width) / 2;
              var isFirefox = typeof InstallTrigger !== 'undefined';
              if(!isFirefox){
                    if(task.task_type.name=="YT Лайки к комментам" || task.task_type.name=="YT Ответы к комментариям"){
                      this.openedWindow = window.open(task.url+"&lc="+task.extend.snippet.topLevelComment.id, 'targetWindow', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
                    }
                    else{
                      this.openedWindow = window.open(task.url, 'targetWindow', 'width=' + width + ',height=' + height + ',top=' + top + ',left=' + left + ',toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');
                    }
                }
              else if(isFirefox){
                this.$toasted.show("Firefox браузер не поддерживается, используйте chrome", {
                  'type': 'error',
                  'duration': 5000
                });
                return false;

/*                if(task.task_type.name=="YT Лайки к комментам" || task.task_type.name=="YT Ответы к комментариям"){
                  window.open(task.url+"&lc="+task.extend.snippet.topLevelComment.id, 'mozillaTab');
                  //this.openedWindow.closed = false;
                }
                else{
                  const windowFeatures = "left="+left+",top="+top+",width="+width+",height="+height;
                  //const handle = window.open(task.url, "mozillaWindow", windowFeatures);
                  const handle = window.open(task.url, "mozillaTab");
                  if (!handle) {
                    this.$toasted.show("Разрешите всплывающие окна firefox", {
                      'type': 'info',
                      'duration': 5000
                    });
                  }
                  else{
                    this.openedWindow.closed = false
                  }

                }*/
              }
              this.changeStatusToPrimary(task);
              this.progress = true;
              this.windowModeProgressBar = true;
              this.toggleTimer();
            }
          })
        }

      },
      TaskOpenRequest(task){
        this.activeTask = task;
        this.$axios.$get('/api/task-open/' + task.id).then(res=>{
          console.log(res.data);
        })
      },
      async copyCommentClipbord(text) {
        try {
          await this.$copyText(text);
        } catch (e) {
          console.error(e);
        }
      },
      async changeStatusToPrimary(task) {
        await this.$store.commit('ytuber/changeStatusToPrimary', task)
      },
      closeVideo() {
        this.youtubemode = false;
        this.progress = false;
        this.closed = true;
        this.time = 0;
        this.interval = 0;
        this.toggleTimer();
        this.ClearStatusTasks(this.activeTask);
      },
      getTaskComment(id) {
        this.$axios.$get(this.site+'/api/task-comment/get-free/' + id)
          .then((res) => {
            if(res["comment_text"]) {
              this.currentWorkComment = res["comment_text"];
              this.copyCommentClipbord(res["comment_text"]);
              this.$toasted.show("Комментарий '" + res["comment_text"] + "' скопирован", {
                'type': 'info',
                'duration': 5000
              });
            }
            else{
              this.$toasted.show("Комментарий не найден", {'type': 'error', 'duration': 5000})
            }
          })
          .catch((err) => {
            this.$toasted.show("Нет комментариев", {'type': 'error', 'duration': 5000});
            this.currentWorkComment = null;
            console.log(err);
          });
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
      copyClipboardComment() {
        this.copyCommentClipbord(this.currentWorkComment);
        this.$toasted.show("Комментарий '" + this.currentWorkComment + "' скопирован", {
          'type': 'info',
          'duration': 5000
        });
      },
      divClass(task){
        if(task.completions && task.completions[0] && task.completions[0].status == 2){return 'info-300'}
        if(task.completions && task.completions[0] && task.completions[0].status == 3){return 'warning-300'}
        if(task.completions && task.completions[0] && task.completions[0].status == 4){return 'success-300'}
        if(task.completions && task.completions[0] && task.completions[0].status == 5){return 'danger-300'}
        return 'grey-300';

      },
      rowClass(item, type) {
        if (!item || type !== 'row') return
        if (item.status == 'success') return 'table-success'
        if (item.status == 'info') return 'table-info'
        if (item.status == 'danger') return 'table-danger'
        if (item.status == 'primary') return 'table-info'
        if (item.status == 'warning') return 'table-warning'
        if (item.status == 'active') return 'table-warning'
      }
    },
    watch: {
      'openedWindow':{
        immediate: false,
        handler() {
         if(this.openedWindow.closed){
           this.closeVideo();
         }
        }
      },
      '$store.state.ytuber.user':{
        immediate: false,
        handler() {
         if(this.$store.state.ytuber.user.active_account){
           this.noActiveChannel = false;
         }
        }
      },
      accounts: {
        immediate: false,
        handler() {
          this.fetchDataTasks();
        }
      },
    },
    computed: {
/*      currentPage: {
        get: function () {
          return this.initialPage;
        },
        set: function (value) {

          if (this.initialPage == value) {
            return false;
          }

          console.log('route page push /work/list/' + value);

          if (value == 1) {
            this.$router.push({name: 'work.list'}).catch(() => {
            });
          } else {
            this.$router.push({name: 'work.list', params: {initialPage: value}});
          }
        }
      },*/
      accounts: function () {
        return this.$store.state.ytuber.accounts
      },
      tasks: function () {
        return this.$store.state.ytuber.work
      },
      maketMode: function () {
        return this.$store.state.ytuber.maketMode
      },
    },
  }
</script>
<style>
  .alert-primary {
    background-color: #d9ebfa !important;
  }

  .border-top {
    border: 1px solid #DDD;
  }

  .overlay-header.show {
    z-index: 99;
  }

  .block.block-themed > .block-header {
    color: #313131;
  }

  table#tasks td.title span {
    cursor: pointer;
  }

  ul.pagination li.page-item.pagination-page-nav {
    float: left;
    display: block;
    width: 44px
  }

  ul.pagination {
    display: block
  }

  ul.pagination li.page-item.pagination-next-nav,
  ul.pagination li.page-item.pagination-prev-nav {
    max-width: 40px;
    max-height: 38px;
    float: left
  }
</style>
