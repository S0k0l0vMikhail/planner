<!--
  Шаблон для страницы "о проекте"
  http://www.csrhymes.com/bulma-clean-theme/landing/
  https://bulmathemes.com/
-->

<template>
  <div>
    <section class="section">
      <div class="container">
        <div class="columns">

          <div class="column is-8">
            <div class="content">
              <h1 class="title has-text-grey">О проекте</h1>

              <p>
                Онлайн-сервис vplanner.ru создан, чтобы предоставить вам инструмент для управления графиками смен
                и графиками отпусков. Ваши сотрудники смогут участвовать в планировании, а также получать уведомления
                о своих предстоящих рабочих сменах и/или отпусках, а также коллег по отделу.
                <br>
                Проект бесплатный, для использования вам достаточно иметь адрес электронной почты.
              </p>

              <h2 class="subtitle has-text-grey">Как начать пользоваться сервисом?</h2>

              <p>
                Достаточно загеристрироваться, указав ваше имя, email и название огранизации. <br>
                После регистрации нужно будет уделить немного времени, чтобы добавить ваши отделы, а также сотрудников,
                которые в них работают.
                <br>
                Все добавленные в организацию сотрудники смогут самостоятельно заниматься планированием рабочих смен,
                отпусков,
                командировок, больничных.
              </p>

              <h2 class="subtitle has-text-grey">Полный список возможностей</h2>

              <ul>
                <li>Графики рабочих смен с разбивкой по отделам</li>
                <li>Графики отпусков/командировок/больничных/day-off и т.д. (нахождения не на работе в рабочее время)
                </li>
                <li>Статистика по отработанным дням и дням не на работе</li>
                <li>Уведомления о предстоящих событиях</li>
                <li>История по всем изменениям в графиках</li>
                <li>Комментарии в сменах для взаимодействия с коллегами</li>
                <li>Современные технологии, обеспечивающие высокую скорость работы сервиса</li>
              </ul>

            </div>
          </div>


          <div class="column is-4-desktop is-12-tablet">

            <p class="title is-4 has-text-grey">Есть вопросы? Свяжитесь с нами:</p>

            <form action="#" @submit.prevent="askQuestion">
              <section>
                <b-field label="Ваше имя">
                  <b-input icon="account" v-model="name" required></b-input>
                </b-field>

                <b-field label="Email для ответа">
                  <b-input type="email" icon="email" maxlength="50" v-model="email" required></b-input>
                </b-field>

                <b-field label="Ваш вопрос">
                  <b-input maxlength="500" type="textarea" v-model="question" required></b-input>
                </b-field>

                <b-field>
                  <b-checkbox required>
                    Я согласен на <a href="/personal" target="_blank">обработку</a> персональных данных
                  </b-checkbox>
                </b-field>

                <b-button type="is-info" native-type="submit" expanded>Задать вопрос</b-button>
              </section>
            </form>

          </div>

        </div>
      </div>
    </section>
  </div>


</template>

<script>
  export default {
    name: "About",
    data() {
      return {
        name: '',
        email: '',
        question: '',
        password: ''
      }
    },
    methods: {
      askQuestion() {

      }
    }
  }
</script>

<style scoped>

</style>