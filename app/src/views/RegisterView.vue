<script setup lang="ts">
import { Routes } from '@/router'
import CenteredLayout from "@/components/layouts/CenteredLayout.vue"
import RegisterForm from "@/components/forms/RegisterForm.vue"

</script>

<template>
  <CenteredLayout>
    <RegisterForm @loggedIn="$router.push(Routes.Home)" />
  </CenteredLayout>
</template>
