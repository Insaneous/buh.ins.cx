<template>
  <div class="overflow-hidden rounded-xl border border-gray-300 shadow-sm">
    <table class="min-w-full border-collapse text-sm">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-3 text-left">Тип</th>
          <th class="p-3 text-left">Категория</th>
          <th class="p-3 text-left">Дата</th>
          <th class="p-3 text-right">Сумма</th>
          <th class="p-3 text-right">Итого</th>
          <th class="p-3 text-left">Комментарий</th>
          <th class="p-3 text-right">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(t, i) in transactions"
          :key="t.id"
          :class="[
            'transition-colors',
            i % 2 === 0 ? 'bg-white' : 'bg-gray-50',
            t.type === 'income' ? 'hover:bg-green-50/70' : 'hover:bg-red-50/70',
          ]"
        >
          <td class="p-3 font-medium" :class="t.type === 'income' ? 'text-green-600' : 'text-red-600'">
            {{ t.type === 'income' ? 'Доход' : 'Расход' }}
          </td>
          <td class="p-3">
            {{ t.category?.name || '—' }}
          </td>
          <td class="p-3">{{ formatDate(t.date) }}</td>
          <td class="p-3 text-right font-semibold" :class="t.type === 'income' ? 'text-green-600' : 'text-red-600'">
            {{ formatAmount(t.amount) }}
          </td>
          <td class="p-3 text-right text-gray-800">
            {{ formatAmount(runningTotal(i)) }}
          </td>
          <td class="p-3 text-gray-600 max-w-96">
            {{ t.comment || '—' }}
          </td>
          <td class="p-3 text-right space-x-2">
            <button
              @click="$emit('edit', t)"
              class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors"
              title="Редактировать"
            >
              ✏️
            </button>
            <button
              @click="$emit('delete', t.id)"
              class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors"
              title="Удалить"
            >
              🗑️
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
const props = defineProps({
  transactions: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['edit', 'delete'])

const runningTotal = (index) => {
  return props.transactions
    .slice(0, index + 1)
    .reduce(
      (sum, t) => sum + (t.type === 'income' ? Number(t.amount) : -Number(t.amount)),
      0
    )
}

const formatAmount = (n) =>
  (+n).toLocaleString('ru-RU', { minimumFractionDigits: 2 })

const formatDate = (d) => new Date(d).toLocaleDateString('ru-RU')
</script>
