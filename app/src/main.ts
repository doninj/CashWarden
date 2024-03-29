import {createApp} from 'vue'
import {createPinia} from 'pinia'
import PrimeVue from "primevue/config"
import ToastService from 'primevue/toastservice'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"

import App from './App.vue'

import router from './router'
import primeVueComponents from "./primeVue"
import "./faIcons"

import "@/assets/style/main.scss"

const app = createApp(App)

app
  .use(createPinia())
  .use(router)
  .use(PrimeVue)
  .use(ToastService)

primeVueComponents.forEach(component => {
  /** Primevue components added from primeVue.ts */
  app.component(component.name, component)
})

app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app')
