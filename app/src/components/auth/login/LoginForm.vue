<script setup lang="ts">
import { ref } from "vue"
import { useAuth } from "@/stores/auth"
import FormHeader from "@/components/auth/FormHeader.vue"
import { Routes } from "@/router"
import {useToast} from "primevue/usetoast";
import {useRouter} from "vue-router";

const toast = useToast()
const auth = useAuth()
const router = useRouter()

const isLoggingIn = ref(false)
const loginForm = ref({
  email: "",
  password: "",
})

async function onSubmit() {
  const form = loginForm.value
  isLoggingIn.value = true

  try {
    await auth.login(form)
    await router.push(Routes.Home)
  }
  catch (error) {
    toast.add({ severity:'error', summary: 'Création du compte', detail: "", life: 3000 });
  }
  finally {
    isLoggingIn.value = false
  }
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
    <Button
        class="submit"
        type="submit"
        label="Se connecter"
        :loading="isLoggingIn"
    />
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
