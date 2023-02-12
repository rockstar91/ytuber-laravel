<template>
  <b-container fluid>
<b-row v-if="showChat">
  <b-col>
  <b-card style="min-width: 310px;">
    <ul v-if="chatMessages.data && chatMessages.data.length>0" class="media-list media-chat media-chat-scrollable mb-3 messages" v-chat-scroll="{always: true, smooth: false}">
      <li :class="'media '+chatClass(i)+' mx-2 message'" v-for="(message,i) in chatMessages.data" :key="message.id">
        <div class="mr-3" v-if="!(i % 2 == 0)">
          <a v-if="message.data && !!message.data.user" target="_blank" :href="message.data.user.url" >
            <img :src="fixImgPath(message.data.user.url)" class="rounded-circle" alt="" width="40" height="40">
            <br />
            <small>{{message.data.user.title}}</small>
          </a>
          <br v-if="showDeleteMessage"/>
          <b-button @click="deleteMessage(message)" v-if="showDeleteMessage && $store.state.ytuber.user.id == message.user_id || showDeleteMessage && $store.state.ytuber.user.moderator" size="sm" variant="outline-danger"><i class="icon-cross3"></i>Удалить</b-button>
          <b-button @click="banUser(message.user_id)" v-if="showDeleteMessage && $store.state.ytuber.user.moderator" size="sm" variant="outline-danger"><i class="icon-cross3"></i>Забанить</b-button>
        </div>
        <div class="media-body" v-if="message.data">
          <div class="media-chat-item text-default">
            <p style="white-space: pre-wrap;font-size:16px" v-html="wrapEmoji(message.data.text)"></p>
            <b-card class="mt-1" v-if="message.data && message.data.youtube_video">
              <template #header>
                <div class="media-title" style="color: #000;">{{message.data.youtube_video.snippet.title}}
                </div>
              </template>
            <div class="card-img-actions" style="height: 80px; width: 108px;">
                <a :href="'https://www.youtube.com/watch?v='+message.data.youtube_video.id" target="_blank">
                  <img :src="message.data.youtube_video.snippet.thumbnails.default.url" class="img-fluid img-preview rounded" alt="">
                  <span class="card-img-actions-overlay card-img"><i class="icon-play3 icon-2x"></i></span>
                </a>
            </div>
            </b-card>
            <b-card class="mt-1" v-if="message.data && message.data.youtube_channel">
              <template #header>
                <div class="media-title" style="color: #000;">{{message.data.youtube_channel.snippet.title}}
                </div>
              </template>
              <div class="card-img-actions" style="height: 80px; width: 108px;">
                <a :href="'https://www.youtube.com/channel/'+message.data.youtube_channel.id" target="_blank">
                  <img :src="fixImgPath('https://www.youtube.com/channel/'+message.data.youtube_channel.id)" class="img-fluid img-preview rounded" alt="">
                  <span class="card-img-actions-overlay card-img"><i class="icon-play3 icon-2x"></i></span>
                </a>
              </div>
            </b-card>

          </div>
        </div>
        <div class="ml-3" v-if="i % 2 == 0 && message.data">
            <a target="_blank" :href="message.data.user.url" v-if="!!message.data.user">
              <img :src="fixImgPath(message.data.user.url)" class="rounded-circle" alt="" width="40" height="40">
              <br />
              <small @click="deleteMessage(message)">{{message.data.user.title}}</small>
            </a>
          <br v-if="showDeleteMessage"/>
          <b-button @click="deleteMessage(message)" v-if="showDeleteMessage && $store.state.ytuber.user.id == message.user_id || showDeleteMessage && $store.state.ytuber.user.moderator" size="sm" variant="outline-danger"><i class="icon-cross3"></i>Удалить</b-button>
          <b-button @click="banUser(message.user_id)" v-if="showDeleteMessage && $store.state.ytuber.user.moderator" size="sm" variant="outline-danger"><i class="icon-cross3"></i>Забанить</b-button>
        </div>
      </li>
    </ul>
    <b-card-footer v-if="chatEnabled && this.user.channel">
      <div class="mb-4">
        <span v-if="isActive"><b-badge variant="secondary">{{isActive.name}} набирает сообщение...</b-badge></span>
      </div>
      <b-card style="position: absolute; bottom: 258px; left: 40px;" v-if="youtubeVideoData && youtubeVideoData.snippet && showVideoImg">
        <div class="card-img-actions" style="height: 80px; width: 108px;">
          <a :href="'https://www.youtube.com/watch?v='+youtubeVideoData.id" target="_blank">
            <img :src="youtubeVideoData.snippet.thumbnails.default.url" class="img-fluid img-preview rounded" alt="">
            <span class="card-img-actions-overlay card-img"><i class="icon-play3 icon-2x"></i></span>
          </a>
        </div>
      </b-card>
      <b-card style="position: absolute; bottom: 258px; left: 40px;" v-if="youtubeChannelData && youtubeChannelData.snippet && showChannelImg">
        <div class="card-img-actions" style="height: 80px; width: 108px;">
          <a :href="'https://www.youtube.com/channel/'+youtubeChannelData.id" target="_blank">
            <img :src="fixImgPath('https://www.youtube.com/channel/'+youtubeChannelData.id)" class="img-fluid img-preview rounded" alt="">
            <span class="card-img-actions-overlay card-img"><i class="icon-play3 icon-2x"></i></span>
          </a>
        </div>
      </b-card>
      <b-input-group class="mb-2 mr-sm-2 mb-sm-0">
        <b-button class="smileBtn" size="sm" variant="outline-light" @click="switchEmoticon" style="position: absolute;left: -39px;top: -14px;z-index: 1;">
        <img data-text="😄" id="emoticon-btn" alt=":smile:" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class="emoji-text emoji-set-twitter emoji-type-image" style="background-position: 53.33% 40%">
        </b-button>
          <b-form-textarea style="font-size: 16px" @keydown.enter="clicked1" v-model="textMessage" @keydown="userAction" class="mb-2" size="sm">
          <div>
            <button>press me</button>
            <span contenteditable="true">editable</span>
          </div>
        </b-form-textarea>
      </b-input-group>
      <Picker style="position: absolute;z-index: 1;" v-if="emoticon_switcher" title="Иконки" :data="emojiIndex" set="twitter" @select="showEmoji" :i18n="{search: 'Поиск', notfound: 'Эмотиконы не найдены', categories: {search: 'Результаты поиска',recent: 'Часто используемые',smileys: 'Смыйлики & Эмотиконы',people: 'Люди & Тело',nature: 'Животные & Природа',foods: 'Еда & Напитки',activity: 'Активность',places: 'Путешествия & Места',objects: 'Объекты',symbols: 'Символы',flags: 'Флаги',custom: 'Индивидуальное',}}" />
    <b-input-group class="mb-2 mr-sm-2 mb-sm-0" style="max-width: 540px;">
      <b-input-group-prepend is-text>
        <i class="icon-youtube2"></i>
      </b-input-group-prepend>
    <b-form-input class="float-left" type="text" v-model="youtubeVideo" placeholder="Вставить ролик"></b-form-input>
    </b-input-group>
      <b-input-group class="mb-2 mr-sm-2 mb-sm-0 mt-2" style="max-width: 540px;">
        <b-input-group-prepend is-text>
          <i class="icon-feed3"></i>
        </b-input-group-prepend>
    <b-form-input class="float-left" type="text" v-model="youtubeChannel" placeholder="Вставить канал"></b-form-input>
    </b-input-group>
      <div>
    <b-button class="mt-2 mb-2 float-right" variant="outline-primary" @click="sendMessage"><i class="icon-bubble9 mr-2"></i>Отправить</b-button>
      <b-button class="mt-2" @click="resizeChat" variant="outline-secondary"><i class="icon-arrow-resize7"></i></b-button>
      <b-button class="mt-2" @click="showDeleteMessages" variant="outline-secondary"><i class="icon-cog3"></i></b-button>
      </div>
    </b-card-footer>
    <b-card-footer v-if="activeUsers.length==0">
      <b-button @click="chatRoomEnter" variant="outline-primary">Присоединиться к чату</b-button>
    </b-card-footer>
  </b-card>
  </b-col>
  <b-col cols="2" v-if="activeUsers.length>0" class="media-chat-scrollable" style="float: right; min-width: 210px">
    <b-card-group>
          <b-card class="mt-2" style="max-width: 200px;min-width:200px;width: 25%; float:left;margin: 0px" v-for="(user,i) in activeUsers" :key="i">
        <div class="mr-3">
        <div class="media" style="margin: 0px">
          <a target="_blank" :href="user.url">
            <img :src="fixImgPath(user.url)" style="max-height: 40px;" class="rounded-circle card-img-top img-fluid" alt="" width="40" height="40">
          </a>
        </div>
        </div>
        <div class="media-body">
          <span class="mt-2 float-left">
          {{user.title}}
              <b-button @click="banUser(user.user_id)" v-if="showDeleteMessage && $store.state.ytuber.user.moderator" size="sm" variant="outline-danger"><i class="icon-cross3"></i>Забанить</b-button>
          </span>
        </div>
          </b-card>
    </b-card-group>
  </b-col>
