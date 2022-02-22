<script setup lang="ts">
import type { IconName } from "@/faIcons"

defineProps<{
  name: IconName,
  size: String
}>()
</script>

<template>
 <font-awesome-icon :icon="['fas', name]"  :size="size" />
</template>

<style scoped>

</style>
