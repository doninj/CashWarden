<script setup>
import {useAuth} from "@/stores/auth"
import {useRouter} from "vue-router";
import {Routes} from "@/router";
import { onBeforeMount, ref } from "vue"
import LoaderSpinner from "@/components/LoaderSpinner.vue"
import sleep from "@/utils/sleep";

const auth = useAuth()
onBeforeMount(async() => {
  console.log("revovery not tried")
  await auth.recoverToken()
  await auth.recoverUser()
})


</script>

<template>
  <RouterView />
<!--  <LoaderSpinner v-if="!auth.wasRecoveryTried" />-->
  <RouterView />
  <Toast/>
</template>
