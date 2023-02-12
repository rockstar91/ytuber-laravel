//import axios from "axios";

export const state = () => ({
  video: [],
  channel: [],

});

export const mutations = {
  setVideo(state, data) {
    return state.video = data
  },
  setChannel(state, data) {
    return state.channel = data
  },

}

export const actions = {
  async getVideoFromId ({commit}, settings) {
    await this.$axios.$get(process.env.site + '/api/get-video-info/'+settings.videoId).then(res => {
      commit('setVideo', res)
    }).catch(e => {
      if (e.response && e.response.status === 401) {
        window.location.href = "/login";
      }
    })
  },
  async getChannelFromId ({commit}, settings) {
    await this.$axios.$get(process.env.site + '/api/get-channel-info/'+settings.channelId).then(res => {
      commit('setChannel', res)
    }).catch(e => {
      if (e.response && e.response.status === 401) {
        window.location.href = "/login";
      }
    })
  },
  async getChannelFromName({commit}, settings) {
    await this.$axios.$get(process.env.site + '/api/get-channel-name-info/'+settings.channelName).then(res => {
      commit('setChannel', res)
    }).catch(e => {
      if (e.response && e.response.status === 401) {
        window.location.href = "/login";
      }
    })
  }

}
