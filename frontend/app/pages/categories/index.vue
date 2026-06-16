<template>
  <div class="min-h-screen bg-white p-8 font-sans text-black">
    <div class="flex items-center gap-4 bg-white border border-gray-200 rounded-xl px-8 py-4 mb-6 shadow-sm justify-between">
      <div class="flex gap-4">
        <NuxtLink to="/BlogPostsUi" class="font-semibold text-gray-600 hover:text-black transition-colors">Пости</NuxtLink>
        <NuxtLink to="/categories" class="font-semibold text-black transition-colors underline decoration-2 decoration-primary underline-offset-4">Категорії</NuxtLink>
      </div>
      <UButton to="/categories/create" icon="i-lucide-plus" size="md">Додати категорію</UButton>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col p-6">
      <h1 class="text-2xl font-bold mb-6">Категорії блогу</h1>

      <UTable :columns="columns" :data="categories">
        <template #actions-cell="{ row }">
          <UDropdownMenu :items="getActions(row.original)">
            <UButton icon="i-lucide-ellipsis-vertical" color="neutral" variant="ghost" />
          </UDropdownMenu>
        </template>
      </UTable>

      <div class="mt-6 flex items-center justify-between">
        <span class="text-sm text-gray-500">
          Всього: {{ totalItems }}
        </span>
        <div class="flex items-center gap-2">
          <UButton :disabled="currentPage <= 1" @click="currentPage--" variant="outline">Попередня</UButton>
          <span class="text-sm font-medium">{{ currentPage }} / {{ totalPages }}</span>
          <UButton :disabled="currentPage >= totalPages" @click="currentPage++" variant="outline">Наступна</UButton>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const currentPage = ref(1)

const { data, refresh } = await useAsyncData<any>('categories-list', () =>
  $fetch('/api/admin/blog/categories', {
    query: {
      page: currentPage.value
    }
  }),
  {
    watch: [currentPage],
    getCachedData: () => null
  }
)

onMounted(() => {
  refresh()
})

const categories = computed(() => data.value?.data ?? [])
const totalItems = computed(() => data.value?.meta?.total ?? 0)
const totalPages = computed(() => data.value?.meta?.last_page ?? 1)

const columns = [
  { accessorKey: 'id', header: 'ID' },
  { accessorKey: 'title', header: 'Назва' },
  { accessorKey: 'parent_title', header: 'Батьківська категорія' },
  { accessorKey: 'actions', header: 'Дії' }
]

const getActions = (item: any) => [
  [
    {
      label: 'Редагувати',
      icon: 'i-lucide-pencil',
      to: `/categories/${item.id}/edit`
    },
    {
      label: 'Видалити',
      icon: 'i-lucide-trash',
      color: 'danger',
      onSelect: () => deleteCategory(item.id)
    }
  ]
]

const deleteCategory = async (id: number) => {
  if (confirm('Ви впевнені, що хочете видалити категорію?')) {
    try {
      await $fetch(`/api/admin/blog/categories/${id}`, {
        method: 'DELETE'
      })
      refresh()
    } catch (e) {
      alert('Помилка при видаленні категорії')
    }
  }
}
</script>
