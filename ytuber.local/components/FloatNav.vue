<template>

  <!-- Top left menu -->
  <ul class="fab-menu fab-menu-absolute fab-menu-top-right" data-fab-toggle="hover" id="fab-menu-affixed-demo-right">
    <li>
      <a class="fab-menu-btn btn bg-indigo-400 btn-float rounded-round btn-icon">
        <i class="fab-icon-open icon-checkmark3"></i>
        <i class="fab-icon-close icon-cross2"></i>
      </a>

      <ul class="fab-menu-inner">
        <li>
          <div data-fab-label="Создать задачу">
            <a @click="$router.push('/task/create')" class="btn btn-light rounded-round btn-icon btn-float">
              <i class="icon-pencil"></i>
            </a>
          </div>
        </li>
        <li>
          <div data-fab-label="Просмотры">
            <a @click="$router.push('/views')" class="btn btn-light rounded-round btn-icon btn-float">
              <i class="icon-play"></i>
            </a>
          </div>
        </li>
        <li>
          <div data-fab-label="Чат пользователей">
            <a @click="$router.push('/chat')" class="btn btn-light rounded-round btn-icon btn-float">
              <i class="icon-bubbles9"></i>
            </a>
          </div>
        </li>
      </ul>
    </li>
  </ul>
  <!-- /top left menu -->

</template>
<script>
  export default {
    data () {
      return {
      }
    },
    mounted() {
    },
    methods:{
    }
  }
</script>
