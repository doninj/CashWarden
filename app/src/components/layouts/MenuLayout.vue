<script setup lang="ts">
 import Header from '@/components/header/Header.vue'

</script>

<template>
  <div id="app-container">
    <Header />
    <div id="page-router">
    <router-view></router-view>
    </div>
  </div>
</template>

<style scoped lang="scss">
#app-container {
  display: block;
  overflow: scroll;
  height: 100%;
}
#page-router{
  margin-left: 300px; /* Same as the width of the sidebar */
  padding: 0px 10px;
  width: 80%;
  min-height: 100%;
}

</style>
