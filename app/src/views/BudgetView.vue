<script setup lang="ts">
import { onMounted, ref } from "vue"
import BudgetPrevi from "@/components/BudgetPreviComponent.vue"
import ShowBudget from "@/components/showBudget.vue"
import axios from "@/utils/axios"
import PageSpinner from "@/components/PageSpinner.vue"
import CreateBudgetForm from "@/components/budget/CreateBudgetForm.vue"
import moment from "moment/min/moment-with-locales"
import { useAuth } from "@/stores/auth"


interface LimitedBudget {
  id: number
  name: string
  amount: number
  previsionDate: string
}

interface BudgetComparison {
  month: string,
  prevision: number,
  real: number,
}

const { user } = useAuth()

const limitedBudgets = ref<LimitedBudget[]>([])
const budgetComparision = ref<BudgetComparison[]>([])
const hasBudget = ref(false)
const areBudgetsLoading = ref(true)
const isBudgetFormVisible = ref(false)

onMounted(async () => {
  await fetchBudgets()
})

const detailsBudgetPrev = ref([])

const basicData = ref({
  labels: [],
  datasets: [
    {
      label: "Compte courant du mois",
      backgroundColor: "#42A5F5",
      data: []
    },
    {
      label: "Budget prévisionnel du mois",
      backgroundColor: "#e19321",
      data: []
    }
  ]
})

async function fetchBudgets() {
  areBudgetsLoading.value = true
  const limitedBudgetsData = (await axios.get("/limitedBudgets")).data

  if(limitedBudgetsData.length === 0) {
    return
  }

  limitedBudgets.value = [ ...limitedBudgetsData.limitedBudgets.map(limitedBudget => ({
    id: limitedBudget.id,
    name: moment(limitedBudget.previsionDate).format("MMMM"),
    amount: limitedBudget.amount,
    previsionDate: limitedBudget.previsionDate,
  }))].reverse()

  const budgetsComparision = (await axios.get("/limitedBudgets/comparison")).data
  moment.locale("fr")
  budgetComparision.value = Object.entries(budgetsComparision).map(([ month, budget ]) => ({
    month: moment(`01-${month}`, "DD-MM-YY").format("MMMM"),
    prevision: budget.prevision,
    real: budget.real,
  }))

  basicData.value.labels = [ ...budgetComparision.value.map(budget => budget.month) ]
  basicData.value.datasets[0].data = [ ...budgetComparision.value.map(budget => budget.real) ]
  basicData.value.datasets[1].data = [ ...budgetComparision.value.map(budget => budget.prevision) ]

  detailsBudgetPrev.value = [
      ...budgetComparision.value
          .map(budget => ({ name: budget.month, amount: `${budget.prevision} €` })),
  ].reverse()

  hasBudget.value = limitedBudgets.value.length > 0
  areBudgetsLoading.value = false
  isBudgetFormVisible.value = false
}

function onAddBudgetClicked() {
  isBudgetFormVisible.value = true
}
</script>

<template>
  <div v-if="areBudgetsLoading" class="budget__page-spinner">
    <PageSpinner/>
  </div>
  <div v-else-if="!hasBudget || isBudgetFormVisible" class="budget__empty">
    <CreateBudgetForm
        :limitedBudgets="limitedBudgets"
        @budgetCreatedOrEdited="fetchBudgets"
        @back="isBudgetFormVisible = false"
    />
  </div>
  <div v-else>
    <div>
      <h2 class="font-bold"> Budget prévisionnel du mois</h2>
    </div>
    <show-budget
        :income="budgetComparision[budgetComparision.length - 1].real"
        :expenses="user.account.totalSpendingOfActualMonth"
    ></show-budget>
    <BudgetPrevi
        :limitedBudgets="limitedBudgets"
        :basicsData="basicData"
        :basicsOptions="basicOptions"
        @addBudgetClicked="onAddBudgetClicked"
    />
  </div>
</template>

<style lang="scss" scoped>

.budget__page-spinner {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.budget__empty {
  width: 100%;
  display: flex;
  height: 50rem;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  flex: 1;
}

.p-dropdown .p-dropdown-label {
  color: black !important;
  background: #F7F8FC !important;

}

.modif_dropdown {
  color: #1a202c !important;
  background: #F7F8FC !important;
  border: none !important;
}

.p-dropdown .p-dropdown-label.p-placeholder {
  color: black !important;
  background: #F7F8FC !important;

}

.p-dropdown .p-dropdown-trigger {
  color: black !important;
  background: #F7F8FC !important;

}

.amount {
  font-size: 18px;
  font-weight: 500;
  color: red;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
