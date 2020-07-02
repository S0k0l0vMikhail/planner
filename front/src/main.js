import Vue from 'vue'
import Buefy from 'buefy'
import Master from "./components/layouts/Master"
import {store} from './store/store'
import VueRouter from 'vue-router'
import routes from "./routes";

Vue.config.productionTip = false
Vue.use(VueRouter)
Vue.use(Buefy)

const router = new VueRouter({
  routes,
  mode: 'history'
})

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    // этот путь требует авторизации, проверяем залогинен ли
    // пользователь, и если нет, перенаправляем на страницу логина
    if (!store.getters.loggedIn) {
      next({name: 'login'})
    } else {
      next()
    }
  } else if (to.matched.some(record => record.meta.requiresVisitor)) {
    if (store.getters.loggedIn) {
      next({name: 'app'})
    } else {
      next()
    }
  } else {
    next() // всегда так или иначе нужно вызвать next()!
  }
})

new Vue({
  render: h => h(Master),
  store: store,
  router: router,
}).$mount('#app')
