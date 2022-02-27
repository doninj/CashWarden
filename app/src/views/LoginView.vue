<script setup lang="ts">
import CenteredLayout from "@/components/layouts/CenteredLayout.vue"
import LoginForm from "@/components/forms/LoginForm.vue"
import { Routes } from '@/router'

</script>

<template>
  <CenteredLayout>
    <LoginForm @loggedIn="$router.push(Routes.Home)" />
  </CenteredLayout>
</template>
