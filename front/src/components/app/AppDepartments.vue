<template>
  <div class="columns">
    <div class="column is-three-fifths is-8">
      <CreateDepartmentForm />

      <b-table
        :data="getDepartments"
        :striped="true"
        :hoverable="true"
        :loading="isLoading">

        <template slot-scope="props">
          <b-table-column field="name" label="Название">
            {{ props.row.name }}
          </b-table-column>

          <b-table-column field="users_count" label="Кол-во сотрудников" numeric>
            {{ props.row.users_count }}
          </b-table-column>

          <b-table-column field="date" label="Создан" centered>
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
                У вас еще нет ни одного отдела.
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
  import CreateDepartmentForm from "./Department/CreateDepartmentForm";

  export default {
    name: "AppDepartments",
    data() {
      return {
        isLoading: false
      }
    },
    computed: {
      ...mapGetters([
        'getDepartments'
      ])
    },
    created() {
      this.isLoading = true;
      this.$store.dispatch('retrieveDepartments').then(() => {
        this.isLoading = false;
      })
    },
    components: {
      CreateDepartmentForm
    }
  }
</script>

<style scoped>

</style>
