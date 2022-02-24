<template>
  <MenuLayout>
    <div class="flex flex-column">
      <div>
        <h2 class="font-bold"> Budget prévisionnel du mois</h2>
      </div>
      <div class=" grid justify-content-center">
        <Dropdown class="modif_dropdown" v-model="month" :options="months" optionLabel="name"
                  placeholder="Selectionner un mois"/>
      </div>
      <div class=" mt-3 grid justify-content-center">
        <div class="card-info col-3 mr-5">
          <div class="flex flex-column align-items-center text-center">
            <span class="text-name">Compte courant actuel</span>
            <span class="text-price">1000 €</span>
          </div>
        </div>
        <div class="card-info col-3 ml-5">
          <div class="flex flex-column align-items-center text-center">
            <span class="text-name">Dépenses du mois</span>
            <span class="text-price">1000 €</span>
          </div>
        </div>
      </div>
      <div class=" mt-3 grid  justify-content-between">
        <div class="card-content-info_bancaire col-12">
          <div class="grid">
            <div class="col-8" style="border-right: 1px solid">
              <div class="flex flex-column">
                <div class="mr-5 my-3 ml-5 flex lg:justify-content-between">
                  <div>
                    <h2 class="font-bold"> Budget prévionnel sur les derniers mois</h2>
                    <span class="date_mise_a_jour"> Mise à jour le 24 février 2022 </span>
                  </div>
                  <div>
                    <span class="voir_plus"> Voir plus </span>
                  </div>
                </div>
                <div class="flex flex-column justify-content-center align-content-center align-items-center">
                  <div class="mt-5" style="height:400px; width:800px;">
                    <Chart type="bar" :data="basicData" :options="basicOptions"/>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="flex flex-column">
                <div class="  my-3 mr-5 ml-5 flex lg:justify-content-between align-items-center">
                  <div>
                    <h2 class="font-bold"> Budget prévisionnel du mois</h2>
                  </div>
                  <div>
                    <span class="voir_plus"> Ajouter un budget </span>
                  </div>
                </div>
                <div class=" mt-5 flex flex-column justify-content-center align-content-center ">
                  <div v-for="transaction in transactions" :key="transaction.name" class=" mr-5 ml-5 grid">
                    <div class="col-7">
                      <span style="font-size: 18px; font-weight: 500"> {{ transaction.name }} </span>
                    </div>
                    <div class="col-3">
                      <span class="amount"> {{ transaction.amount }} </span>
                    </div>
                    <div class="col-2">
                      <Icon name="ellipsis-v" size="2x"></Icon>
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
  </MenuLayout>
</template>

<script setup>
import {ref} from 'vue';
import Icon from "@/components/Icon.vue";
import MenuLayout from "@/components/layouts/MenuLayout.vue"

const transactions = ref([
  {name: "Carrefour part dieu", amount: '- 300 €'},
  {name: "Carrefour part dieu", amount: '+ 300 €'},
  {name: "Carrefour part dieu", amount: '+ 300 €'},
  {name: "Carrefour part dieu", amount: '+ 300 €'},
  {name: "Carrefour part dieu", amount: '+ 300 €'}
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

const basicOptions = ref(
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
const months = ref([
  {name: 'Janvier', code: 'NY'},
  {name: 'Fevrier', code: 'RM'},
  {name: 'Mars', code: 'LDN'},
  {name: 'Avril', code: 'IST'},
  {name: 'Mai', code: 'PRS'}
]);
</script>
<style>

.p-dropdown .p-dropdown-label {
  color: black !important;
}

.modif_dropdown {
  color: #1a202c !important;
  background: #F7F8FC  !important;
  border: none !important;
}

.p-dropdown .p-dropdown-label.p-placeholder {
  color: black !important;
}

.p-dropdown .p-dropdown-trigger {
  color: black !important;
}
.amount {
  font-size: 18px;
  font-weight: 500;
  color: red;
}
</style>
