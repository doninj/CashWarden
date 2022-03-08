<template>
  <div class="flex flex-column">
    <div class="mt-5">
      <h2 class="font-bold"> Tableau de bord</h2>
    </div>
    <div class=" mt-3 grid justify-content-between">
      <div class="card-info col-3">
        <div class="flex flex-column align-items-center text-center">
          <span class="text-name">Revenu perçus du mois</span>
          <span class="text-price">{{ user.account.totalIncomeOfActualMonth }} €</span>
        </div>
      </div>
      <div class="card-info_principale col-3">
        <div class="flex flex-column align-items-center text-center">
          <span class="text-name_principale">Compte courant actuel</span>
          <span class="text-price_principale">{{ user.account.latest_balances.amount }} €</span>
        </div>
      </div>
      <div class="card-info col-3">
        <div class="flex flex-column align-items-center text-center">
          <span class="text-name">Dépenses du mois</span>
          <span class="text-price">{{ user.account.totalSpendingOfActualMonth }} €</span>
        </div>
      </div>
    </div>
    <div class=" mt-3 grid justify-content-between">
      <div class="card-content-info_bancaire col-12">
        <div class="grid">
          <div class="col-8" style="border-right: 1px solid">
            <div class="flex flex-column">
              <div class="mr-5 ml-5 flex lg:justify-content-between">
                <div>
                  <h2 class="font-bold"> Statistiques bancaire</h2>
                  <span class="date_mise_a_jour"> Mise à jour le {{ reformDate() }} </span>
                </div>
                <div>
                  <span class="voir_plus"> Voir plus </span>
                </div>
              </div>
              <div class="flex flex-column justify-content-center align-content-center align-items-center">
                <div class="flex">
                  <Dropdown :option-label="opt" class="modif_dropdown  mr-5" v-model="month" :options="months"
                            optionLabel="name" @change="showTotal" option-value="name"
                            placeholder="Selectionner un mois"/>
                  <Dropdown :option-label="opt" class="modif_dropdown" option-value="name" v-model="year"
                            :options="years"
                            optionLabel="name" @change="showTotal"
                            placeholder="Selectionner une année"/>

                </div>
                <div class="flex">
                  <div class="mt-5 m-4"  style="height: 300px; width: 300px;">
                    <Chart :plugins="plugins" ref="primeChart" type="doughnut" :data="chartDataDepense"
                           :options="lightOptionsDepense"/>
                  </div>
                  <div class="mt-5 m-4" style="height: 300px; width: 300px;">
                    <Chart :plugins="plugins" ref="primeChartIncome" type="doughnut" :data="chartDataIncome"
                           :options="lightOptionsIncome"/>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-4">
            <div class="flex flex-column">
              <div class="  mr-5 ml-5 flex lg:justify-content-between align-items-center">
                <div>
                  <h2 class="font-bold"> Transactions <br> récentes</h2>
                </div>
                <div>
                  <span class="voir_plus"> Voir plus </span>
                </div>
              </div>
              <div class=" mt-5 flex flex-column justify-content-center align-content-center ">
                <div v-for="transaction in transactions" :key="transaction.name" class=" mr-5 ml-5 grid">
                  <div class="col-2">
                    <Icon :class="AmountColor(transaction)" name="circle-check" size="2x"></Icon>
                  </div>
                  <div class="col-6">
                    <span style="font-size: 18px; font-weight: 500"> {{ transaction.label }} </span>
                  </div>
                  <div class="col-4">
                        <span :class="AmountColor(transaction)"
                              style="font-size: 18px; font-weight: 500"> {{ transaction.montant }} € </span>
                  </div>
                  <Divider></Divider>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.13.0/css/all.css">

<script setup>
import {onBeforeMount, onMounted, ref} from 'vue';
import Icon from "@/components/Icon.vue";
import ChartDataLabels from 'chartjs-plugin-datalabels'
import ChartjsDoughnutsLabel from 'chartjs-plugin-doughnutlabel-rebourne'
import {useAuth} from "@/stores/auth";
import moment from 'moment/min/moment-with-locales';
import axios from "@/utils/axios"

// get account
const { user } = useAuth()
const plugins = [ChartjsDoughnutsLabel, ChartDataLabels]

const month = ref(null)
const year = ref(null)
let transactions = user.account.get_three_transactions
const primeChart = ref()
const primeChartIncome = ref()

