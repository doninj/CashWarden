import type { IconName } from "@/faIcons"

export enum CategoryId {
  Food = 1,
  Shopping = 2,
}

export interface Transaction {
  label: string;
  amount: number;
  transactionDate: Date;
  categoryId: CategoryId
}

export const categoryIcons: Record<CategoryId, IconName> = {
  [CategoryId.Food]: "utensils",
  [CategoryId.Shopping]: "shopping-bag"
}
