<template>
  <div class="flex flex-column">3
    <div>
      <h2 class="font-bold"> Budget prévisionnel du mois</h2>
    </div>
    <show-budget></show-budget>
    <div class=" grid justify-content-center mt-5">
      <Dropdown class="modif_dropdown" v-model="month" :options="months" optionLabel="name"
                placeholder="Selectionner un mois"/>
    </div>
    <BudgetPrevi :transactions="DetailsBudgetPrev" :basicsData="basicData" :basicsOptions="basicOptions"></BudgetPrevi>
    <TransactionDetails class="mt-5" :transactions="transactionsDetails" :basicsData="basicDataTransactions"
                        :basicsOptions="basicOptionsTransactions"></TransactionDetails>
  </div>
</template>

<script setup>
import {ref} from 'vue';
import BudgetPrevi from "@/components/BudgetPreviComponent.vue";
import TransactionDetails from "@/components/TransactionDetails.vue";
import ShowBudget from "@/components/showBudget.vue";

const DetailsBudgetPrev = ref([
  {name: "Janvier", amount: ' 300 €'},
  {name: "Février", amount: ' 300 €'},
  {name: "Mars", amount: ' 300 €'},
  {name: "Avril", amount: ' 300 €'},
  {name: "Mai", amount: '300 €'}
]);

const transactionsDetails = ref([
  {name: "Janvier", amount: ' 300 €'},
  {name: "Février", amount: ' 300 €'},
  {name: "Mars", amount: ' 300 €'},
  {name: "Avril", amount: ' 300 €'},
  {name: "Mai", amount: '300 €'}
]);

const basicData = ref({
  labels: ['Janvier', 'Février', 'Mars'],
  datasets: [
    {
      label: 'Budget courant du mois',
      backgroundColor: '#42A5F5',
      data: [65, 59, 80, 81]
    },
    {
      label: 'Budget prévisionnel du mois',
      backgroundColor: '#FFA726',
      data: [28, 48, 40, 19]
    }
  ]
});
const basicDataTransactions = ref({
  labels: ['Janvier', 'Février', 'Mars'],
  datasets: [
    {
      label: 'Budget courant du mois',
      backgroundColor: '#42A5F5',
      data: [65, 59, 80, 81]
    },
    {
      label: 'Budget prévisionnel du mois',
      backgroundColor: '#FFA726',
      data: [28, 48, 40, 19]
    }
  ]
});

const month = ref(null)

const months = ref([
  {name: 'Janvier', code: 'NY'},
  {name: 'Fevrier', code: 'RM'},
  {name: 'Mars', code: 'LDN'},
  {name: 'Avril', code: 'IST'},
  {name: 'Mai', code: 'PRS'}
]);
const basicOptionsTransactions = ref(
    {
      plugins: {
        legend: {
          display: false,
          labels: {
            color: '#495057'
          }
        }
      },
      scales: {
        x: {
          ticks: {
            color: '#495057'
          },
          grid: {
            color: '#ebedef'
          }
        },
        y: {
          ticks: {
            color: '#495057'
          },
          grid: {
            color: '#ebedef'
          }
        }
      }
    }
);
</script>
<style>

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
</style>
