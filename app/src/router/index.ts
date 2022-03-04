import {createRouter, createWebHistory} from 'vue-router'
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
      path: Routes.Login,
      name: 'login',
      component: LoginView
    },
    {
      path: Routes.Home,
      name: 'home',
      component: () => import('../components/layouts/MenuLayout.vue'),
      children: [
        {
          path: '',
          name: 'homeView',
          // route level code-splitting
          // this generates a separate chunk (About.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import('../views/HomeView.vue')
        }
      ]
    },
    {
      path: Routes.Transactions,
      name: 'transaction',
      component: () => import('../components/layouts/MenuLayout.vue'),
      meta: {
        needAuth: true
      },
      children: [
        {
          path: '',
          name: 'transactionView',
          // route level code-splitting
          // this generates a separate chunk (About.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import('../views/BudgetView.vue')
        }
      ]
    },
    {
      path: Routes.Budget,
      name: 'budget',
      component: () => import('../components/layouts/MenuLayout.vue'),
      meta: {
        needAuth: true
      },
      children: [
        {
          path: '',
          name: 'budgetView',
          // route level code-splitting
          // this generates a separate chunk (About.[hash].js) for this route
          // which is lazy-loaded when the route is visited.
          component: () => import('../views/BudgetView.vue')
        }
      ]
    },
  ]
})

export default router
