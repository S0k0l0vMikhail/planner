<template>
  <div class="columns">
    <div class="column is-three-fifths is-8">
      <CreateUserForm />

      <b-table
        :data="getUsers"
        :striped="true"
        :hoverable="true"
        :loading="isLoading">

        <template slot-scope="props">
          <b-table-column field="name" label="ФИО">
            {{ props.row.name }}
          </b-table-column>

          <b-table-column field="department_name" label="Отдел">
            <ul id="v-for-object" class="demo">
              <li v-for="departament in props.row.departments" :key="departament.id">
                {{ departament.name }}
              </li>
            </ul>
          </b-table-column>

          <b-table-column field="email" label="Email">
            {{ props.row.email }}
          </b-table-column>

          <b-table-column field="date" label="Добавлен" centered>
                    <span class="tag is-info">
                        {{ new Date(props.row.date).toLocaleDateString() }}
                    </span>
          </b-table-column>
        </template>

        <template slot="empty">
          <section class="section">
            <div class="content has-text-grey has-text-centered">
              <p>
                <b-icon
                  icon="emoticon-sad"
                  size="is-large">
                </b-icon>
              </p>
              <div>
                У вас еще нет ни одного сотрудника.
                <br>
              </div>
            </div>
          </section>
        </template>
      </b-table>
    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';
  import CreateUserForm from "./User/CreateUserForm";

  export default {
    name: "AppUsers",
    data() {
      return {
        isLoading: false
      }
    },
    computed: {
      ...mapGetters([
        'getUsers'
      ])
    },
    created() {
      this.isLoading = true;
      this.$store.dispatch('retrieveUsers')
              .then(() => {
        this.isLoading = false;
      })
    },
    components: {
      CreateUserForm
    }
  }
</script>

<style scoped>

</style>
