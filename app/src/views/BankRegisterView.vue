<script setup lang="ts">
import CenteredLayout from "@/components/layouts/CenteredLayout.vue"
import BankRegisterForm from "@/components/auth/bank/BankRegisterForm.vue";
import {useRouter, useRoute} from "vue-router";
import BankRegisterConfirmation from "@/components/auth/bank/BankRegisterConfirmation.vue";

const route = useRoute()
console.log(route.query.ref)
</script>

<template>
  <CenteredLayout>
    <BankRegisterForm v-if="!route.query.ref" />
    <BankRegisterConfirmation v-else />
  </CenteredLayout>
</template>
