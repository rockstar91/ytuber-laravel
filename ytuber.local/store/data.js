//import axios from "axios";

export const state = () => ({
    meta:{
        title:null,
        desciption:null,
    }
});

export const mutations = {
    setMeta(state,data){
        return state.meta = data;
    },
}

export const actions = {
    async setMeta({commit},meta){
        // console.log(meta);
        commit('setMeta', meta);
    },
}
