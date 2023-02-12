import Vue from 'vue';
import { Picker, EmojiIndex } from "emoji-mart-vue-fast";
Vue.use(Picker);
Vue.component('Picker', Picker);

import emojiRegex from 'emoji-regex'