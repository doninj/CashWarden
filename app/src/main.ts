import { createApp } from 'vue'
import { createPinia } from 'pinia'
import PrimeVue from "primevue/config"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"

import App from './App.vue'

import router from './router'
import primeVueComponents from "./primeVue"
import "./faIcons"

import "@/assets/style/main.scss"

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.use(PrimeVue)
primeVueComponents.forEach(component => {
  /** Primevue components added from primeVue.ts */
  app.component(component.name, component)
})

app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app')
