<script setup lang="ts">
import type { IconName } from "@/faIcons"

defineProps<{
  name: IconName,
  size?: string,
}>()
</script>

<template>
 <font-awesome-icon :icon="['fas', name]" :size="size" />
</template>

<style scoped>

</style>
