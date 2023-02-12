<template>
  <div>
    <TaskForm></TaskForm>
  </div>
</template>

<script>
  export default {
    //mixins: [Youtube],
    props: {
      taskId: {
        type: [Number, String],
        default: 0
      },
    },

    data() {
      return {
      }
    },
    mounted() {
      let metadata ={
        title:'Создать задачу',
        description:'создание задачи для ютуба'
      };
      this.$store.dispatch('data/setMeta',metadata);
    },
    methods: {

    },
    computed: {
      task(){
        return this.$store.state.ytuber.edit;
      }
    },
    components: {
      //'Multiselect': require('vue-multiselect').default,
    }
  }
</script>
<style>
</style>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
