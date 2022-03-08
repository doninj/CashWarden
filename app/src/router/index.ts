import {
  createRouter,
  createWebHistory,
} from "vue-router"

import HomeView from "@/views/HomeView.vue"
import LoginView from "@/views/LoginView.vue"
import RegisterView from "@/views/RegisterView.vue"

import auth from "@/middlewares/auth"
import guest from "@/middlewares/guest"
import { nextFactory } from "@/middlewares/bootstrap"

export enum Routes {
  Home = "/",
  Login = "/login",
  Register = "/register",
  BankRegister = "/bank-register",
  Budget = "/budget",
  Transactions = "/transactions",
  Settings = "/settings",
}

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: Routes.Login,
      name: 'login',
      component: LoginView,
      meta: {
        middleware: [ guest ],
      }
    },
    {
      path: Routes.Register,
      name: "register",
      component: RegisterView,
      meta: {
        middleware: [ guest ],
      }
    },
    {
      path: Routes.BankRegister,
      name: Routes.BankRegister,
      component: () => import("@/views/BankRegisterView.vue"),
      meta: {
        middleware: [ auth ],
      }
    },
    {
      path: Routes.Home,
      name: 'home',
      component: () => import('../components/layouts/MenuLayout.vue'),
      meta: {
        middleware: [ auth ],
      },
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
        middleware: [ auth ],
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
      name: "budget",
      meta: {
        middleware: [ auth ],
      },
      component: () => import("../components/layouts/MenuLayout.vue"),
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

router.beforeEach((to, from, next) => {
  if (to.meta.middleware) {
    const middleware = Array.isArray(to.meta.middleware)
      ? to.meta.middleware
      : [ to.meta.middleware ]

    const context = {
      from,
      next,
      router,
      to,
    }
    const nextMiddleware = nextFactory(context, middleware, 1)

    return middleware[0]({ ...context, next: nextMiddleware })
  }

  return next()
})

export default router
