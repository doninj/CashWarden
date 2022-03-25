<script setup lang="ts">
import type { Transaction } from "@/models/Transaction"
import Icon from "@/components/Icon.vue"
import { categoryIcons } from "@/models/Transaction"
import { onMounted, ref } from "vue"
import axios from "@/utils/axios"
import moment from "moment"
import PageSpinner from "@/components/PageSpinner.vue"


const transactions = ref<Transaction[]>([])
const totalTransactionsCount = ref(0)
const transactionsPerPage = ref(6)
const isTableInitialized = ref(false)
const areTransactionsLoading = ref(true)

onMounted(async () => {
  await fetchTransactions()
  isTableInitialized.value = true
})

async function fetchTransactions(page = 1) {
  areTransactionsLoading.value = true
  try {
    const transactionsQuery = (await axios.get("/transactions", {
      params: {
        page,
      },
    })).data
    totalTransactionsCount.value = transactionsQuery.total
    transactionsPerPage.value = transactionsQuery.per_page

    transactions.value = transactionsQuery.data.map(transaction => ({
      label: transaction.label,
      amount: transaction.montant,
      transactionDate: transaction.dateTransaction,
      categoryId: transaction.category_id,
    }))
  }
  catch (error) {
    console.error(error)
  }
  finally {
    areTransactionsLoading.value = false
  }
}

function onPageChange(event) {
  fetchTransactions(event.page + 1, event.rows)
}

function amountClass(data: string) {
  return parseInt(data.amount) > 0 ? "gain" : "loss"
}

function dateFromNow(date: string) {
  moment.locale("fr")
  return moment(date).fromNow()
}

</script>

<template>
  <div class="transactions-table-container">
    <DataTable
        v-if="isTableInitialized"
        :totalRecords="totalTransactionsCount"
        :value="transactions"
        :paginator="true"
        :lazy="true"
        :rows="transactionsPerPage"
        :loading="areTransactionsLoading"
        @page="onPageChange($event)"
        dataKey="id" :rowHover="true"
        paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport"
        :rowsPerPageOptions="[
          transactionsPerPage,
          transactionsPerPage * 2,
          transactionsPerPage * 3,
           transactionsPerPage* 4,
      ]"
        currentPageReportTemplate="Affiche de {first} à {last} de {totalRecords} transactions au total"
        responsiveLayout="scroll"
    >
      <template #empty>
        Pas de transactions pour le moment !
      </template>
      <template #loading>
        Transactions en cours de chargement.
      </template>
      <Column field="name" header="Label" style="min-width: 14rem">
        <template #body="{data}">
          <div class="transaction-label">
            <div class="category-icon">
              <Icon :name="categoryIcons[data.categoryId]"/>
            </div>
            <div class="transaction-label-inner">
              {{ data.label }}
              <span>{{ dateFromNow(data.transactionDate) }}</span>
            </div>
          </div>
        </template>
      </Column>
      <Column field="createdAt" header="Date" style="min-width: 14rem">
        <template #body="{data}">
          {{ data.transactionDate }}
        </template>
      </Column>
      <Column header="Montant"
              style="min-width: 14rem"
              class="lol"
      >
        <template #body="{data}">
          <div class="transaction-amount" :class="amountClass(data)">
            {{ data.amount }}€
          </div>
        </template>
      </Column>
    </DataTable>
    <PageSpinner class="transactions-table-loader" v-else/>
  </div>
</template>

<style scoped lang="scss">
::v-deep(.p-datatable-table) {
  .transaction {
    &-label {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;

      .category-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background-color: #f5f5f5;
      }

      &-inner {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        width: 100%;
        font-size: 14px;
        font-weight: 500;
        color: #4a4a4a;
      }

    }

    &-amount {
      &.gain {
        color: #00b300;
      }

      &.loss {
        color: #b30000;
      }
    }
  }
}
.transactions-table-container {
  min-height: 50rem;
}

.transactions-table-loader {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}
</style>
