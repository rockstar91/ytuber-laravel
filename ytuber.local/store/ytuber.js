//import axios from "axios";

export const state = () => ({
  maketMode:true,
  chatSize:'25%',
  videoSize:'65%',
  category: [],
  categories: [],
  news: [],
  lates_completed: [],
  refSources: [],
  taskTypes: [],
  taskType: 1,
  tasks: [],
  task: [],
  edit: [],
  work: [],
  user: [],
  accounts: [],
  messages:[],
  refferals: [],
  dashboard: [],
  watchClient: {},
  ytPlayer: {
    time: 0,
    max:0,
  },
});

export const mutations = {
  changeStatusToPrimary(state, task) {
    let newDat = state.work;
    for (let key in newDat.data) {
      if(newDat.data[key].id == task.id){
        newDat.data[key].status = 'primary';
        return state.work = newDat;
      }
    }
  },
  clearWorkStatus(state,task) {
    let newDat = state.work;
    for (let key in newDat.data) {
      if(newDat.data[key].id == task.id) {
        if (newDat.data[key].completions && newDat.data[key].completions[0]) {
          if (newDat.data[key].completions[0].status == 2) {
            newDat.data[key].status = 'active';
          }
          if (newDat.data[key].completions[0].status == 3) {
            newDat.data[key].status = 'warning';
          }
          if (newDat.data[key].completions[0].status == 4) {
            newDat.data[key].status = 'success';
          }
          if (newDat.data[key].completions[0].status == 5) {
            newDat.data[key].status = 'danger';
          }
          if (newDat.data[key].completions[0].status == 6) {
            newDat.data[key].status = 'danger';
          }
        }
        else{
          newDat.data[key].status = null;
        }
      }
    }
    return state.work = newDat
  },
  categories(state, data) {
    return state.categories = data
  },
  news(state, data) {
    return state.news = data
  },
  lates_completed(state, data) {
    return state.lates_completed = data
  },
  messages(state, data) {
    return state.messages = data
  },
  category(state, data) {
    return state.category = data
  },
  refSources(state, data) {
    return state.refSources = data
  },
  taskTypes(state, data) {
    return state.taskTypes = data
  },
  taskType(state, data) {
    return state.taskType = data
  },
  tasks(state, data) {
    let articles = data;
    for (let key in articles.data) {
      articles.data[key]._showDetails = false;
    }
    return state.tasks = articles;
  },
  work(state, data) {
    let newDat = data;
    for (let key in newDat.data) {
      newDat.data[key].status = null;
      if (newDat.data[key].completions && newDat.data[key].completions[0]) {
        if (newDat.data[key].completions[0].status == 2) {
          newDat.data[key].status = 'active';
        }
        if (newDat.data[key].completions[0].status == 3) {
          newDat.data[key].status = 'warning';
        }
        if (newDat.data[key].completions[0].status == 4) {
          newDat.data[key].status = 'success';
        }
        if (newDat.data[key].completions[0].status == 5) {
          newDat.data[key].status = 'danger';
        }
        if (newDat.data[key].completions[0].status == 6) {
          newDat.data[key].status = 'danger';
        }
      }
    }
    return state.work = newDat
  },
  user(state, data) {
    return state.user = data
  },
  edit(state, data) {
    return state.edit = data
  },
  task(state, data) {
    return state.task = data
  },
  refferals(state, data) {
    return state.refferals = data
  },
  refferals_dashboard(state, data) {
    return state.dashboard.refferals = data
  },
  accounts(state, data) {
    return state.accounts = data
  },
  maketMode(state, data) {
    return state.maketMode = data
  },
  detailTaskOpen(state,data){
      console.log(data.id);
      let articles = state.tasks;
      for (let key in articles.data) {
        if (data.id == articles.data[key].id) {
          articles.data[key]._showDetails = !articles.data[key]._showDetails;
        }
      }
      return state.tasks = articles;
  },
  message(state, data) {
    let newDat = state.messages.data;
    let checkDublicated = true;
    for (let key in newDat) {
      if(newDat[key].id == data.id){
        checkDublicated = false;
      }
    }
    if(checkDublicated){
      state.messages.data.push(data);
    }
    return state.messages;
  },

  chatSize(state) {
    if(state.chatSize == '25%'){
      return state.chatSize = '100%';
    }
    else{
      return state.chatSize = '25%';
    }

  },
  videoSize(state) {
    if(state.videoSize == '25%'){
      return state.videoSize = '65%';
    }
    else{
      return state.videoSize = '25%';
    }
  },
  dashboard(state, data) {
    return state.dashboard = data
  },
  YtPlayerTimeIncrement(state, data) {
    return state.ytPlayer = data
  },
  watchClient(state, data) {
    return state.watchClient = data
  },
}

