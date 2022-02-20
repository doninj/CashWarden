<script setup lang="ts">
import {computed, ref, watch} from "vue"

  /** Les variables réactive (qui doient rerender le dom) sont créer avec ref() */
  const text = ref("Hello world")
  const textChangeCount = ref(1)
  const test = computed(() => {return textChangeCount})
  watch(text, () => textChangeCount.value++)
</script>

<template>
  {{ textRendered }}
  <p>Le texte : {{ text }} a changé {{ textChangeCount }} fois</p>
  <input v-model="text" />
</template>

<style scoped>

</style>
