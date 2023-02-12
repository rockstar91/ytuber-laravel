import Vue from "vue"
import VueYouTubeEmbed from 'vue-youtube-embed';
Vue.use(VueYouTubeEmbed);
Vue.use(VueYouTubeEmbed, { global: true });

// Make sure to pick a unique name for the flag
// so it won't conflict with any other mixin.
if (!Vue.__my_mixin__) {
  Vue.__my_mixin__ = true
  Vue.mixin({

    methods: {
      parseVideoId(url) {
        let vid = '';
        let pos = url.indexOf('?v=');
        let poz = url.indexOf('.be/');

        if (url.length == 11)
          vid = url;
        else {
          if (pos !== -1)
            vid = url.substring(pos + 3, pos + 14)
          else if (poz !== -1)
            vid = url.substring(poz + 4, poz + 15)
        }

        if (vid.length == 11)
          return vid;
        else
          return null;
      },

      parseChannelName(url) {
        let channelName = null;

        let pos = url.indexOf('/c/');

        if (pos !== -1) {
          channelName = url.substring(pos + 3, url.length);
        }

        return channelName;
      },

      parseChannelId(url) {
        // https://www.youtube.com/channel/UCW9Uij5uiksVkMDg0c2AFuQ
        let channelId = null;

        let pos = url.indexOf('/channel/');

        if (pos !== -1) {
          channelId = url.substring(pos + 9, pos + 33);
        }

        return channelId;
      }
    }


  }) // Set up your mixin then
}


