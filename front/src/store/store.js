import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)
axios.defaults.baseURL = 'http://vplanner.test/api'

let authToken = localStorage.getItem('auth_token') || null
if (authToken !== null) {
  axios.defaults.headers.common['Authorization'] = 'Bearer ' + authToken
}

export const store = new Vuex.Store({
  state: {
    // для работы с фронтом без бекенда
    disabled_backend: false,
    // JWT-токен, полученный с сервера
    auth_token: localStorage.getItem('auth_token') || null,
    // Флаг режима администратора
    user_is_admin: localStorage.getItem('user_is_admin') || false,
    // Название организации
    org_name: localStorage.getItem('org_name') || '',
    // Отделы организации
    departments: [
      {'id': 1, 'name': 'Загрузка списка отделов', 'users_count': 777, 'date': new Date()},
    ],
    users: [
      {'id': 1, 'name': 'Загрузка списка сотрудников', 'users_count': 777, 'date': new Date()},
    ],
    table_vacations: [
      {'name': 'Загрузка списка графиков', 'year': new Date('y')},
    ]
  },
  getters: {
    loggedIn(state) {
      return state.disabled_backend ? true : state.auth_token !== null
    },
    isCurrentUserAdmin(state) {
      return state.user_is_admin
    },
    getOrganizationName(state) {
      return state.org_name
    },
    getDepartments(state) {
      return state.departments
    },
    getUsers(state) {
      return state.users
    },
    getTableVacations(state) {
      return state.table_vacations
    }
  },
  mutations: {
    setToken(state, token) {
      state.auth_token = token
    },
    setIsAdmin(state, isCurrentUserAdmin) {
      state.user_is_admin = isCurrentUserAdmin
    },
    setOrgName(state, orgName) {
      state.org_name = orgName
    },
    setDepartments(state, departments) {
      state.departments = departments
    },
    destroyToken(state) {
      state.auth_token = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_is_admin')
    },
    setUsers(state, users) {
      state.users = users
    },
    setTableVacations(state, table_vacations) {
      state.table_vacations = table_vacations
    }
  },
  actions: {
    retrieveToken(context, creds) {
      return new Promise((resolve, reject) => {
        axios.post('/login', {
          username: creds.username,
          password: creds.password
        })
        .then(response => {//console.log(response)
          localStorage.setItem('auth_token', response.data.token)
          context.commit('setToken', response.data.token)

          localStorage.setItem('user_is_admin', response.data.meta.is_admin)
          context.commit('setIsAdmin', response.data.meta.is_admin)

          localStorage.setItem('org_name', response.data.meta.org_name)
          context.commit('setOrgName', response.data.meta.org_name)
          resolve(response)
        })
        .catch(error => {
          console.log(error)
          reject(error)
        })
      })
    },

    destroyToken(context) {
      return new Promise((resolve) => {
        if (context.getters.loggedIn) {
          context.commit('destroyToken')
        }

        resolve()
      })
    },

    registerUser(context, formdata) {
      return new Promise((resolve, reject) => {
        axios.post('/user/register', {
          name: formdata.name,
          organization_name: formdata.organization_name,
          password: formdata.password,
          email: formdata.email
        })
        .then(response => {//console.log(response)
          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
      })
    },

    retrieveDepartments(context) {
      return new Promise((resolve, reject) => {
        axios.get('/departments')
        .then(response => {//console.log(response)
          context.commit('setDepartments', response.data)
          resolve()
        })
        .catch(error => {
          console.log(error)
          reject(error)
        })
      })
    },

    retrieveUsers(context) {
      return new Promise((resolve, reject) => {
        axios.get('/users')
        .then(response => {
          context.commit('setUsers', response.data)
          resolve()
        })
        .catch(error => {
          console.log(error)
          reject(error)
        })
      })
    },

    addUser(context, formdata) {
      return new Promise((resolve, reject) => {
        axios.post('/user', {
          name: formdata.name,
          department_id: formdata.department_id,
          email: formdata.email
        })
        .then(response => {
          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
      })
    },

    addDepartment(context, formdata) {
      return new Promise((resolve, reject) => {
        axios.post('/departments', {
          name: formdata.name
        })
        .then(response => {
          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
      })
    },

    updateOrganization(context, formdata) {
      return new Promise((resolve, reject) => {
        axios.put('/organization', {
          name: formdata.name
        })
        .then(response => {
          localStorage.setItem('org_name', formdata.name)
          context.commit('setOrgName', formdata.name)

          resolve(response)
        })
        .catch(error => {
          reject(error)
        })
      })
    },

    retrieveTableVacations(context) {
      return new Promise((resolve, reject) => {
        axios.get('/tablevacations')
            .then(response => {
              context.commit('setTableVacations', response.data)
              resolve()
            })
            .catch(error => {
              console.log(error)
              reject(error)
            })
      })
    },
  }
})
