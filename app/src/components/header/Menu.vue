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
  <nav class="Menu">
    <template v-for="(item, index) in items">
      <RouterLink
          v-if="item.label !== 'divider' && item.route"
          :key="index"
          :to="item.route"
          class="Menu__item"
      >
        <Icon :name="item.icon"/>
        {{ item.label }}
      </RouterLink>
      <div class="Menu__item" v-else-if="!!item.action" @click="item.action">
        <Icon :name="item.icon"/>
        {{ item.label }}
      </div>
      <Divider v-else-if="item.label === 'divider'" />
    </template>
  </nav>
</template>

<style scoped lang="scss">
.Menu {
  display: flex;
  flex-direction: column;

  &__item {
    display: flex;
    gap: 1rem;
    align-items: center;
    padding: .8rem 1.2rem;
    position: relative;
    transition: color .2s ease-in-out;
    cursor: pointer;

    &:before {
      width: 100%;
      height: 100%;
      position: absolute;
      top: 0;
      left: 0;
      opacity: 0;

      content: '';
      background-color: #9FA2B4;
      transition: opacity .2s ease-in-out;
    }

    &:hover, &.router-link-active {
      color: #DDE2FF;
      border-left: 3px solid #DDE2FF;

      &:before {
        opacity: 8%;
      }
    }
  }

  .p-divider {
    margin: 0.5rem 0;
    padding: 0 0.5rem;

    &:before {
     border-top: 1px solid rgba(#DFE0EB, .08);
    }
  }
}
</style>
