<script setup lang="ts">
import Logo from "@/assets/logo.svg"
import genAvatarUrl from '@/utils/uiAvatar'

import type { MenuItem } from "@/models/Menu"
import Menu from "./Menu.vue"
import { Routes } from "@/router"
import {useAuth} from "@/stores/auth";

const auth = useAuth()

const items: MenuItem[] = [
  {
    route: Routes.Home,
    label: "Vue d'ensemble",
    icon: "pie-chart",
  },
  {
    route: Routes.Budget,
    label: "Budget prévisionnel",
    icon: "hand-holding-dollar"
  },
  {
    route: Routes.Transactions,
    label: "Mes transactions",
    icon: "money-check"
  },
  {
    label: "divider"
  },
  {
    route: Routes.Settings,
    label: "Paramètres",
    icon: "cog"
  },
  {
    label: "Déconnexion",
    icon: "sign-out-alt",
    action: auth.logout
  }
]
</script>

<template>
  <header>
    <div id="brand">
      <img :src="Logo"/>
      <div>CashWarden</div>
    </div>
    <div id="user">
      <div id="user__avatar">
        <Avatar
            :image="genAvatarUrl(auth.user.firstName, auth.user.lastName)"
            size="large"
            shape="circle"
        />
      </div>
      {{ auth.user.firstName }} {{ auth.user.lastName }}
    </div>
    <Menu :items="items"/>
  </header>
</template>

<style scoped lang="scss">
header {
  background: #363740;
  height: 100%;
  width: 255px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  overflow-x: hidden;
  padding-top: 20px;
  color: #A4A6B3;

  #brand {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .5rem;
    padding: .8rem 1.2rem;
    font-size: 1.2rem;
    color: #A4A6B3;

    img {
      width: 32px;
      height: 32px;
    }
  }

  #user {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: .5rem;
    padding-bottom: 2rem;

    &__avatar {
      display: flex;
      border-radius: 50%;
      border: 2px solid white;

      .p-avatar-circle {
        border: 2px solid black;
        position: relative;

      }
    }
  }
}
</style>
