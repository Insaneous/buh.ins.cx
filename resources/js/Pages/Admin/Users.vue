<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import Checkbox from '@/Components/Checkbox.vue'

const users = ref([])
const currentUserId = ref(null)

const fetchCurrentUser = async () => {
  try {
    const res = await axios.get('/api/user')
    currentUserId.value = res.data.id
  } catch (e) {
    console.error('Failed to fetch current user', e)
  }
}

const fetchUsers = async () => {
  try {
    const res = await axios.get('/api/admin/users')
    users.value = res.data.users ?? res.data
  } catch (e) {
    console.error('Failed to fetch users', e)
  }
}

const deleteUser = async (id) => {
  if (!confirm('Удалить пользователя?')) return
  try {
    await axios.delete(`/api/admin/users/${id}`)
    await fetchUsers()
  } catch (e) {
    console.error('Failed to delete user', e)
  }
}

const toggleAdmin = async (user) => {
  try {
    await axios.put(`/api/admin/users/${user.id}`, {
      is_admin: user.is_admin,
    })
  } catch (e) {
    console.error('Failed to update admin status', e)
  }
}

const totalUsers = computed(() => users.value.length)
const totalAdmins = computed(() => users.value.filter((u) => u.is_admin).length)

onMounted(async () => {
  await fetchCurrentUser()
  await fetchUsers()
})
</script>

<template>
  <Head title="Пользователи" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">Пользователи</h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
        <!-- Card -->
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg p-6 space-y-6 border border-gray-200">
          <!-- Summary -->
          <div class="flex justify-between items-center bg-gray-50 p-4 border border-gray-200 rounded-xl shadow-sm">
            <div class="flex flex-col">
              <span class="text-sm text-gray-500">Всего пользователей</span>
              <span class="text-lg font-semibold text-blue-700">{{ totalUsers }}</span>
            </div>
            <div class="flex flex-col items-end">
              <span class="text-sm text-gray-500">Администраторов</span>
              <span class="text-lg font-semibold text-green-600">{{ totalAdmins }}</span>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-hidden rounded-xl border border-gray-300 shadow-sm">
            <table class="min-w-full border-collapse text-sm">
              <thead class="bg-gray-100 text-gray-700">
                <tr>
                  <th class="p-3 text-left">ID</th>
                  <th class="p-3 text-left">Имя</th>
                  <th class="p-3 text-left">Email</th>
                  <th class="p-3 text-left">Администратор</th>
                  <th class="p-3 text-right">Действия</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(u, i) in users"
                  :key="u.id"
                  :class="[
                    'transition-colors',
                    i % 2 === 0 ? 'bg-white' : 'bg-gray-50',
                    'hover:bg-blue-50/70',
                  ]"
                >
                  <td class="p-3">{{ u.id }}</td>
                  <td class="p-3 font-medium">{{ u.name }}</td>
                  <td class="p-3 text-gray-600">{{ u.email }}</td>
                  <td class="p-3">
                    <label class="flex items-center gap-2">
                      <Checkbox
                        v-model:checked="u.is_admin"
                        @change="toggleAdmin(u)"
                        :disabled="u.id === currentUserId"
                      />
                      <span
                        class="text-sm"
                        :class="{
                          'text-gray-700': u.id !== currentUserId,
                          'text-gray-400 italic': u.id === currentUserId,
                        }"
                      >
                        {{
                          u.id === currentUserId
                            ? 'Вы (нельзя изменить)'
                            : u.is_admin
                            ? 'Да'
                            : 'Нет'
                        }}
                      </span>
                    </label>
                  </td>
                  <td class="p-3 text-right space-x-2">
                    <button
                      @click="deleteUser(u.id)"
                      :disabled="u.id === currentUserId"
                      class="inline-flex items-center justify-center px-2 py-1 text-xs font-medium rounded-md transition-colors"
                      :class="[
                        u.id === currentUserId
                          ? 'bg-gray-200 text-gray-500 cursor-not-allowed'
                          : 'bg-red-100 text-red-700 hover:bg-red-200',
                      ]"
                    >
                      🗑️
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
