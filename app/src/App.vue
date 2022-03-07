<script setup>
import {useAuth} from "@/stores/auth"
import LoaderSpinner from "@/components/LoaderSpinner.vue"

const auth = useAuth()
auth.recoverToken()
</script>

<template>
  <LoaderSpinner v-if="!auth.wasRecoveryTried" />
  <RouterView v-else />
  <Toast/>
</template>
