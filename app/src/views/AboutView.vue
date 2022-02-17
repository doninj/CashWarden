<script setup lang="ts">
import { ref } from "vue"

const selectedCity = ref<string>()
const cities = [
  { name: "London", code: "LND" },
  { name: "Paris", code: "PRS" },
  { name: "Rome", code: "RM" }
]
</script>


<template>
  <div class="about">
    <SelectButton v-model="selectedCity" :options="cities" optionLabel="name"/>
  </div>
</template>

<style>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
}
</style>
