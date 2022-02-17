<script setup>
import Header from "@/components/header/Header.vue"
</script>

<template>
  <Header />
  <main>
    <RouterView />
  </main>
</template>

<style lang="scss">
@import '@/assets/base';

#app {
  display: flex;
  main {
    width: 100%;
    overflow: auto;
    padding: 2rem;
  }
}
</style>
