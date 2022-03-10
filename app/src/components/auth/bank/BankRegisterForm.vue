<script setup lang="ts">
import { onMounted, ref } from "vue"
import FormHeader from "@/components/auth/FormHeader.vue"
import axios from "@/utils/axios"

interface Bank {
  id: string,
  name: string,
  logo: string,
}

type RegisterStep = "info" | "password"
const registerStep = ref<RegisterStep>("info")
const isRegistering = ref(false)
const banks = ref<Bank[]>([])

onMounted(async () => {
  banks.value = (await axios.get("banks")).data
})

const selectedBank = ref<Bank | null>(null)

async function onBankSubmit() {
  if (selectedBank.value) {
    const requisition = await axios.post("/requisition", {
      "bank_id": selectedBank.value.id,
    })

    window.open(requisition.data.requisition_response.link, "_blank", "height=800,width=500")
  }
}
</script>

<template>
  <FormHeader title="Connexion à votre banque" subtitle="Choisissez votre banque parmis la liste"/>

  <form @submit.prevent="onBankSubmit">
    <Transition>
      <div class="info_inputs" v-if="registerStep === 'info'">
        <div class="field">
          <label for="email">Votre banque</label>
          <Dropdown
              v-model="selectedBank"
              :options="banks"
              optionLabel="name"
              :filter="true"
              placeholder="Banque"
          >
            <template #value="slotProps">
              <div class="bank-item" v-if="slotProps.value">
                <img :src="slotProps.value.logo"/>
                <div>{{ slotProps.value.name }}</div>
              </div>
              <span v-else>
                    {{ slotProps.placeholder }}
              </span>
            </template>
            <template #option="slotProps">
              <div class="bank-item">
                <img :src="slotProps.option.logo"/>
                <div>{{ slotProps.option.name }}</div>
              </div>
            </template>
          </Dropdown>
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
        label="Sélectionner votre banque"
        :loading="isRegistering"
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
    label {
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

.p-dropdown {
  width: 100%;
}

.bank-item {
  display: flex;
  font-size: 14px;

  img {
    width: 20px;
    height: 20px;
    margin-right: 0.5rem;
  }
}
</style>
