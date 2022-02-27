import { defineStore } from 'pinia'
import sleep from "@/utils/sleep"

type User = {
  email: string
}

interface AuthState {
  user?: User
}

export const useAuth = defineStore({
  id: 'auth',
  state: () => ({
    user: undefined
  } as AuthState),
  getters: {
    isLoggedIn: (state) => !!state.user
  },
  actions: {
    async login(email: string, password: string) {
      await sleep(2000)
      this.user = { email }
    },

    async register(email: string, password: string, confirmPassword: string) {
      await sleep(2000)
      this.user = { email }
    }
  }
})
