<script setup lang="ts">
import {ref} from "vue"
import {useAuth} from "@/stores/auth"
import FormHeader from "@/components/auth/FormHeader.vue"
import {Routes} from "@/router"
import {useRouter} from "vue-router"
import {useToast} from "primevue/usetoast"
import errorsToString from "@/utils/errorsToString"

const auth = useAuth()
const router = useRouter()
const toast = useToast()

type RegisterStep = "info" | "password"
const registerStep = ref<RegisterStep>("info")
const isRegistering = ref(false)

const loginForm = ref({
  email: "",
  firstName: "",
  lastName: "",
  password: "",
  passwordConfirmation: "",
})

async function onStepSubmit() {
  const form = loginForm.value

  if (registerStep.value === "info") {
    registerStep.value = "password"
  } else {
    isRegistering.value = true

    try {
      await auth.register(form)
      toast.add({ severity:'succes', summary: 'Inscription réussie !', detail: "Vous pouvez maintenant vous connectez", life: 3000 });
      await router.push(Routes.Login)
    }
    catch ({ response: errorResponse }) {
      /** Vue3 à quelques soucis avec typescript :/ */
      // @ts-expect-error: entries is not found on Object ?
      const errorsMessage = errorsToString(errorResponse.data.errors)

      toast.add({ severity:'error', summary: 'Création du compte', detail: errorsMessage, life: 3000 });
    }
    finally {
      isRegistering.value = false
    }
  }
}

</script>

<template>
  <FormHeader title="Inscription" subtitle="Créer votre compte"/>

  <form @submit.prevent="onStepSubmit">
    <Transition>
      <div class="info_inputs" v-if="registerStep === 'info'">
        <div class="field">
          <label for="email">Email</label>
          <InputText id="email" v-model="loginForm.email"/>
        </div>
        <div class="field">
          <label for="firstName">Prénom</label>
          <InputText id="firstName" v-model="loginForm.firstName"/>
        </div>
        <div class="field">
          <label for="lastName">Nom</label>
          <InputText id="lastName" v-model="loginForm.lastName"/>
        </div>
      </div>
      <div class="password_inputs" v-else>
        <div class="field">
          <label for="password">Password</label>
          <Password id="password" :feedback="false" toggle-mask v-model="loginForm.password"/>
        </div>
        <div class="field">
          <label for="confirm-password">Confirmation du mot de passe</label>
          <Password id="confirm-password" :feedback="false" toggle-mask v-model="loginForm.passwordConfirmation"/>
        </div>
      </div>
    </Transition>
    <Button
        type="submit"
        class="submit-form-button"
        label="Créer votre compte"
        :loading="isRegistering"
    />
    <Button
        v-if="registerStep === 'password'"
        class="p-button-text"
        label="Retour"
        @click="registerStep = 'info'"
    />
  </form>
</template>

<style scoped lang="scss">
.form-container {
}

form {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 1rem;
  min-height: 350px;

  .info_inputs, .password_inputs {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
  }

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

  .submit-form-button {
    margin-top: auto;
  }
}

.have-account-cta {
  display: flex;
  gap: 1rem;
  margin: 1rem;
}

/* we will explain what these classes do next! */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
