<template>
  <div>
    <div style="margin-bottom: 10px;">
      <h1 class="title has-text-grey">Организация &laquo;{{ getOrganizationName }}&raquo;</h1>
    </div>

    <div class="tile is-ancestor">
      <div class="tile is-vertical is-10">
        <div class="tile">
          <div class="tile is-parent">
            <article class="tile is-child notification is-light">
              <p class="subtitle">Ближайшие смены</p>

              <section>
                <b-table
                  :data="data"
                  striped
                  narrowed
                  hoverable>

                  <template slot-scope="props">
                    <b-table-column field="first_name" label="Отдел">
                      {{ props.row.first_name }}
                    </b-table-column>

                    <b-table-column field="last_name" label="Период">
                      {{ props.row.last_name }}
                    </b-table-column>

                    <b-table-column field="date" label="Date" centered>
                    <span class="tag is-success">
                        {{ new Date(props.row.date).toLocaleDateString() }}
                    </span>
                    </b-table-column>

                    <b-table-column label="Gender">
                    <span>
                        <b-icon pack="fas"
                                :icon="props.row.gender === 'Male' ? 'mars' : 'venus'">
                        </b-icon>
                        {{ props.row.gender }}
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
                        <p>Nothing here.</p>
                      </div>
                    </section>
                  </template>
                </b-table>
              </section>

            </article>
          </div>
          <div class="tile is-parent">
            <article class="tile is-child notification is-light">
              <p class="subtitle">Ближайшие отпуска</p>

              <section>
                <b-table
                  :data="data"
                  striped
                  narrowed
                  hoverable>

                  <template slot-scope="props">
                    <b-table-column field="first_name" label="Сотрудник">
                      {{ props.row.first_name }}
                    </b-table-column>
                    <b-table-column field="first_name" label="Отдел">
                      {{ props.row.first_name }}
                    </b-table-column>

                    <b-table-column field="date" label="Период" centered>
                    <span class="tag is-success">
                        {{ new Date(props.row.date).toLocaleDateString() }}
                    </span>
                      &mdash;
                    <span class="tag is-success">
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
                        <p>Nothing here.</p>
                      </div>
                    </section>
                  </template>
                </b-table>
              </section>

            </article>
          </div>
        </div>
        <div class="tile is-parent">
          <article class="tile is-child">
            <p class="subtitle">Последние события</p>
            <div class="content">
              <ul>
                <li>user_name добавил заявку на отпуск с ... по ...</li>
                <li>user_name будет выйдет на смену в department_name с ... по ... </li>
                <li>user_name находится в отпуске с ... по ...</li>
                <li>user_name вернулся из отпуска</li>
                <li>user_name добавил в организацию second_user_name</li>
                <li>user_name запросил замену для себя на date</li>
                <li>...</li>
              </ul>
            </div>
          </article>
        </div>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child">
          <div class="content">
            <p class="title"><!--Tall tile--></p>
            <p class="subtitle"><!--With even more content--></p>
            <div class="content">
              <!-- Content -->
            </div>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    name: "App",
    computed: {
      ...mapGetters([
        'isCurrentUserAdmin',
        'getOrganizationName'
      ])
    },
    data() {
      const data = [
        {'id': 1, 'first_name': 'Jesse', 'last_name': 'Simmons', 'date': '2016/10/15 13:43:27', 'gender': 'Male'},
        {'id': 2, 'first_name': 'John', 'last_name': 'Jacobs', 'date': '2016/12/15 06:00:53', 'gender': 'Male'},
        {'id': 3, 'first_name': 'Tina', 'last_name': 'Gilbert', 'date': '2016/04/26 06:26:28', 'gender': 'Female'},
        {'id': 4, 'first_name': 'Clarence', 'last_name': 'Flores', 'date': '2016/04/10 10:28:46', 'gender': 'Male'},
        {'id': 5, 'first_name': 'Anne', 'last_name': 'Lee', 'date': '2016/12/06 14:38:38', 'gender': 'Female'}
      ]

      return {
        data,
        organization: 'Рога и копыта'
      }
    }
  }
</script>
