<template>


    <!--
    <b-button v-b-modal.modal-1>Launch demo modal</b-button>

    <b-modal id="modal-1" title="BootstrapVue">
        <p class="my-4">Hello from modal!</p>
    </b-modal>
    -->

  <!-- Basic modal -->
  <div id="deleteModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Удаление задачи #{{id}}</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
          <div class="text-center" v-if="imageUrl">
            <img :src="imageUrl" :alt="name" width="60" />
          </div>
          <p class="text-center"><b>{{name}}</b> {{typeName}}</p>
          <p class="text-center text-danger">Вы уверены что хотите удалить задачу?</p>
          <p class="text-center">После удаления, вы не сможете больше редактировать задачу, сделать возраты и статистика по этой задаче будет не доступна. Остаток баланса задачи будет возвращен на основной баланс.</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-alt-success" data-dismiss="modal">Закрыть</button>
          <button type="button" class="btn btn-alt-danger" data-dismiss="modal" @click="deleteTask(id)">
            <i class="fa fa-check"></i> Удалить
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- /basic modal -->
</template>

<script>
   // import Youtube from "../mixins/Youtube";

    export default {
        //mixins: [Youtube],
        name: "TaskDeleteModal",
        props: {
            removableTask: {
                type: [Object, Array],
                default: {}
            },
            afterDeleted: {
                type: [Object, Function],
                default: function () {
                    return {};
                }
            }
        },
        data() {
            return {}
        },
        mounted() {
            //
        },
        methods: {
            async deleteTask(id) {
                await this.$axios.$get(process.env.site+'/api/task/delete/'+id)
                    .then((response) => {
                        //console.log(response);
                        //this.afterDeleted(response);
                      this.$root.$emit('updateBalance');

                      // this.$store.dispatch('ytuber/getTasks',settings).then(()=>{
                        // })

                      this.tasksUpdate();
                      this.$root.$emit('update:isLoading', true);

                        if(response.url){
                          if(response.type_id != 4){
                        this.$toast.success('<h1>Задача '+response.name+' удалена! ' +
                          '<img class="rounded float-left mr-2" ' +
                            'src="https://img.youtube.com/vi/'+this.parseVideoId(response.url)+'/default.jpg"' +'width="120" '+ 'height="90"'+ ' />'+
                          '</br> На баланс задачи возвращено ' + response.total_cost + ' баллов</h1>',
                          {
                            duration: 3500
                          }
                        );
                          }
                          else{
                            this.$toast.success('<h1>Задача '+response.name+' удалена! ' +
                              '<img class="rounded float-left mr-2" ' +
                              'src="'+response.youtube_extend.snippet.thumbnails.medium.url+'"' +'width="120" '+ 'height="90"'+ ' />'+
                              '</br> На баланс задачи возвращено ' + response.total_cost + ' баллов</h1>',
                              {
                                duration: 3500
                              });
                          }

                        }
                      this.$root.$emit('update:isLoading', false);
                    })
                    .catch((error) => {
                      this.$root.$emit('update:isLoading', false);
                        console.log(error);
                    });
            },
            async tasksUpdate(){
              this.$root.$emit('update:isLoading', true);
            //  await this.$store.dispatch('ytuber/getTasks',settings).then(()=>{
                this.$root.$emit('updateMyTasks');
                this.$root.$emit('updateWorkTasks');
                this.$root.$emit('isLoading', false);

             // })
            }
        },
        computed: {
            id: function() {
                return this.removableTask.id || 0;
            },
            name: function () {
                return this.removableTask.name || '';
            },
            url: function() {
                return this.removableTask.url || '';
            },
            typeName: function () {
                if(typeof this.removableTask.tasks_type != 'undefined') {
                    console.log('!undefined');
                    return this.removableTask.tasks_type.name;
                }
            },
            imageUrl: function () {
              if(this.url){
                let vid = this.parseVideoId(this.url);
                return vid ? 'https://img.youtube.com/vi/'+vid+'/default.jpg' : vid;
              }
            }
        }
    }
</script>

<style scoped>
</style>
