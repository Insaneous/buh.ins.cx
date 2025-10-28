<template>
  <form @submit.prevent="handleSubmit" class="space-y-4">
    <div class="flex flex-wrap gap-3">
      <!-- Type -->
      <select v-model="form.type" class="border rounded p-2 w-28 h-10">
        <option value="income">Доход</option>
        <option value="expense">Расход</option>
      </select>

      <!-- Category -->
      <div class="flex flex-col min-w-[180px]">
        <select
          v-model="form.category_id"
          class="border rounded p-2 transition-colors duration-200 h-10"
          :class="errors.category_id ? 'border-red-500 bg-red-50' : ''"
        >
          <option disabled value="">Категория</option>
          <option
            v-for="cat in filteredCategories"
            :key="cat.id"
            :value="cat.id"
          >
            {{ cat.name }}
          </option>
        </select>
        <div class="h-5 flex items-center">
          <ValidationMessage :message="errors.category_id" />
        </div>
      </div>

      <!-- Amount -->
      <div class="flex flex-col w-32">
        <input
          v-model="displayAmount"
          placeholder="Сумма"
          type="text"
          inputmode="decimal"
          @input="formatAmountInput"
          @blur="normalizeAmount"
          class="border rounded p-2 text-right transition-colors duration-200 h-10"
          :class="errors.amount ? 'border-red-500 bg-red-50' : ''"
        />
        <div class="h-5 flex items-center">
          <ValidationMessage :message="errors.amount" />
        </div>
      </div>

      <!-- Date -->
      <div class="flex flex-col">
        <input
          v-model="form.date"
          type="date"
          class="border rounded p-2 transition-colors duration-200 h-10"
          :class="errors.date ? 'border-red-500 bg-red-50' : ''"
        />
        <div class="h-5 flex items-center">
          <ValidationMessage :message="errors.date" />
        </div>
      </div>

      <!-- Comment -->
      <input
        v-model="form.comment"
        placeholder="Комментарий"
        class="border rounded p-2 flex-1 h-10"
      />

      <!-- Submit -->
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
    </div>
  </form>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import axios from 'axios'
import ValidationMessage from './ValidationMessage.vue'

const emit = defineEmits(['submit'])
const props = defineProps({
  modelValue: Object,
  editMode: Boolean,
})

/* FORM */
const form = ref({
  type: 'income',
  category_id: '',
  amount: 0,
  date: new Date().toISOString().substring(0, 10),
  comment: '',
})

const displayAmount = ref('')
const errors = ref({})
const categories = ref([])

// Reset form
const resetForm = () => {
  form.value = {
    type: 'income',
    category: '',
    amount: 0,
    date: new Date().toISOString().substring(0, 10),
    comment: '',
  }
  displayAmount.value = ''
  errors.value = {}
}

/* FETCH CATEGORIES */
const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/categories')
    categories.value = res.data
  } catch (e) {
    console.error('Failed to load categories', e)
  }
}

const filteredCategories = computed(() =>
  categories.value.filter((c) => c.type === form.value.type)
)

/* AMOUNT FORMATTING */
watch(displayAmount, (val) => {
  const numeric = val.replace(/\s/g, '').replace(',', '.')
  form.value.amount = parseFloat(numeric) || 0
})

const formatAmountInput = (e) => {
  let raw = e.target.value.replace(/\s/g, '').replace(',', '.')
  if (!/^\d*\.?\d*$/.test(raw)) return
  const [intPart, decPart] = raw.split('.')
  const formatted = intPart.replace(/\B(?=(\d{3})+(?!\d))/g, ' ')
  displayAmount.value = decPart ? `${formatted}.${decPart}` : formatted
}

const normalizeAmount = () => {
  if (!displayAmount.value) return
  displayAmount.value = form.value.amount.toLocaleString('ru-RU', {
    minimumFractionDigits: 2,
  })
}

/* VALIDATION + SUBMIT */
const handleSubmit = () => {
  errors.value = {}
  if (!form.value.category_id) errors.value.category_id = 'Выберите категорию'
  if (!form.value.amount || form.value.amount <= 0)
    errors.value.amount = 'Введите сумму'
  if (!form.value.date) errors.value.date = 'Укажите дату'

  if (Object.keys(errors.value).length) return
  emit('submit', { ...form.value })
  resetForm()
}

/* WATCH EDIT MODE */
watch(
  () => props.modelValue,
  (val) => {
    if (val) form.value = { ...val }
  },
  { immediate: true }
)

/* INIT */
onMounted(fetchCategories)
</script>
