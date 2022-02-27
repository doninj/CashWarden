<script setup lang="ts">
import { ref } from "vue"
import { useAuth } from "@/stores/auth"
import FormHeader from "@/components/forms/FormHeader.vue"
import { Routes } from "@/router"

const emit = defineEmits<{ (event: "loggedIn"): void }>()

const loginForm = ref({
  email: "",
  password: "",
})

async function onSubmit() {
  const auth = useAuth()
  const { email, password } = loginForm.value

  await auth.login(email, password)

  emit("loggedIn")
}

</script>

<template>
  <FormHeader title="Connexion" subtitle="Se connecter à son compte"/>
  <form @submit.prevent="onSubmit">
    <div class="field">
      <label for="email">Email</label>
      <InputText id="email" v-model="loginForm.email"/>
    </div>
    <div class="field">
      <label for="password">Password</label>
      <Password id="password" :feedback="false" toggle-mask v-model="loginForm.password"/>
    </div>
    <Button class="submit" label="Se connecter"/>
  </form>
  <div class="no-account-cta">
    Pas encore de compte ?
    <RouterLink :to="Routes.Register">Inscrivez-vous</RouterLink>
  </div>
</template>

<style scoped lang="scss">
form {
  display: flex;
  flex-direction: column;
  gap: 1rem;

  .field {
    & > * {
      display: block;
      width: 100% !important;
    }

    :deep(.p-password-input) {
      width: 100%;
    }

    label {
      text-transform: uppercase;
      letter-spacing: .5px;
      font-size: 12px;
      font-family: "Mulish";
      font-weight: bold;
      color: #9FA2B4;
    }
  }

  .submit {
    margin-top: 1rem;
  }
}

.no-account-cta {
  display: flex;
  gap: 1rem;
  margin: 1rem;
}
</style>
