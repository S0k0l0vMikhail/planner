import Home from "./components/Home";
import About from "./components/About";
import Login from "./components/auth/Login";
import Logout from "./components/auth/Logout";
import Register from "./components/auth/Register";
import Personal from "./components/Personal";

import App from "./components/App";
import AppVacations from "./components/app/AppVacations";
import AppVacationsView from "./components/app/Vacations/AppVacationsView";
import AppSmeni from "./components/app/AppSmeni";
import AppUsers from "./components/app/AppUsers";
import AppDepartments from "./components/app/AppDepartments";
import AppSettings from "./components/app/AppSettings";

const routes = [
  {
    path: '/', name: 'home', component: Home, meta: {requiresVisitor: true}
  },
  {
    path: '/about', name: 'about', component: About
  },
  {
    path: '/personal', name: 'personal', component: Personal
  },
  {
    path: '/login', name: 'login', component: Login, meta: {requiresVisitor: true}
  },
  {
    path: '/logout', name: 'logout', component: Logout, meta: {requiresAuth: true}
  },
  {
    path: '/register', name: 'register', component: Register, meta: {requiresVisitor: true}
  },
  {
    path: '/app', name: 'app', component: App, meta: {requiresAuth: true}
  },
  {
    path: '/app/vacations', name: 'app_vacations', component: AppVacations, meta: {requiresAuth: true}
  },
  {
    path: '/app/vacations/:id', name: 'app_vacations_view', component: AppVacationsView, meta: {requiresAuth: true}
  },
  {
    path: '/app/smeni', name: 'app_smeni', component: AppSmeni, meta: {requiresAuth: true}
  },
  {
    path: '/app/users', name: 'app_users', component: AppUsers, meta: {requiresAuth: true}
  },
  {
    path: '/app/departments', name: 'app_departments', component: AppDepartments, meta: {requiresAuth: true}
  },
  {
    path: '/app/settings', name: 'app_settings', component: AppSettings, meta: {requiresAuth: true}
  },
]

export default routes
