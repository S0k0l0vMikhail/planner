<template>
  <div class="settings-form">
    <h1 class="title has-text-grey">Параметры учетной записи</h1>
    <form action="#" @submit.prevent="updateSettings">

      <b-field label="Название организации" type="is-info" message="Это поле может менять только администратор" v-if="!isCurrentUserAdmin">
        <b-input type="text" rounded icon="account-multiple" size="is-medium" maxlength="100" v-model="organization_name" disabled></b-input>
      </b-field>

      <b-field label="Название организации" v-if="isCurrentUserAdmin">
        <b-input type="text" rounded icon="account-multiple" size="is-medium" maxlength="100" v-model="organization_name" required></b-input>
      </b-field>

      <b-button type="is-info" native-type="submit" v-if="isCurrentUserAdmin">Сохранить</b-button>
    </form>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    name: "AppSettings",
    computed: {
      ...mapGetters([
        'isCurrentUserAdmin',
        'getOrganizationName'
      ]),
      organization_name: {
        get () {
          return this.getOrganizationName
        },
        set (value) {
          this.organization_name_updated = value
        }
      }
    },
    data() {
      return {
        organization_name_updated: ''
      }
    },
    methods: {
      updateSettings() {
        let updatedName = this.organization_name_updated.trim()
        if (updatedName.length === 0 || updatedName === this.getOrganizationName) {
          alert('Название организации не изменилось')
        } else {
          this.$store.dispatch('updateOrganization', {
            name: this.organization_name_updated
          })
          .then(() => {
            this.$buefy.notification.open({
              message: 'Название организации успешно обновлено',
              type: 'is-success',
              hasIcon: true,
              position: 'is-top'
            })
          })
        }
      }
    }
  }
</script>

<style scoped>
  .settings-form {
    max-width: 500px;
  }
</style>
