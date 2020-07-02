<template>
  <div>
    <b-navbar>
      <template slot="start">
      Графики отпусков
      <router-link :to="{ name: 'app_vacations_view', params: { id: 1 } }">Тестовый график отпусков</router-link>
      </template>
    </b-navbar>
    <button class="button is-info">
      Добавить график
    </button>
    <b-table
            :data="getTableVacations"
            :columns="columns"
            :loading="isLoading">
    </b-table>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    name: "AppVacations",
    data() {
      return {
        isLoading: false,
        columns: [
          {
            field: 'year',
            label: 'Год',
            width: '50',
            numeric: true
          },
          {
            field: 'name',
            label: 'Название',
          },
          {
            field: 'approved_at',
            label: 'Статус',
          },
          {
            field: 'updated_at',
            label: 'Дата обновления'
          }
        ]
      }
    },
    computed: {
      ...mapGetters([
        'getTableVacations'
      ])
    },
    created() {
      this.isLoading = true;
      this.$store.dispatch('retrieveTableVacations').then(() => {
        this.isLoading = false
      })
    }
  }
</script>

<style scoped>

</style>
