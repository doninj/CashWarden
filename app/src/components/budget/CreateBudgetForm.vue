<script setup>
import { computed, ref } from "vue"
import axios from "@/utils/axios"
import moment from "moment"

const emit = defineEmits(['budgetCreatedOrEdited', 'back'])

const { limitedBudgets } = defineProps({
  limitedBudgets: Array,
})

const budgetForm = ref({
  previsionDate: new Date(),
  amount: 100,
})

const existingBudgetBeingEdited = computed(() => limitedBudgets?.find(limitedBudget =>
        (new Date(limitedBudget.previsionDate)).getMonth() === budgetForm.value.previsionDate.getMonth()
    )
)

const isBudgetBeingCreated = ref(false)

async function onSubmit() {
  isBudgetBeingCreated.value = true
  const date = moment(budgetForm.value.previsionDate).format("YYYY-MM")

  try {
    if(existingBudgetBeingEdited.value) {
      await axios.put(`/limitedBudgets/${existingBudgetBeingEdited.value.id}`, {
        amount: budgetForm.value.amount,
      })
    } else {
      await axios.post("/limitedBudgets", {
        amount: budgetForm.value.amount,
        previsionDate: date,
      })
    }
    emit('budgetCreatedOrEdited')
  }
  catch (error) {
    console.error(error)
  }
  finally {
    isBudgetBeingCreated.value = false
  }
}
</script>

<template>
  <template v-if="limitedBudgets?.length > 0">
    <h2>Créer ou modifier un budget</h2>
    <h3 v-if="!!existingBudgetBeingEdited" class="text-orange-500">
      Un budget existe déjà pour ce mois, seul le montant sera modifier !
    </h3>
  </template>
 <template v-else>
   <h2>Aucun budget disponible</h2>
   <h3>créer en un maintenant !</h3>
 </template>
  <form @submit.prevent="onSubmit" class="budget__form">
    <div class="budget__form-fields">
      <div class="field field__day">
        <label for="previsionDay">Mois de début du budget</label>
        <Calendar
            id="icon" v-model="budgetForm.previsionDate" :showIcon="true"
            view="month" date-format="mm/yy"
        />
      </div>
      <div class="field field__amount">
        <label for="amount">Montant du budget</label>
        <InputNumber
            showButtons
            buttonLayout="horizontal"
            id="amount"
            :min="1"
            :max="1000000"
            mode="currency"
            locale="fr-FR"
            currency="EUR"
            v-model="budgetForm.amount"
        />
      </div>
    </div>
    <Button
        type="submit"
        :label="existingBudgetBeingEdited ? 'Modifier le budget' : 'Créer un budget'"
        :loading="isBudgetBeingCreated"
    />
    <Button
        v-if="isCreatingBudget"
        class="p-button-text mt-2"
        label="Retour"
        icon="pi pi-arrow-left"
        @click="emit('back')"
        :disabled="isBudgetBeingCreated"
    />
  </form>
</template>

<style scoped lang="scss">
.budget__form {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  margin-top: 2rem;

  &-fields {
    display: flex;
    flex-direction: column;
    gap: 1rem;

    margin-bottom: 1rem;

    .field {
      display: flex;
      flex-direction: column;
      width: 14rem;

      ::v-deep(.p-inputnumber .p-inputnumber-input) {
        width: 8rem;
      }
    }
  }
}
</style>
