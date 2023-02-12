<template>
  <div class="content">
    <br/>
    <TaskDeleteModal v-if="taskList" :removable-task.sync="removableTask"
                     :after-deleted="afterDeleted"></TaskDeleteModal>
    <br/>
    <TaskFilter></TaskFilter>
    <b-card>
      <b-table striped responsive
               v-if="taskList && taskList.length>0 && maketMode"
               :fields="fields"
               :items="taskList"
               :current-page="currentPage"
               id="tasks"
               class="table-vcenter" show-empty empty-text="Задачи не найдены"
               emptyFilteredText="Задачи этого типа не найдены">

        <template v-slot:head(image)="row">
          <i class="fa fa-image"></i>
        </template>
        <template v-slot:empty="scope">
          <h4>{{ scope.emptyText }}</h4>
        </template>

        <template v-slot:emptyfiltered="scope">
          <h4>{{ scope.emptyFilteredText }}</h4>
        </template>

        <template v-slot:cell(image)="row">
          <img v-if="row.item.type_id !=4"
               :src="'https://img.youtube.com/vi/'+parseVideoIdFromUrl(row.item.url)+'/default.jpg'"
               class="img-fluid img-thumbnail pull-left mr-4"
               width="60" height="45" alt="">
          <img v-else :src="fixImgPath(row.item.url)"
               class="img-fluid img-thumbnail pull-left mr-4"
               width="88" height="88" alt="">
        </template>

        <template v-slot:cell(name)="row">
          <span>{{ row.value }}</span>

          <b-progress :value="row.item.actionCompleted" variant="success"
                      :max="row.item.actionTarget"
                      :animated="false" :striped="false" class="mt-2" style="height: 8px;">
          </b-progress>
        </template>

        <template v-slot:cell(disabled)="row">

          <b-form-checkbox v-if="!row.item.disabled" @change="changeStatus(row.item)" :checked="true" :value="true"
                           name="check-button" switch>Включено
          </b-form-checkbox>
          <b-form-checkbox v-else @change="changeStatus(row.item)" :checked="false" :value="true" name="check-button"
                           switch>Выключено
          </b-form-checkbox>

        </template>
        <template v-slot:cell(action_done)="row">
          <span class="badge badge-info">{{ row.item.actionCompleted }} / {{ row.item.actionTarget }}</span>
        </template>
        <template v-slot:cell(tasks_type)="row">
          <div style="min-width:130px">
            <b v-if="row.item.task_type.name">{{ $t(row.item.task_type.name) }}</b>
            <i v-if="row.item.task_type.name == 'YT Лайки' && row.item.extend.type == 1"
               class="pull-left icon-thumbs-up2 ml-2"/>
            <i v-if="row.item.task_type.name == 'YT Лайки' && row.item.extend.type == 0"
               class="pull-left  icon-thumbs-down2 ml-2"/>
            <i v-if="row.item.task_type.name == 'YT Просмотры'" class="pull-left icon-youtube ml-2"/>
            <i v-if="row.item.task_type.name == 'YT Подписчики'" class="pull-left icon-user-check ml-2"/>
            <i v-if="row.item.task_type.name == 'YT Комментарии'" class="pull-left icon-user-check ml-2"/>
            <i v-if="row.item.task_type.name == 'YT Лайки к комментам'" class="pull-left icon-bubbles10 ml-2"/>
            <i v-if="row.item.task_type.name == 'YT Ответы к комментариям'" class="pull-left icon-user-check ml-2"/>
            <hr class="mt-2 mb-3"/>
            <div class="mt-2">
                    <span>
                      <b-button @click="detailMake(row.item)">Подробнее</b-button>
                    </span>
            </div>
          </div>
        </template>
        <template #row-details="row">
            <div  style="max-height: 634px;overflow: scroll;">
          <h3>Кто выполнял задачу</h3>
             <TaskDetailCompletions :task_id="row.item.id" />
            </div>
            <b-button class="mt-2" size="sm" @click="detailMake(row.item)">Скрыть подробнее</b-button>
        </template>
        <template v-slot:cell(manage)="row" colspan="2">
          <b-button-group>
            <b-button size="lg" @click="refundPoints(row.item)"
                      variant="outline-primary" data-toggle="tooltip" title="" data-original-title="Пересчитать">
              <i class="icon-reload-alt"></i>
            </b-button>
            <b-button class="mr-2" size="lg" @click="setEditTask(row.item)"
                      variant="outline-primary" data-toggle="tooltip" title="" data-original-title="Редактировать">
              <i class="icon-pencil"></i>
            </b-button>
            <b-button size="lg" data-toggle="modal" data-target="#deleteModal" @click="deleteTask(row.item)"
                      variant="danger" title="" data-original-title="Удалить">
              <i class="icon-cross"></i>
            </b-button>
          </b-button-group>
        </template>
      </b-table>
      <div v-else class="row">
        <div class="col-md-4" v-for="(task,index) in taskList" :key="index">
          <div class="card border-left-3 border-left-grey-300 rounded-left-0">
            <div class="card-body">
              <div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
                <div class="mr-4" style="width: 100%;">
                  <img v-if="task.type_id !=4"
                       :src="'https://img.youtube.com/vi/'+parseVideoIdFromUrl(task.url)+'/default.jpg'"
                       class="img-fluid img-thumbnail pull-left mr-4"
                       width="60" height="45" alt="">
                  <img v-else :src="fixImgPath(task.url)"
                       class="img-fluid img-thumbnail pull-left mr-4"
                       width="88" height="88" alt="">
                  <h6>{{ task.name }}</h6>

                  <b-progress :value="task.actionCompleted" variant="success"
                              :max="task.actionTarget"
                              :animated="false" :striped="false" class="mt-2" style="height: 8px;">
                  </b-progress>
                  <p>Бюджет: {{ task.total_cost }}</p>
                </div>
                <ul class="list list-unstyled mb-0 mt-3 mt-sm-0 ml-auto" style="min-width: 90px;">
                  <li>
                    Цена: {{ task.action_cost }}
                  </li>
                  <li>
                    {{ $t(task.task_type.name) }}
                    <i v-if="task.task_type.name == 'YT Лайки' && task.extend.type == 1"
                       class="pull-left icon-thumbs-up2 ml-2"/>
                    <i v-if="task.task_type.name == 'YT Лайки' && task.extend.type == 0"
                       class="pull-left  icon-thumbs-down2 ml-2"/>
                  </li>
                  <li>
                    <span class="badge badge-info">{{ task.actionCompleted }} / {{ task.actionTarget }}</span>
                  </li>
                  <li>
                    <b-button-group>
                      <b-form-checkbox v-if="!task.disabled" @change="changeStatus(task)" :checked="true" :value="true"
                                       name="check-button" switch>Включено
                      </b-form-checkbox>
                      <b-form-checkbox v-else @change="changeStatus(task)" :checked="false" :value="true"
                                       name="check-button" switch>Выключено
                      </b-form-checkbox>
                    </b-button-group>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-footer d-sm-flex justify-content-sm-between align-items-sm-center">
              <b-button-group>
                <b-button size="lg" @click="refundPoints(task)"
                          variant="outline-primary" data-toggle="tooltip" title="" data-original-title="Пересчитать">
                  <i class="icon-reload-alt"></i>
                </b-button>
                <b-button size="lg" @click="setEditTask(task)"
                          variant="outline-primary" data-toggle="tooltip" title="" data-original-title="Редактировать">
                  <i class="icon-pencil"></i>
                </b-button>
                <b-button size="lg" data-toggle="modal" data-target="#deleteModal" @click="deleteTask(task)"
                          variant="danger" title="" data-original-title="Удалить">
                  <i class="icon-cross"></i>
                </b-button>
              </b-button-group>
            </div>
          </div>
        </div>
      </div>
      <h1 class="text-center" v-if="taskList.length == 0">
        Вы еще не добавили, этого типа задач.
      </h1>
    </b-card>
    <b-card v-if="tasks && tasks.total>10">
      <pagination :data="tasks" @pagination-change-page="fetchDataTasks" class="mb-4"></pagination>
    </b-card>
    <div class="form-group">
      <b-button variant="success" @click="$router.push({path: '/task/create'})">
        <i class="icon-plus3 mr-2"></i>Добавить задачу
      </b-button>
    </div>
  </div>
