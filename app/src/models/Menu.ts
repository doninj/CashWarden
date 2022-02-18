import type { IconName } from "@/faIcons"
import type { Routes } from "@/router"

export interface MenuItem {
  label: string,
  route?: Routes,
  icon?: IconName,
  active?: boolean,
  action?: () => void,
}
