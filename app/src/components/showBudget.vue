<script setup lang="ts">
  defineProps({
    income: Number,
    expenses: Number,
  })
</script>

<template>
  <div class=" mt-3 grid justify-content-center">
    <div class="card-info_principale col-3 mr-5">
      <div class="flex flex-column align-items-center text-center">
        <span class="text-name_principale">Compte courant actuel</span>
        <span class="text-price_principale">{{ income }} €</span>
      </div>
    </div>
    <div class="card-info col-3 ml-5">
      <div class="flex flex-column align-items-center text-center">
        <span class="text-name">Dépenses du mois</span>
        <span class="text-price">{{ expenses }} €</span>
      </div>
    </div>
  </div>
</template>

<style>
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

.text-name_principale {
  font-size: 20px;
  color: #3751FF;
}
</style>
