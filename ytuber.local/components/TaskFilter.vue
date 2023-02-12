<template>
  <b-container fluid>
  <!-- Filter toolbar -->
  <div class="navbar navbar-expand-lg navbar-light navbar-component rounded">
    <div class="text-center d-lg-none w-100">
      <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-filter">
        <i class="icon-unfold mr-2"></i>
        Фильтр задач:
      </button>
    </div>

    <div class="navbar-collapse collapse" id="navbar-filter">
      <ul class="navbar-nav flex-wrap">
        <li v-if="taskTypeList.length > 0" :style="borderStyle" v-for="(taskFilter,index) in taskTypeList" :key="index" :class="'list-group-item navbar-nav-link '+checkActive(taskFilter.id)" @click="currentFilterTaskType(taskFilter.id)">
          {{$t(taskFilter.name)}}
        </li>
      </ul>
    </div>
    <span class="navbar-text font-weight-semibold ml-md-auto">

            <b-form-checkbox v-model="maketMode" name="check-button" switch>
              Макет:
            </b-form-checkbox>
								</span>
  </div>
  </b-container>
</template>

<script>
  export default {
    name: "TaskFilter",
    data(){
      return{
        maketMode:false,
        borderStyle: {
          'border-top':'',
          'border-top-width': '1px'
        },
      }
    },
    mounted() {
      this.maketMode = this.$store.state.ytuber.maketMode;
    },
    methods: {
      firstStyle(){
        if(this.taskType == 1) {
          this.borderStyle["border-top"] = '1px #2196f3 solid';
          this.borderStyle["border-top-width"] = '1px';
        }
        else{
          this.borderStyle["border-top"] = '';
          this.borderStyle["border-top-width"] = '1px';
        }
      },
      checkActive(id){
          if(id == this.taskType){
            return 'active';
          }

      },
      currentFilterTaskType(TaskType) {
        this.$store.dispatch('ytuber/setTaskType',TaskType);
        this.$nuxt.$emit('currentFilterTaskType', TaskType);
      },
    },
    computed: {
    taskTypeList: function(){
      return this.$store.state.ytuber.taskTypes;
    },
      taskType: function(){
        return this.$store.state.ytuber.taskType;
    },
    },
    watch: {
      maketMode: {
        immediate: false,
        handler() {
           let data = {
             mode: this.maketMode,
           };
           //console.log('maketMode filter changed '+this.maketMode);
            //this.$nuxt.$emit('makeMode', this.maketMode);
            this.$store.dispatch('ytuber/setMaketMode',data);
        }
      },
    }
  }
</script>

<style scoped>

</style>
