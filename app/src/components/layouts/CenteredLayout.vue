<script setup lang="ts">
</script>

<template>
  <div id="app-container">
    <main>
        <slot/>
    </main>
  </div>
</template>

<style scoped lang="scss">
#app-container {
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #363740;

  main {
    min-width: 25rem;
    min-height: 35rem;
    max-height: 50rem;
    border-radius: .5rem;
    border: 1px solid var(--gray-500);
    padding: 2rem;
    background-color: #fff;
  }
}

</style>
