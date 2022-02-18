import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'

export enum Routes {
  Home = '/',
  Login = '/login',
  Budget = '/budget',
  Transactions = '/transactions',
  Settings = '/settings',
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: Routes.Home,
      name: 'home',
      component: HomeView,
      meta: {
        needAuth: true,
      }
    },
    {
      path: Routes.Login,
      name: 'login',
      component: LoginView
    },
    {
      path: Routes.Budget,
      name: 'budget',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/BudgetView.vue')
    },
    {
      path: Routes.Transactions,
      name: 'transactions',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/BudgetView.vue')
    },
    {
      path: Routes.Settings,
      name: 'settings',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/BudgetView.vue')
    }
  ]
})

export default router