</b-row>
  </b-container>
</template>

<script>
import Vue from 'vue';
import { Picker, EmojiIndex } from "emoji-mart-vue-fast";
Vue.use(Picker);
import data from "emoji-mart-vue-fast/data/all.json";
let emojiIndex = new EmojiIndex(data);
import "emoji-mart-vue-fast/css/emoji-mart.css";
import emojiRegex from 'emoji-regex';

export default {
  data(){
    return{
        emojiIndex: emojiIndex,
        emojisOutput: "",
        emoticon_switcher:false,
        site:process.env.site,
        showDeleteMessage: false,
        showChannelImg: false,
        showVideoImg: false,
        showChat:false,
        activeUsers:[],
        textMessage:'',
        youtubeChannel:'',
        youtubeVideo:'',
        youtubeVideoData:'',
        youtubeChannelData:'',
        isActive:false,
        typingTimer:false,
        search: '',
        chatEnabled:true,
        userModerator:false,
        site:process.env.site
    }
  },
  mounted() {
    this.connectChat();
    this.$store.dispatch('ytuber/getMessages');
  },
  beforeDestroy () {
    this.$echo.leave('presence-room.1')
  },
  methods:{
    showYoutubeVideoImg() {
      console.log('show video');
      this.showVideoImg = true;
      setInterval(() => {
        this.showVideoImg = false;
      }, 7000);
    },
    showYoutubeChannelImg(){
      console.log('show channel');
      this.showChannelImg = true;
      setInterval(() => {
        this.showChannelImg = false;
      }, 7000);
    },
    switchEmoticon(){
      this.emoticon_switcher = !this.emoticon_switcher;
    },
    wrapEmoji(text){
      const unicodeEmojiRegex = emojiRegex();
      if(text){
        return text.replace(unicodeEmojiRegex, function(match, offset) {
          const before = text.substring(0, offset)
          if (before.endsWith('alt="') || before.endsWith('data-text="')) {
            return match
          }
          let emoji = emojiIndex.nativeEmoji(match)
          if (!emoji) {
            return match
          }
          let style = `background-position: ${emoji.getPosition()}`
          // The src="data:image..." is needed to prevent border around img tags.
          return `<img data-text="${emoji.native}" alt="${
              emoji.colons
          }" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class='emoji-text emoji-set-twitter emoji-type-image' style="${style}">`
        })
        }
      return text;
    },
    emojiToHtml(emoji){
      let style = `background-position: ${emoji.getPosition()}`
      // The src="data:image..." is needed to prevent border around img tags.
      return `<img data-text="${emoji.native}" alt="${
          emoji.colons
      }" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" class='emoji-text emoji-set-twitter emoji-type-image' style="${style}">`
    },
    addEmoji(){

    },
    showEmoji(emoji) {
      this.textMessage = this.textMessage + emoji.native;
    },
    clicked1(e){
      if (e.shiftKey){
        this.textMessage = this.textMessage+= '\n';
      }
      else{
        this.sendMessage();
      }
    },
    fixImgPath(url){
      if(url){
        let img = url.replace('https://www.youtube.com/channel/',this.site+'/images/');
        return img+'.jpg';
      }
    },
    showDeleteMessages(){
      this.showDeleteMessage = !this.showDeleteMessage;
    },
    deleteMessage(m){
      this.$axios.$get('/api/message-delete/'+m.id).then(() => {
        this.getMessages();
      });
    },
    banUser(id){
      this.$axios.$get('/api/user-chat-ban/'+id).then(() => {
        this.getMessages();
      });
    },
    connectChat(){
      this.showChat = true;
      this.$axios.$get('/api/get-api-key').then(res=>{
        this.$echo.connector.options.auth.headers['Authorization'] = 'Bearer ' + res
        this.$echo.options.auth = {
          headers: {
            Authorization: 'Bearer ' + res,
          },
        }
      }).then(()=>{
        this.listenChannel.listen('PrivateChat',({message}) => {
          this.$store.dispatch('ytuber/sendMessage',message);
          console.log('chat-message');
          this.typingTimer = false;
        }).here((users)=>{
          this.activeUsers = users;
        }).joining(user=>{
          let checkDublicated = true;
          for (let key in this.activeUsers) {
          if(this.activeUsers[key].user_id == user.user_id){
            checkDublicated = false
          }
          }
          if(checkDublicated){
            this.activeUsers.unshift(user);
          }
        }).leaving(user=>{
          this.activeUsers.splice(this.activeUsers.indexOf(user),1);
        }).listenForWhisper('typing', (e)=>{
          this.isActive = e;
          if(this.typingTimer) clearTimeout(this.typingTimer);
          this.typingTimer = setTimeout(()=>{
            this.isActive = false;
          },5000);

        });
      });
    },
    chatRoomEnter(){
      this.$axios.$get('/api/join-chat-main').then(res=>{
        if(res == "access denied"){
          this.$toast.info('Чтобы вступить в чат добавьте канал <a href="/profile">&nbsp;В профиле</a>', {
            'duration': 3000
          });
        }
        this.$nuxt.refresh()
      })
    },
    insert(emoji) {
      this.textMessage += emoji
    },
    resizeChat(){
      this.$store.dispatch('ytuber/setChatSize');
    },
    getMessages(){
      this.$store.dispatch('ytuber/getMessages');
      setTimeout(()=>{
        if(this.listenChannel.pusher.channels.channels['presence-room.1'].subscribed){
          this.chatEnabled = true;
        }
        else{
          this.chatEnabled = false;
        }
      },2000);

    },
    userAction(){
      this.listenChannel.whisper('typing',{
        name:this.user.active_account.title
      })
    },
    sendMessage(){
      if(this.textMessage.length>0){
      let data = {
        'text':this.textMessage,
        'room_id':1,
        'user':this.user.active_account,
        'youtube_video':this.youtubeVideo,
        'youtube_channel':this.youtubeChannel
      }
      this.$axios.$post('api/message-chat',data).then(res=>{
        this.textMessage = '';
      }).catch(error => {
        if (error.response && error.response.status == 422) {
          for (let key in error.response.data.errors) {
            this.$toast.error(error.response.data.errors[key], {
              'duration': 3000
            });
          }
        }
        if (error.response && error.response.status == 500) {
          if(error.response.data.message="The supplied URL does not look like a Youtube URL"){
            this.$toast.error("Вы указали не правильную ссылку на ролик", {
              'duration': 3000
            });
          }
          if(error.response.data.message="Undefined property: stdClass::$items"){
            this.$toast.error("Не верная ссылка на канал", {
              'duration': 3000
            });
          }

        }
      })
      }
    },
    chatClass(i){
      if(i % 2 == 0){
        return 'media-chat-item-reverse';
      }
      else{
        return "";
      }
    },
    parseVideoId(url) {
      let vid = '';
      let pos = url.toString().indexOf('?v=');
      let poz = url.toString().indexOf('.be/');
      let pox = url.toString().indexOf('shorts/');

      if (url.toString().length == 11)
        vid = url;
      else {
        if (pos !== -1)
          vid = url.toString().substring(pos + 3, pos + 14)
        else if (poz !== -1)
          vid = url.toString().substring(poz + 4, poz + 15)
      }
      if(url.toString().includes('shorts/')){
        vid = url.toString().substring(pox + 7, url.length)
      }
      if (vid.length == 11)
        return vid;
      else
        return null;
    }
  },
  watch:{
    youtubeVideo: {
      immediate: true,
      handler() {
        if(this.youtubeVideo && this.youtubeVideo.length>20) {
          this.$axios.$get('/api/get-video-info/' + this.parseVideoId(this.youtubeVideo)).then(res => {
            this.youtubeVideoData = res;
            this.showYoutubeVideoImg();
          })
        }
      }
    },
    youtubeChannel: {
      immediate: true,
      handler() {
        let data ={
          url:this.youtubeChannel
        }
        if(this.youtubeChannel && this.youtubeChannel.length>20){
          this.$axios.$post('/api/get-сhannel-url/',data).then(res=>{
            this.youtubeChannelData = res;
            this.showYoutubeChannelImg();
          })
        }

      }
    },
    accounts: {
      immediate: false,
      handler() {
        this.showChat = false;
        this.connectChat();

      }
    },
    textMessage: function() {
      const replacements = {
        ":-)": "🙂",
        ":-(": "😞",
        ";-)": "😉",
        ":-|": "😐",
        ":'-(": "😢",
        ":-*": "😘",
        "*.*": "😍",
        ";-P": "😜",
        "8-)": "😎",
        ":-D": "😄",
        "=-D": "😃"
      }
      Object.keys(replacements).forEach(k => {
        this.textMessage = this.textMessage.replace(k, replacements[k])
      })
    }
  },
  computed:{
    user(){
      return this.$store.state.ytuber.user;
    },
    listenChannel(){
      return this.$echo.join('room.1');
    },
    chatMessages(){
      //return this.messages.join('\n');
      return this.$store.state.ytuber.messages
    },
    accounts: function () {
      return this.$store.state.ytuber.accounts
    },
  },
}
</script>
<style>
.smileBtn:hover{
  background:none;
}
.emoji-text{
  height: 24px;
  width: 24px;
  margin: 1px;
}
</style>