<template>
  <div class="flex flex-column">
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
                  <Button class="p-button-text" @click="emit('addBudgetClicked')">Ajouter un budget</Button>
                </div>
              </div>
              <div class=" mt-5 flex flex-column justify-content-center align-content-center ">
                <div v-for="transaction in limitedBudgets" :key="transaction.name" class=" mr-5 ml-5 grid">
                  <div class="col-3">
                    <span style="font-size: 18px; font-weight: 500"> {{ transaction.name }} </span>
                  </div>
                  <div class="col-7">
                    <span class="amount"> {{ transaction.amount }} € </span>
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
defineProps({
  limitedBudgets: Array,
  basicsOptions: Object,
  basicsData: Object,
})

const emit = defineEmits(['addBudgetClicked'])


</script>
<style>

.amount {
  font-size: 18px;
  font-weight: 500;
  color: #e19321;
}
</style>
