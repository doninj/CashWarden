<template>
  <div class="flex flex-column">
    <div class=" mt-3 grid  justify-content-between">
      <div class="card-content-info_bancaire col-12">
        <div class="grid">
          <div class="col-8" style="border-right: 1px solid">
            <div class="flex flex-column">
              <div class="mr-5 my-3 ml-5 flex lg:justify-content-between">
                <div>
                  <h2 class="font-bold"> Transactions prévionnel sur les derniers mois</h2>
                  <span class="date_mise_a_jour"> Mise à jour le 24 février 2022 </span>
                </div>
                <div>
                  <span class="voir_plus"> Voir plus </span>
                </div>
              </div>
              <div class="flex flex-column justify-content-center align-content-center align-items-center">
                <div class="mt-5" style="height:400px; width:800px;">
                  <Chart type="bar" :data="basicsData" :options="basicsOptions"/>
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
</template>

<script setup>
import {ref} from 'vue';
import Icon from "@/components/Icon.vue";

const months = ref([
  {name: 'Janvier', code: 'NY'},
  {name: 'Fevrier', code: 'RM'},
  {name: 'Mars', code: 'LDN'},
  {name: 'Avril', code: 'IST'},
  {name: 'Mai', code: 'PRS'}
]);
const month = ref(null)

defineProps({
  transactions: Array,
  basicsOptions: Object,
  basicsData: Object,

})

</script>
<style scoped>

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
