<template>
  <div class="overflow-hidden rounded-xl border border-gray-300 shadow-sm">
    <table class="min-w-full border-collapse text-sm">
      <thead class="bg-gray-100 text-gray-700">
        <tr>
          <th class="p-3 text-left">ID</th>
          <th class="p-3 text-left">Название</th>
          <th class="p-3 text-left">Тип</th>
          <th class="p-3 text-right">Действия</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(c, i) in categories"
          :key="c.id"
          :class="[
            'transition-colors',
            i % 2 === 0 ? 'bg-white' : 'bg-gray-50',
            c.type === 'income' ? 'hover:bg-green-50/70' : 'hover:bg-red-50/70',
          ]"
        >
          <td class="p-3">{{ c.id }}</td>
          <td class="p-3 font-medium">{{ c.name }}</td>
          <td class="p-3" :class="c.type === 'income' ? 'text-green-600' : 'text-red-600'">
            {{ c.type === 'income' ? 'Доход' : 'Расход' }}
          </td>
          <td class="p-3 text-right space-x-2">
            <button
              @click="$emit('edit', c)"
              class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors"
            >
              ✏️
            </button>
            <button
              @click="$emit('delete', c.id)"
              class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 transition-colors"
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
defineProps({
  categories: {
    type: Array,
    default: () => [],
  },
})
defineEmits(['edit', 'delete'])
</script>
