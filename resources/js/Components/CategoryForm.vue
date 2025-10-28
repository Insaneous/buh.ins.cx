<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <div class="flex flex-wrap gap-3">
      <!-- Type -->
      <select v-model="form.type" class="border rounded p-2 w-28 h-10">
        <option value="income">Доход</option>
        <option value="expense">Расход</option>
      </select>

      <!-- Name -->
      <div class="flex flex-col flex-1 min-w-[200px]">
        <input
          v-model="form.name"
          placeholder="Название категории"
          class="border rounded p-2 transition-colors duration-200 h-10"
          :class="errors.name ? 'border-red-500 bg-red-50' : ''"
        />
        <div class="h-5 flex items-center text-sm text-red-500">
          {{ errors.name }}
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex gap-2">
        <button
          type="submit"
          :class="[
            'rounded px-4 py-2 text-white font-medium transition-colors h-10',
            form.type === 'income'
              ? 'bg-green-600 hover:bg-green-700'
              : 'bg-red-600 hover:bg-red-700',
          ]"
        >
          {{ editMode ? 'Сохранить' : 'Добавить' }}
        </button>

        <button
          v-if="editMode"
          type="button"
          @click="handleCancel"
          class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md h-10"
        >
          Отмена
        </button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: Object,
  editMode: Boolean,
})
const emit = defineEmits(['submit', 'cancel'])

const form = ref({
  name: '',
  type: 'income',
})
const errors = ref({})

/* SUBMIT */
const handleSubmit = () => {
  errors.value = {}
  if (!form.value.name.trim()) {
    errors.value.name = 'Введите название категории'
    return
  }

  emit('submit', { ...form.value })
  resetForm()
}

/* CANCEL */
const handleCancel = () => {
  resetForm()
  emit('cancel')
}

/* HELPERS */
const resetForm = () => {
  form.value = { name: '', type: 'income' }
  errors.value = {}
}

/* WATCH EDIT MODE */
watch(
  () => props.modelValue,
  (val) => {
    if (val) {
      form.value = { ...val }
    } else {
      resetForm()
    }
  },
  { immediate: true }
)
</script>
