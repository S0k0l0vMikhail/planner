<template>
  <div class="login-form">
    <h1 class="title has-text-grey">Регистрация</h1>
    <form action="#" @submit.prevent="register" style="margin-bottom: 100px;">

      <b-field label="Название вашей организации">
        <b-input type="text" rounded icon="account-multiple" size="is-medium" maxlength="100" v-model="organization_name"
                 required></b-input>
      </b-field>

      <b-field label="Ваше имя">
        <b-input type="text" rounded icon="account" size="is-medium" maxlength="100" v-model="name" required></b-input>
      </b-field>

      <b-field label="Email" :type="{'is-danger': server_error !== ''}" :message="server_error">
        <b-input type="email" rounded icon="email" size="is-medium" maxlength="50" v-model="email" required @keyup.native="clearServerError()"></b-input>
      </b-field>

      <b-field label="Пароль">
        <b-input type="password" rounded icon="lock" size="is-medium" v-model="password" password-reveal></b-input>
      </b-field>

      <br>

      <b-field>
        <b-checkbox required>
          Я согласен на <a href="/personal" target="_blank">обработку</a> персональных данных
        </b-checkbox>
      </b-field>

      <b-button type="is-info" native-type="submit" size="is-large" expanded>Создать аккаунт</b-button>
    </form>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        organization_name: '',
        name: '',
        email: '',
        password: '',
        server_error: ''
      }
    },
    methods: {
      register() {
        this.$store.dispatch('registerUser', {
          organization_name: this.organization_name,
          name: this.name,
          email: this.email,
          password: this.password
        })
        .then(() => {
          this.$router.push({name: 'login'})
        })
        .catch(error => {
          if ('email already taken' === error.response.data.result) {
            this.server_error = 'Пользователь с таким email уже существует';
          } else {
            this.server_error = error.response.data.result
          }
        })
      },
      clearServerError() {
        this.server_error = ''
      }
    }
  }
</script>
