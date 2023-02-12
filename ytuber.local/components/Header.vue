<template>
  <div class="card" style="padding: 0;margin:0">
    <div class="page-header page-header-light border-bottom-0">
      <div class="page-header-content header-elements-md-inline header-light">
        <div class="page-title d-flex">
          <h4><i class="icon-youtube icon-2x mr-2"></i> <span class="font-weight-semibold">{{ $store.state.data.meta.description }}</span></h4>
          <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
        </div>
        <CompleteForPublic/>
      </div>
      <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
          <div>
            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
              <div class="d-flex">
                <div class="breadcrumb bg-light">
                  <nuxt-link to="/" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Ytuber</nuxt-link>
                  <span href="#" class="breadcrumb-item active">{{ $store.state.data.meta.title }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
<!--      <div class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="text-center d-lg-none w-100">
          <button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse"
                  data-target="#navbar-header">
            <i class="icon-menu7 mr-2"></i>
            Меню
          </button>
          <div class="navbar-collapse collapse" id="navbar-header">
            <ul class="navbar-nav ml-lg-auto">
              <li class="nav-item"><nuxt-link to="/" class="navbar-nav-link"><i class="icon-home2 mr-2"></i> Главная</nuxt-link></li>
              <li class="nav-item"><nuxt-link to="/task/list" class="navbar-nav-link"><i class="icon-file-text2 mr-2"></i> Мои задачи</nuxt-link></li>
              <li class="nav-item"><nuxt-link to="/payments" class="navbar-nav-link font-weight-semibold"><span class="text-pink-400"><i class="icon-cart2 mr-2"></i> Пополнить баланс</span></nuxt-link></li>
            </ul>
          </div>
        </div>
      </div>-->
    </div>
  </div>
</template>
<script>
export default{
  data() {
    return {
      class: '',
      items: [
        {
          text: this.$store.state.data.meta.title,
          href: '#'
        },
      ]
    }
  },
  mounted() {
  },
  methods: {
  },
  computed: {
  }
}
</script>
