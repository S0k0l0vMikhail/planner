<template>
  <div>
    <div>
      <b-navbar class="is-light">
        <template slot="brand">
          <b-navbar-item tag="router-link" :to="{ name: 'home' }">
            <img src="@/assets/logo2.png" alt="">
          </b-navbar-item>
        </template>

        <template slot="start">
          <b-navbar-item tag="router-link" :to="{ name: 'about' }">О проекте</b-navbar-item>
          <a href="https://demo.vplanner.ru" class="navbar-item" target="_blank">Демо версия</a>
        </template>

        <template slot="end">
          <b-navbar-item tag="router-link" :to="{ name: 'login' }">Войти</b-navbar-item>
          <b-navbar-item tag="div">
            <router-link :to="{ name: 'register' }" class="button is-info">Регистрация</router-link>
          </b-navbar-item>
        </template>
      </b-navbar>
    </div>

    <router-view></router-view>
  </div>

</template>

<script>
  export default {
    name: "HomeNavbar"
  }
</script>

<style scoped>

</style>
