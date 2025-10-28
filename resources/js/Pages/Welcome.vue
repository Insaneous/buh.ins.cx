<script setup>
import { ref, computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import SummaryBar from '@/Components/SummaryBar.vue'
import TransactionTable from '@/Components/TransactionTable.vue'

const props = defineProps({
  demoTransactions: Array,
})

const transactions = ref(props.demoTransactions)

const totalIncome = computed(() =>
  transactions.value.filter(t => t.type === 'income').reduce((s, t) => s + Number(t.amount), 0)
)
const totalExpense = computed(() =>
  transactions.value.filter(t => t.type === 'expense').reduce((s, t) => s + Number(t.amount), 0)
)
const netBalance = computed(() => totalIncome.value - totalExpense.value)
</script>

<template>
  <Head title="Добро пожаловать" />

  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-50">
    <div class="max-w-4xl w-full p-8 bg-white rounded-2xl shadow-md space-y-6 border border-gray-200">
      <div class="text-center">
        <h1 class="text-2xl font-semibold text-gray-800 mb-2">Финансовый трекер</h1>
        <p class="text-gray-500 mb-4">Демо-режим: пример учета доходов и расходов</p>
        <Link
          :href="route('login')"
          class="inline-block bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition"
        >
          Войти / Зарегистрироваться
        </Link>
      </div>

      <SummaryBar :totalIncome="totalIncome" :totalExpense="totalExpense" :netBalance="netBalance" />

      <TransactionTable :transactions="transactions" />
    </div>
  </div>
</template>
