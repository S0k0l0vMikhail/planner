<!-- https://buefy.org/documentation/sidebar/ -->
<template>
  <div class="sidebar-page">
    <section class="sidebar-layout hero is-fullheight">
      <b-sidebar
        position="static"
        mobile="reduce"
        fullheight
        type="is-light"
        :open.sync="open"
      >
        <div class="p-1">
          <div class="block">
            <router-link :to="{ name: 'app' }">
              <img src="@/assets/logo2.png" />
            </router-link>
          </div>
          <b-menu class="is-custom-mobile">
            <b-menu-list label="Меню">
              <b-menu-item
                      tag="router-link"
                      :to="{ name: 'app_vacations' }"
                      :active="this.$router.currentRoute.name === 'app_vacations'"
                      icon="table"
                      label="Графики отпусков">
              </b-menu-item>

              <b-menu-item
                      tag="router-link"
                      :to="{ name: 'app_smeni' }"
                      :active="this.$router.currentRoute.name === 'app_smeni'"
                      icon="clock-outline"
                      label="Графики смен">
              </b-menu-item>

              <b-menu-item
                      tag="router-link"
                      :to="{ name: 'app_departments' }"
                      :active="this.$router.currentRoute.name === 'app_departments'"
                      icon="account-multiple"
                      label="Отделы">
              </b-menu-item>

              <b-menu-item
                      tag="router-link"
                      :to="{ name: 'app_users' }"
                      :active="this.$router.currentRoute.name === 'app_users'"
                      icon="account"
                      label="Сотрудники">
              </b-menu-item>

              <b-menu-item
                      tag="router-link"
                      :to="{ name: 'app_settings' }"
                      :active="this.$router.currentRoute.name === 'app_settings'"
                      icon="cog-outline"
                      label="Настройки">
              </b-menu-item>
            </b-menu-list>
            <b-menu-list label="Действия">
              <b-menu-item
                      tag="router-link"
                      :to="{ name: 'logout' }"
                      icon="logout"
                      label="Выход">
              </b-menu-item>
            </b-menu-list>
          </b-menu>
        </div>
      </b-sidebar>

      <div style="width: 100%; padding-left: 18px; margin-top: 20px;">
        <router-view></router-view>
      </div>
    </section>

  </div>
</template>

<script>
  export default {
    data() {
      return {};
    },
    computed: {
      open() {
        return this.$route.name !== 'app_vacations_view'
      }
    },
    methods: {}
  };
</script>

<style lang="scss">
  .p-1 {
    padding: 1em;
  }

  .sidebar-page {
    display: flex;
    flex-direction: column;
    width: 100%;
    min-height: 100%;
    // min-height: 100vh;
    .sidebar-layout {
      display: flex;
      flex-direction: row;
      min-height: 100%;
      // min-height: 100vh;
    }
  }

  @media screen and (max-width: 1023px) {
    .b-sidebar {
      .sidebar-content {
        &.is-mini-mobile {
          &:not(.is-mini-expand),
          &.is-mini-expand:not(:hover) {
            .menu-list {
              li {
                a {
                  span:nth-child(2) {
                    display: none;
                  }
                }

                ul {
                  padding-left: 0;

                  li {
                    a {
                      display: inline-block;
                    }
                  }
                }
              }
            }

            .menu-label:not(:last-child) {
              margin-bottom: 0;
            }
          }
        }
      }
    }
  }

  @media screen and (min-width: 1024px) {
    .b-sidebar {
      .sidebar-content {
        &.is-mini {
          &:not(.is-mini-expand),
          &.is-mini-expand:not(:hover) {
            .menu-list {
              li {
                a {
                  span:nth-child(2) {
                    display: none;
                  }
                }

                ul {
                  padding-left: 0;

                  li {
                    a {
                      display: inline-block;
                    }
                  }
                }
              }
            }

            .menu-label:not(:last-child) {
              margin-bottom: 0;
            }
          }
        }
      }
    }
  }
</style>