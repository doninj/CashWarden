<script setup lang="ts">
import Logo from "@/assets/logo.svg"

defineProps<{
  title: string,
  subtitle: string,
}>()

</script>

<template>
  <div class="FormHeader">
    <img :src="Logo"/>
    <span class="FormHeader__appName">CashWarden</span>

    <h1>{{ title }}</h1>
    <p>{{ subtitle }}</p>
  </div>

</template>

<style scoped lang="scss">
.FormHeader {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: .5rem;

  &__appName {
    font-size: 1.2rem;
    font-weight: bold;
    color: #A4A6B3;
  }

  h1 {
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0;
    padding: 0;
  }

  p {
    color: #A4A6B3;
    font-size: 1.1rem;
    margin-bottom: 2rem;
  }


}
</style>
