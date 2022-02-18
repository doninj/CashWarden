import { defineStore } from 'pinia'

type User = {
  name: string
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
    login() {
      this.user = { name: 'John Doe' }
    },
  }
})
