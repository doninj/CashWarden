<script setup lang="ts">
import type { IconName } from "@/faIcons"

defineProps<{
  name: IconName
}>()
</script>

<template>
 <font-awesome-icon :icon="['fas', name]" />
</template>

<style scoped>

</style>
