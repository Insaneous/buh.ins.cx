<template>
  <div class="flex flex-wrap justify-between items-center gap-4 p-4 rounded-xl border border-gray-200 bg-gray-50 shadow-sm">
    <div class="flex flex-col">
      <span class="text-sm text-gray-500">Доход</span>
      <span class="text-lg font-semibold text-green-600">{{ formatAmount(totalIncome) }}</span>
    </div>
    <div class="flex flex-col">
      <span class="text-sm text-gray-500">Расход</span>
      <span class="text-lg font-semibold text-red-600">{{ formatAmount(totalExpense) }}</span>
    </div>
    <div class="flex flex-col items-end">
      <span class="text-sm text-gray-500">Баланс</span>
      <span class="text-lg font-semibold" :class="netBalance >= 0 ? 'text-green-700' : 'text-red-700'">
        {{ formatAmount(netBalance) }}
      </span>
    </div>
  </div>
</template>

<script setup>
defineProps({
  totalIncome: Number,
  totalExpense: Number,
  netBalance: Number,
})

const formatAmount = (n) =>
  (+n).toLocaleString('ru-RU', { minimumFractionDigits: 2 })
</script>