</template>

<script>
//import Youtube from "@/mixins/Youtube";

export default {
  // mixins: [Youtube],
  props: {},

  data() {
    return {
      site: process.env.site,
      fields: [
        {key: 'image', label: 'img', class: 'text-center d-sm-table-cell', thStyle: {width: '100px'}},
        {key: 'name', label: 'Название', tdClass: 'd-flex d-md-table-cell', thStyle: {'max-width': '140px'}},
        {key: 'total_cost', label: 'Бюджет', class: 'd-none  d-sm-table-cell', thStyle: {width: '80px'}},
        {key: 'action_cost', label: 'Цена', class: 'text-center', thStyle: {width: '80px'}},
        {key: 'disabled', label: 'Статус', class: 'text-center', thStyle: {width: '80px'}},
        {key: 'action_done', label: 'Выполнено', class: 'd-none d-sm-table-cell', thStyle: {width: '100px'}},
        {key: 'tasks_type', label: 'Тип', class: 'd-none d-sm-table-cell', thStyle: {width: '150px'}},
        {key: 'manage', label: 'Редактировать', class: 'text-center d-sm-table-cell', thStyle: {width: '100px'}},
      ],
      tasks: {},
      taskListRaw: [],
      pager: [],
      noTask: false,
      currentPage: 1,
      removableTask: [],
      currentFilterTaskType: 0,
      mine: true,
    }
  },
  mounted() {
    let metadata = {
      title: 'Мои задачи',
      description: 'Редактировать свои задачи'
    };
    this.$store.dispatch('data/setMeta', metadata);
    this.checkRouterPage();
    this.$nuxt.$on('currentFilterTaskType', (data) => {
      if ($nuxt.$route.path == "/task/list") {
        this.currentFilterTaskType = data;
        this.fetchDataTasks();
      }
    });
    this.isMobile();
    this.$nuxt.$on('updateMyTasks', () => {
      this.fetchDataTasks();
    });
    this.fetchDataTasks();
  },
  methods: {
    detailMake(item) {
      this.$store.dispatch('ytuber/detailTaskOpen', item);
    },
    fixImgPath(url) {
      if (url) {
        let img = url.replace('https://www.youtube.com/channel/', this.site + '/images/');
        return img + '.jpg';
      }
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
      if (url.toString().includes('shorts/')) {
        vid = url.toString().substring(pox + 7, url.length)
      }
      if (vid.length == 11)
        return vid;
      else
        return null;
    },
    switcherChecker(status) {
      if (status == 1) {
        return true
      } else {
        return false
      }
    },
    changeStatus(task) {
      this.$axios.$get('/api/change-status-task/' + task.id).then(res => {
        console.log(res);
        this.fetchDataTasks();
      })
    },
    isMobile() {
      if (window.innerWidth <= 760) {
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
    deleteTask(task) {
      this.removableTask = task;
      // jQuery('#deleteModal').modal('show');
    },
    async setEditTask(item) {
      console.log('set-edit');
      await this.$store.dispatch('ytuber/setEdit', item);
      this.$router.push('/task/edit/' + item.id);
    },
    afterDeleted(response) {
      //this.$toast.success('Задача #' + response.data.id + ' успешно удалена');
      this.removableTask = {};
      this.fetchDataTasks();
    },
    refundPoints(task) {
      this.$axios.get('/api/refund-task/' + task.id).then(res => {
        console.log(res);
      })
    },
    checkRouterPage() {
      this.$root.$emit('isLoading', true);
      this.currentPage = this.$route.params.page > 0 ? this.$route.params.page : 1;
    },

    getCurrentTaskCompleteCount(totalCost, actionCost) {
      return Math.floor(totalCost / actionCost);
    },

    calcTaskTotalCompleted(totalCost, actionCost, actionCompleted, actionFail) {
      //$action_done = $item->action_done - $item->action_fail - $item->action_refund;
      //$action_total = floor($item->total_cost / $item->action_cost) + $action_done;
    },

    async fetchDataTasks(page = 1) {
      this.$root.$emit('isLoading', true);
      let settings = {
        'task_type': this.currentFilterTaskType,
        'mine': true,
        'page': page,
      };
      await this.$store.dispatch('ytuber/getTasks', settings).then(() => {
        this.taskList = this.$store.state.ytuber.tasks.data;
        this.tasks = this.$store.state.ytuber.tasks;
        this.$root.$emit('isLoading', false);

      })
    },

    calcActionTarget(task) {
      return Math.floor(task.total_cost / task.action_cost) + parseInt(task.action_done);
    },
    calcActionCompleted(task) {
      //return task.action_done - task.action_fail - task.action_refund;
      return task.action_done
    }
  },

  computed: {
    maketMode() {
      return this.$store.state.ytuber.maketMode;
    },
    taskList: {
      get: function () {
        for (let key in this.taskListRaw) {
          //   console.log(this.taskListRaw[key]);
        }
        if (this.taskListRaw) {
          this.taskListRaw.forEach((item) => {
            item.actionTarget = this.calcActionTarget(item);
            item.actionCompleted = this.calcActionCompleted(item);
            // console.log(item);
          });
        } else {
          this.$router.push('login')
        }
        return this.taskListRaw;
      },
      set: function (data) {
        this.taskListRaw = data;
      }
    }
  },

  watch: {
    page: {
      immediate: true,
      deep: true,
      handler() {
        this.fetchDataTasks();
      }
    },
  },
  /*    components: {
        'TaskDeleteModal': require("../Elements/TaskDeleteModal.vue").default,
        TaskFilter,
      }*/
}
</script>


