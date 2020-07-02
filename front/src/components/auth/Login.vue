<template>
  <div class="login-form">
    <h1 class="title has-text-grey">Авторизация</h1>
    <form action="#" @submit.prevent="login">

      <b-field label="Email">
        <b-input type="email" rounded icon="email" size="is-medium" maxlength="50" v-model="username" required></b-input>
      </b-field>

      <b-field label="Пароль">
        <b-input type="password" rounded icon="lock" size="is-medium" v-model="password" password-reveal required></b-input>
      </b-field>

      <br>

      <b-button type="is-info" native-type="submit" size="is-large" expanded>Войти</b-button>
    </form>
  </div>
</template>

<script>
  export default {
    name: 'login',
    data() {
      return {
        username: '',
        password: ''
      }
    },
    methods: {
      login() {
        this.$store.dispatch('retrieveToken', {
          username: this.username,
          password: this.password
        })
        .then(() => {
          this.$router.push({name: 'app'})
        })
      }
    }
  }
</script>

<style>
  .login-form {
    max-width: 500px;
    margin: auto;
  }
</style>
