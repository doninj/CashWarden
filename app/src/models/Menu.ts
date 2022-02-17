import type { IconName } from "@/faIcons"

export interface MenuItem {
  label: string,
  href?: string,
  icon?: IconName,
  active?: boolean,
}
