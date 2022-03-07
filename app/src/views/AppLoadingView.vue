<script setup lang="ts">
import LoaderSpinner from "@/components/LoaderSpinner.vue"
</script>

<template>
  <div class="loader-container">
    <LoaderSpinner />
  </div>
</template>

<style>
.loader-container {
  width: 100vw;
  height: 100vh;
  background: #363740;
}
</style>
