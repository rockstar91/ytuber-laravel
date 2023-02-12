<template>
  <b-card body-bg-variant="white" header-text-variant="dark" header-bg-variant="light-grey" bg-variant="light">
    <template #header>
      <h3><i class="icon-bubble-lines4 icon-2x mr-2"></i>Обращение в тех. поддержку</h3>
    </template>
    <b-card-text>
      <b-form-group>
      <b-form-input
        id="input-1"
        :value="email"
        type="email"
        placeholder="Введите email"
        disabled
        required
      ></b-form-input>
      </b-form-group>
      <b-form-group>
      <b-form-input
        id="input-1"
        v-model="form.title"
        type="email"
        placeholder="Тема сообщения"
        required
      ></b-form-input>
      </b-form-group>
      <b-form-group>
      <b-form-textarea
        id="textarea"
        v-model="form.text"
        placeholder="Сообщение..."
        rows="3"
        max-rows="6"
      ></b-form-textarea>
      </b-form-group>
      <b-button @click="sendMail" variant="outline-primary" class="mt-2">Отправить</b-button>
    </b-card-text>
  </b-card>
</template>
<script>
  export default {
    data() {
      return {
        form:{
          mail:'',
          title:'',
          text:'',
        }
      }
    },
    mounted() {
    },
    methods: {
      sendMail(){
        let data ={
          email:this.email,
          title:this.form.title,
          message:this.form.text
        }
        this.$axios.$post('/api/send-mail-support',data).then(res=>{
          this.$toast.success("Ваше сообщение "+this.form.title+" отправлено", {
            'duration': 3000
          });
          this.form.title = '';
          this.form.text = '';
        })
      }
    },
    computed: {
      email(){
        return this.$store.state.ytuber.dashboard.email;
      }
    },
    watch: {
      email: {
        immediate: false,
        handler() {
          this.form.mail = this.email;

        }
      },

    }
  }
</script>
