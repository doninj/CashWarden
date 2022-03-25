<script setup>
import { useAuth } from "@/stores/auth"
import AppLoadingView from "@/views/AppLoadingView.vue"

const auth = useAuth()
auth.recoverToken()
</script>

<template>
  <transition name="fade">
    <AppLoadingView v-if="!auth.wasRecoveryTried"/>
    <RouterView v-else/>
  </transition>
  <Toast/>
</template>

<style lang="scss">
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to {
  opacity: 0;
}
</style>
