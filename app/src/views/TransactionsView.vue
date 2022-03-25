<script setup lang="ts">
import TransactionsTable from "@/components/transactions/TransactionsTable.vue"

</script>

<template>
  <div class="transactions-page">
    <h2 class="font-bold">Mes transactions</h2>
    <div class="transactions-table">
      <TransactionsTable />
    </div>
  </div>
</template>

<style lang="scss">
.transactions-page {
  position: relative;
  display: flex;
  width: 100%;
  flex-direction: column;
  align-items: center;

  h2 {
    align-self: flex-start;
  }

 .transactions-table {
   display: flex;
   align-items: center;
   justify-content: center;
   width: 100%;
    flex: 1;
  }
}
</style>
