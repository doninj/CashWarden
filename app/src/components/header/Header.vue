<script setup lang="ts">
import type { MenuItem } from "@/models/Menu"
import Menu from "./Menu.vue"

const items: MenuItem[] = [
  {
    href: "/",
    label: "Vue d'ensemble",
    icon: "pie-chart"
  },
  {
    href: "/",
    label: "Budget prévisionnel",
    icon: "hand-holding-dollar"
  },
  {
    href: "/",
    label: "Mes transactions",
    icon: "money-check"
  },
  {
    label: "divider"
  },
  {
    href: "/",
    label: "Paramètres",
    icon: "cog"
  },
  {
    href: "/",
    label: "Déconnexion",
    icon: "sign-out-alt"
  }
]
</script>

<template>
  <header>
    <h1>Nom d'app</h1>
    <Menu :items="items"/>
  </header>
</template>

<style scoped>
header {
  width: 250px;
  height: 100vh;
  background: var(--surface-card);
  padding: 1rem;
}
</style>