export const actions = {
  detailTaskOpen({commit},data){
    commit('detailTaskOpen', data)
  },
  setCategory({commit},data){
    commit('category', data)
  },
  async sendMessage({commit},data){
    commit('message', data)
  },
  setChatSize({commit}){
    commit('chatSize')
  },
  setVideoSize({commit}){
    commit('videoSize')
  },
  setMaketMode({commit},data){
    commit('maketMode', data.mode)
  },
  async getNews({commit}) {

    await this.$axios.$get(process.env.site + '/api/get-news').then(res => {
      commit('news', res);

    }).catch(e => {
      if (e.response && e.response.status === 401) {

        window.location.href = "/";
      }
    })
  },
  async getMessages({commit}) {

    await this.$axios.get('/api/get-messages-main-chat').then(res=>{

      commit('messages', res);
    });
  },
  async getLatesCompleted({commit}) {

    await this.$axios.$get(process.env.site + '/api/latest-completed').then(res => {

      commit('lates_completed', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })

  },
  async getCategories({commit}) {

    await this.$axios.$get(process.env.site + '/api/task-category/get-list').then(res => {

      commit('categories', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })

  },
  async getDashboard({commit}) {

    await this.$axios.$get(process.env.site + '/api/dashboard').then(res => {

      commit('dashboard', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })

  },
  async getReferralSources({commit}) {

    await this.$axios.$get(process.env.site + '/api/referral-source/get-list').then(res => {

      commit('refSources', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })

  },
  async getTaskTypes({commit}) {

    await this.$axios.$get(process.env.site + '/api/task-type/get-list').then((res) => {

      commit('taskTypes', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })

  },
  async getWatchVideo({commit}, settings) {

    await this.$axios.$get(process.env.site + '/api/get-watch-video').then(res => {

      commit('watchClient', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })

  },
  async getTasks({commit}, settings) {
    let page = 1;
    if(settings.page){
      page = settings.page
    }

    await this.$axios.$get(process.env.site + '/api/task/get-list?page=' + page + '&mine=' + settings.mine + '&task_type=' + settings.task_type).then(res => {

      commit('tasks', res)
    }).catch(e => {
      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })

  },
  async getUserRefferals({commit},settings) {

    let page = 1;

    if(settings.page){
      page = settings.page
    }

    await this.$axios.$get(process.env.site + '/api/get-refferals'+'?&page='+page).then(res => {

      commit('refferals', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })
  },
  async getUserRefferalsLatest({commit}) {

    await this.$axios.$get(process.env.site + '/api/get-refferals-latest').then(res => {

      commit('refferals_dashboard', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })
  },
  async getWork({commit}, settings) {

    this.$axios.$get(process.env.site + '/api/work/get-list?page=' + settings.page + '&task_type=' + settings.task_type).then(res => {

      commit('work', res)
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })


  },
  async setEdit({commit},params) {

    await this.$axios.$get(process.env.site + '/api/task/edit/' + params.id).then(res => {

      commit('task', res);
      commit('edit', res);
    }).catch(e => {

      if (e.response && e.response.status === 401) {
        window.location.href = "/";
      }
    })
  },
  async setTask({commit},task) {
    commit('task', task);
  },
  async getUser({commit}) {

    await this.$axios.$get(process.env.site + '/api/user').then(res=>{

      commit('user', res);

    })

  },
  async getAccounts({commit}) {

    await this.$axios.$get(process.env.site + '/api/account/get-list').then(res=>{

      commit('accounts', res)
    })

  },
  async setYtPlayerTimeIncrement({commit}, data) {
    commit('YtPlayerTimeIncrement',data);
  },
  async setTaskType({commit}, data) {
    commit('taskType',data);
  },
}
