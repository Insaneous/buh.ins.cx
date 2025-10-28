<script setup>
import { ref, computed, onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from 'axios'

import SummaryBar from '../Components/SummaryBar.vue'
import TransactionForm from '../Components/TransactionForm.vue'
import TransactionTable from '../Components/TransactionTable.vue'

/* STATE */
const user = ref(null)
const transactions = ref([])
const editMode = ref(false)
const editingTransaction = ref(null)

/* AUTH */
const fetchUser = async () => {
  try {
    const res = await axios.get('/api/user')
    user.value = res.data
  } catch (e) {
    console.error('Failed to fetch user', e)
  }
}

/* CRUD */
const fetchTransactions = async () => {
  try {
    const res = await axios.get('/api/transactions')
    transactions.value = res.data
  } catch (e) {
    console.error('Failed to fetch transactions', e)
  }
}

const handleFormSubmit = async (form) => {
  try {
    if (editMode.value && editingTransaction.value) {
      await axios.put(`/api/transactions/${editingTransaction.value.id}`, form)
    } else {
      await axios.post('/api/transactions', form)
    }

    await fetchTransactions()
    cancelEdit()
  } catch (e) {
    console.error('Save failed', e)
  }
}

const startEdit = (transaction) => {
  editMode.value = true
  editingTransaction.value = { ...transaction }
}

const cancelEdit = () => {
  editMode.value = false
  editingTransaction.value = null
}

const deleteTransaction = async (id) => {
  if (!confirm('Удалить запись?')) return
  try {
    await axios.delete(`/api/transactions/${id}`)
    await fetchTransactions()
  } catch (e) {
    console.error('Delete failed', e)
  }
}

/* COMPUTED */
const totalIncome = computed(() =>
  transactions.value
    .filter((t) => t.type === 'income')
    .reduce((sum, t) => sum + Number(t.amount), 0)
)
const totalExpense = computed(() =>
  transactions.value
    .filter((t) => t.type === 'expense')
    .reduce((sum, t) => sum + Number(t.amount), 0)
)
const netBalance = computed(() => totalIncome.value - totalExpense.value)

/* LIFECYCLE */
onMounted(async () => {
  await fetchUser()
  await fetchTransactions()
})
</script>

<template>
  <Head title="Transactions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Транзакции
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <!-- Card -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6 space-y-6 border border-gray-200">
          <!-- Summary -->
          <SummaryBar
            :totalIncome="totalIncome"
            :totalExpense="totalExpense"
            :netBalance="netBalance"
          />

          <!-- Form -->
          <TransactionForm
            :edit-mode="editMode"
            :model-value="editingTransaction"
            @submit="handleFormSubmit"
            @cancel="cancelEdit"
          />

          <!-- Table -->
          <TransactionTable
            :transactions="transactions"
            @edit="startEdit"
            @delete="deleteTransaction"
          />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
