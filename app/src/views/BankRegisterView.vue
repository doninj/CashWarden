<script setup lang="ts">
import { useRoute } from "vue-router"
import CenteredLayout from "@/components/layouts/CenteredLayout.vue"
import BankRegisterForm from "@/components/auth/bank/BankRegisterForm.vue"
import BankRegisterConfirmation from "@/components/auth/bank/BankRegisterConfirmation.vue"

const route = useRoute()
</script>

<template>
  <CenteredLayout>
    <BankRegisterForm v-if="!route.query.ref" />
    <BankRegisterConfirmation v-else />
  </CenteredLayout>
</template>