let months = ref()
let years = ref()
const chartDataDepense = ref({
  labels: ['A', 'B', 'C'],
  datasets: [
    {
      icons: ['\uf07a', '\uf469', '\uf2e7', '\uf015', '\uf658',],
      data: [5, 50, 10, 50],
      backgroundColor: ["#54034c", "#fc1669", "#9416fc", "#FCC916", '#162DFC'],
      hoverBackgroundColor: ["#54034c", "#fc1669", "#9416fc", "#FCC916", '#162DFC']
    }
  ]
});
const chartDataIncome = ref({
  labels: ['A', 'B', 'C'],
  datasets: [
    {
      icons: ['\uf07a', '\uf469', '\uf2e7', '\uf015', '\uf658',],
      data: [5, 50, 10, 50],
      backgroundColor: ["#034854", "#fc6716", "#308651", "#16b7fc", '#162DFC'],
      hoverBackgroundColor: ["#034854", "#fc6716", "#308651", "#16b7fc", '#162DFC']
    }
  ]
});
const lightOptionsDepense = ref({
  plugins: {
    tooltip: {
      enabled: true
    },
    doughnutlabel: {
      labels: [{
        text: '',
        color: 'red',
        font: {
          size: 40,
          weight: 'bold'
        }
      }, {
        text: 'Dépenses d\'argent'
      }]
    },
    datalabels: {
      color: '#ffffff',
      font: {
        family: '"Font Awesome 5 Free", "Font Awesome 5 Brands',
        size: 20,
        weight: 900
      },
      formatter: (value, context) => {
        console.log(context.dataset.icons)
        return context.dataset.icons[context.dataIndex];
      }
    },
    legend: {
      display: false
    }
  }
});
const lightOptionsIncome = ref({
  plugins: {
    tooltip: {
      enabled: true
    },
    doughnutlabel: {
      labels: [{
        text: '',
        color: 'green',
        font: {
          size: 40,
          weight: 'bold'
        }
      }, {
        text: 'revenu d\'argent'
      }]
    },
    datalabels: {
      color: '#ffffff',
      font: {
        family: '"Font Awesome 5 Free", "Font Awesome 5 Brands',
        size: 20,
        weight: 900
      },
      formatter: (value, context) => {
        console.log(context.dataset.icons)
        return context.dataset.icons[context.dataIndex];
      }
    },
    legend: {
      display: false
    }
  }
});

// Methods
function AmountColor(transaction) {
  if (transaction.montant < 0) {
    return "icon_list_transaction_minus"
  } else {
    return "icon_list_transaction"
  }
}

async function showTotal() {
  if (year.value && month.value) {
    const response = await axios.get('/transactionsPerMonth', { params: { date: month.value, annee: year.value } })
    lightOptionsDepense.value.plugins.doughnutlabel.labels[0].text = `${response.data.totalDepenses} €`
    lightOptionsIncome.value.plugins.doughnutlabel.labels[0].text = `${response.data.totalIncomes} €`
    const chart = primeChart.value.chart
    const chartIncome = primeChartIncome.value.chart
    chartIncome.update()
    chart.update()
  }
}

function reformDate() {
  moment.locale('fr')
  const date = transactions[0].dateTransaction
  return moment(date).format('LL');
}

function reformMonths() {
  months.value = user.account.months.reduce((accu, curr) => {
    accu.push({ 'name': curr })
    return accu
  }, [])
}

function reformYears() {
  years.value = user.account.years.reduce((accu, curr) => {
    accu.push({ 'name': curr })
    return accu
  }, [])
}

onBeforeMount(async () => {
  const response = await axios.get('/transactionsPerMonth')
  lightOptionsDepense.value.plugins.doughnutlabel.labels[0].text = `${response.data.totalDepenses} €`
  lightOptionsIncome.value.plugins.doughnutlabel.labels[0].text = `${response.data.totalIncomes} €`
  const chart = primeChart.value.chart
  const chartIncome = primeChartIncome.value.chart
  chart.update()
  chartIncome.update()

  reformMonths()
  reformYears()
})
</script>
<style>
.icon_list_transaction {
  color: green;
}

.icon_list_transaction_minus {
  color: red;
}

.p-dropdown .p-dropdown-label {
  color: black !important;
}

.modif_dropdown {
  color: #1a202c !important;
  background: white !important;
  border: none !important;
}

.p-dropdown .p-dropdown-label.p-placeholder {
  color: black !important;
}

.p-dropdown .p-dropdown-trigger {
  color: black !important;
}

.card-content-info_bancaire {
  background-color: white;
  border: 0.1px solid #A4A6B3;
  border-radius: 6px;
}

.card-info {
  background-color: white;
  border: 2px solid #A4A6B3;
  border-radius: 6px;
}

.card-info_principale {
  background-color: white;
  border: 2px solid #3751FF;
  border-radius: 6px;
  box-shadow: 0 0 0 2px #DDE2FF;
}

.text-price {
  font-weight: bold;
  font-size: 30px;
}

.text-price_principale {
  font-weight: bold;
  font-size: 30px;
  color: #3751FF;
}

.voir_plus {
  color: #3751FF;
}

.text-name_principale {
  font-size: 20px;
  color: #3751FF;
}

.text-name {
  font-size: 20px;
  color: #9FA2B4;
}

.date_mise_a_jour {
  font-size: 15px;
  color: #9FA2B4;
}
</style>
