import Vue from 'vue';
import data from 'emoji-mart-vue-fast/data/all.json'
import { Picker, EmojiIndex } from 'emoji-mart-vue-fast'
let emojiIndex = new EmojiIndex(data)
import 'emoji-mart-vue-fast/css/emoji-mart.css'
Vue.use(Picker);
