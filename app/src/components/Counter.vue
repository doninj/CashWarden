<script setup lang="ts">
import { useCounterStore } from "@/stores/counter"
import { computed, defineProps } from "vue"

// Props en typescript
defineProps<{
  title: string
}>()

// Props sans typescript

// defineProps({
//   title: String
// })

const countStore = useCounterStore()

const countWording = computed(() => `Le count est de ${countStore.counter}`)

</script>

<template>
  <h2>{{ title }}</h2>
  {{ countWording }}
  <button @click="countStore.increment">Add</button>
  <button @click="countStore.decrement">Remove</button>
</template>

<style scoped>

</style>
