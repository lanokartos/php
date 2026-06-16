<template>
  <div class="min-h-screen bg-white p-8 font-sans text-black">
    <div class="flex items-center gap-4 bg-white border border-gray-200 rounded-xl px-8 py-4 mb-6 shadow-sm justify-between">
      <div class="flex gap-4">
        <NuxtLink to="/BlogPostsUi" class="font-semibold text-black transition-colors underline decoration-2 decoration-primary underline-offset-4">Пости</NuxtLink>
        <NuxtLink to="/categories" class="font-semibold text-gray-600 hover:text-black transition-colors">Категорії</NuxtLink>
      </div>
      <UButton to="/posts/create" icon="i-lucide-plus" size="md">Додати статтю</UButton>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col p-6">
      <h1 class="text-2xl font-bold mb-6">Публікації блогу</h1>

      <UTable :columns="columns" :data="posts">
        <template #title-cell="{ row }">
          <NuxtLink :to="`/posts/${row.original.id}`" class="font-medium text-primary hover:underline">
            {{ row.original.title }}
          </NuxtLink>
        </template>
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

const { data, refresh } = await useAsyncData<any>('blog-posts-list', () =>
  $fetch('/api/admin/blog/posts', {
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

const posts = computed(() => {
  return (data.value?.data ?? []).map((post: any) => ({
    id: post.id,
    title: post.title,
    category: post.category_title ?? '—',
    author: post.author_name ?? '—',
    published_at: post.date_published ? new Date(post.date_published).toLocaleDateString('uk-UA') : '—',
    is_published: post.is_published ? 'Опубліковано' : 'Чернетка'
  }))
})

const totalItems = computed(() => data.value?.total ?? 0)
const totalPages = computed(() => data.value?.last_page ?? 1)

const columns = [
  { accessorKey: 'id', header: 'ID' },
  { accessorKey: 'title', header: 'Заголовок' },
  { accessorKey: 'category', header: 'Категорія' },
  { accessorKey: 'author', header: 'Автор' },
  { accessorKey: 'published_at', header: 'Дата публікації' },
  { accessorKey: 'is_published', header: 'Статус' },
  { accessorKey: 'actions', header: 'Дії' }
]

const getActions = (item: any) => [
  [
    {
      label: 'Редагувати',
      icon: 'i-lucide-pencil',
      to: `/posts/${item.id}/edit`
    },
    {
      label: 'Видалити',
      icon: 'i-lucide-trash',
      color: 'danger',
      onSelect: () => deletePost(item.id)
    }
  ]
]

const deletePost = async (id: number) => {
  if (confirm('Ви впевнені, що хочете видалити цю статтю?')) {
    try {
      await $fetch(`/api/admin/blog/posts/${id}`, {
        method: 'DELETE'
      })
      refresh()
    } catch (e) {
      alert('Помилка при видаленні статті')
    }
  }
}
</script>
