import { defineStore } from 'pinia'
import axios from "@/utils/axios"

type User = {
  id: number
  firstName: string
  lastName: string
  hasAccountChoices: boolean
  hasBankAuthorization: boolean
}

interface AuthState {
  user?: User
  token?: string | null
  wasRecoveryTried: boolean
}

interface LoginInput {
  email: string
  password: string
}

interface RegisterInput {
  email: string
  password: string
  passwordConfirmation: string
  firstName: string
  lastName: string
}

export const useAuth = defineStore({
  id: 'auth',
  state: () => ({
    user: undefined,
    token: localStorage.getItem('token'),
    wasRecoveryTried: !localStorage.getItem('token')
  } as AuthState),
  getters: {
    isLoggedIn: (state) => !!state.user,
    hasBankLinked: (state) => {
      return !!state.user && state.user.hasBankAuthorization && state.user.hasAccountChoices;
    },
  },
  actions: {
    async login(loginInput: LoginInput) {
      try {
        const loginData = await axios.post("/login", loginInput)
        this.user = loginData.data.user

        this.token = loginData.data.token
        localStorage.setItem('token', loginData.data.token)
      }
      catch (e) {
        return Promise.reject(e)
      }
    },
    async recoverSession() {
      if(this.wasRecoveryTried) return

      const tokenFromStorage = localStorage.getItem('token')
      if(tokenFromStorage) {
        this.token = tokenFromStorage

        try {
          const userData = await axios.get("/user", {
            headers: {
              Authorization: `Bearer ${tokenFromStorage}`
            }
          })
          this.user = userData.data
          this.wasRecoveryTried = true
        }
        catch (e) {
          return Promise.reject(e)
        }
      }
    },
    async recoverToken() {
      this.token = localStorage.getItem('token')

      if (this.token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
      }
    },
    async register(registerInput: RegisterInput): Promise<void> {
      try {
        await axios.post("/register", registerInput)
      }
      catch (e) {
        return Promise.reject(e)
      }
    },
    async logout() {
      try {
        await axios.post("/logout")
        this.user = undefined
        this.token = undefined
        localStorage.removeItem('token')
        window.location.reload()
      }
      catch (e) {
        return Promise.reject(e)
      }
    }
  }
})
