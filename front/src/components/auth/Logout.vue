<template>
  <div></div>
</template>

<script>
  export default {
    name: 'Logout',
    created() {
      this.$store.dispatch('destroyToken')
      .then(() => {
        this.$router.replace({name: 'home'})
      })
    },
    beforeRouteEnter(to, from, next) {
      if (confirm('Вы уверены, что хотите выйти из приложения?')) {
        next();
      } else {
        next(false);
      }
    }
  }
</script>
