<script setup lang="ts">
 import Header from '@/components/header/Header.vue'

</script>

<template>
  <div id="app-container">
    <Header/>
    <main>
      <slot/>
    </main>

  </div>
</template>

<style scoped lang="scss">
#app-container {
  display: flex;

  main {
    background: #F7F8FC;
    width: 100%;
    overflow: auto;
    padding: 2rem;
  }
}

</style>
