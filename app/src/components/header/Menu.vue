<script setup lang="ts">
import type { MenuItem } from "@/models/Menu"
import type { PropType } from "vue"
import Icon from "@/components/Icon.vue"

defineProps({
  items: {
    type: Object as PropType<MenuItem[]>,
    required: true
  }
})
</script>

<template>
  <nav>
    <template v-for="(item, index) in items">
      <RouterLink v-if="item.label !== 'divider'" :to="item.href" :key="index">
        <Icon :name="item.icon"/>
        {{ item.label }}
      </RouterLink>
      <Divider v-else/>
    </template>
  </nav>
</template>

<style scoped lang="scss">
nav {
  display: flex;
  flex-direction: column;
}
</style>
