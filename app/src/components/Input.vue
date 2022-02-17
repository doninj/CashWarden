<script setup lang="ts">
import { ref, watch } from "vue"

  /** Les variables réactive (qui doient rerender le dom) sont créer avec ref() */
  const text = ref("Hello world")
  const textChangeCount = ref(0)

  watch(text, () => textChangeCount.value++)
</script>

<template>
  {{ textRendered }}
  <p>Le texte : {{ text }} a changé {{ textChangeCount }} fois</p>
  <input v-model="text" />
</template>

<style scoped>

</style>
