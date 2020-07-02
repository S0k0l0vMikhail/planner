<!--
  docs: https://github.com/neuronetio/gantt-schedule-timeline-calendar
  default config: https://github.com/neuronetio/gantt-schedule-timeline-calendar/blob/master/src/default-config.ts
  demo: https://neuronet.io/gantt-schedule-timeline-calendar/period.html
-->

<template>
  <div>
    <nav class="breadcrumb" aria-label="breadcrumbs">
      <ul>
        <li><router-link :to="{ name: 'app' }">Vplanner.ru</router-link></li>
        <li><router-link :to="{ name: 'app_vacations' }">Графики отпусков</router-link></li>
        <li class="is-active"><a href="#" aria-current="page">График отпусков 2020</a></li>
      </ul>
    </nav>

    <section>

      <b-field>
        <b-radio-button v-model="viewMode"
                        native-value="week"
                        type="is-info">
          <b-icon icon="check" v-if="viewMode === 'week'"></b-icon>
          <span>Столбцы: недели</span>
        </b-radio-button>

        <b-radio-button v-model="viewMode"
                        native-value="day"
                        type="is-info">
          <b-icon icon="check" v-if="viewMode === 'day'"></b-icon>
          <span>Столбцы: дни</span>
        </b-radio-button>

      </b-field>
    </section>

    <br>

    <GSTC :config="config" @state="onState" />
  </div>
</template>

<script>
  import GSTC from "vue-gantt-schedule-timeline-calendar";
  let subs = [];

  export default {
    name: "AppVacationsView",
    components: {
      GSTC
    },
    watch: {
      viewMode(newVal) {
        this.setViewMode(newVal)
      }
    },
    data() {
      return {
        viewMode: 'week',
        config: {
          height: 300,
          locale: {
            months: 'Январь_Февраль_Март_Апрель_Май_Июнь_Июль_Август_Сентябрь_Октябрь_Ноябрь_Декабрь'.split('_'),
            weekdaysShort: 'Вс_Пн_Вт_Ср_Чт_Пт_Сб'.split('_'),
          },
          actions: {
            //'chart-timeline-items-row-item': [addItemTitle]
          },
          list: {
            toggle: {display: false},
            rows: {
              "1": {
                id: "1",
                label: "Станислав Лозицкий"
              },
              "2": {
                id: "2",
                label: "Соколов Михаил"
              }
            },
            columns: {
              data: {
                label: {
                  id: "label",
                  data: "label",
                  width: 150,
                  header: {
                    content: "Сотрудник"
                  }
                }
              }
            }
          },
          chart: {
            items: {
              "1": {
                id: "1",
                rowId: "1",
                label: "1 июня - 17 июня",
                time: {
                  start: 1577836800000,
                  end: 1580479091000
                },
                style: {
                  background: 'green'
                }
              },
              "2": {
                id: "2",
                rowId: "1",
                label: "1 июня - 17 декабря",
                time: {
                  start: 1588341491000,
                  end: 1589983091000
                },
                style: {
                  background: 'green'
                }
              },
              "3": {
                id: "3",
                rowId: "2",
                label: "1 июня - 17 декабря",
                time: {
                  start: 1588341491000,
                  end: 1589983091000
                },
                style: {
                  background: 'red'
                }
              }
            },
            time: {
              period: 'week',
              from: 1577836800000,
              to: 1609459199000,
              zoom: 100
            }
          }
        }
      };
    },
    methods: {
      onState(state) {
        this.state = state;
        subs.push(
          state.subscribe("config.chart.items.1", item => {
            console.log("item 1 changed", item);
          })
        );
        subs.push(
          state.subscribe("config.list.rows.1", row => {
            console.log("row 1 changed", row);
          })
        );
      },
      setViewMode(mode) {
        if ('week' === mode) {
          this.state.update('config.chart.time.zoom', +24);
        } else {
          this.state.update('config.chart.time.zoom', +22);
        }
      }
    },
    mounted() {
      this.state.update('config.chart.time.zoom', +24);
      /*setTimeout(() => {
        const item1 = this.config.chart.items["1"];
        item1.label = "label changed dynamically";
        item1.time.end += 2 * 24 * 60 * 60 * 1000;
      }, 2000);*/
    },
    beforeDestroy() {
      subs.forEach(unsub => unsub());
    }
  }
</script>

<style scoped>

</style>
