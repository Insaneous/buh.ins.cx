<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from 'axios'
import CategoryForm from '@/Components/CategoryForm.vue'
import CategoryTable from '@/Components/CategoryTable.vue'

const categories = ref([])
const editMode = ref(false)
const editingCategory = ref(null)

/* CRUD */
const fetchCategories = async () => {
  try {
    const res = await axios.get('/api/admin/categories')
    categories.value = res.data.categories ?? res.data
  } catch (e) {
    console.error('Failed to fetch categories', e)
  }
}

const handleFormSubmit = async (form) => {
  try {
    if (editMode.value && editingCategory.value) {
      await axios.put(`/api/admin/categories/${editingCategory.value.id}`, form)
    } else {
      await axios.post('/api/admin/categories', form)
    }
    await fetchCategories()
    cancelEdit()
  } catch (e) {
    console.error('Save failed', e)
  }
}

const startEdit = (category) => {
  editMode.value = true
  editingCategory.value = { ...category }
}

const cancelEdit = () => {
  editMode.value = false
  editingCategory.value = null
}

const deleteCategory = async (id) => {
  if (!confirm('Удалить категорию?')) return
  try {
    await axios.delete(`/api/admin/categories/${id}`)
    await fetchCategories()
  } catch (e) {
    console.error('Delete failed', e)
  }
}

/* SUMMARY */
const totalCategories = computed(() => categories.value.length)
const totalIncome = computed(() => categories.value.filter((c) => c.type === 'income').length)
const totalExpense = computed(() => categories.value.filter((c) => c.type === 'expense').length)

onMounted(fetchCategories)
</script>

<template>
  <Head title="Категории" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Категории</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6 space-y-6 border border-gray-200">
          <!-- Summary -->
          <div class="flex justify-between items-center bg-gray-50 p-4 border border-gray-200 rounded-xl shadow-sm">
            <div class="flex flex-col">
              <span class="text-sm text-gray-500">Всего категорий</span>
              <span class="text-lg font-semibold text-blue-700">{{ totalCategories }}</span>
            </div>
            <div class="flex flex-col">
              <span class="text-sm text-gray-500">Доходы</span>
              <span class="text-lg font-semibold text-green-600">{{ totalIncome }}</span>
            </div>
            <div class="flex flex-col items-end">
              <span class="text-sm text-gray-500">Расходы</span>
              <span class="text-lg font-semibold text-red-600">{{ totalExpense }}</span>
            </div>
          </div>

          <!-- Form -->
          <CategoryForm
            :edit-mode="editMode"
            :model-value="editingCategory"
            @submit="handleFormSubmit"
            @cancel="cancelEdit"
          />

          <!-- Table -->
          <CategoryTable
            :categories="categories"
            @edit="startEdit"
            @delete="deleteCategory"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
