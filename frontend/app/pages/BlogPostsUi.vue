<template>
  <div class="min-h-screen bg-[#f8f9fa] p-8 font-sans text-gray-900">
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden flex flex-col">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600 whitespace-nowrap">
          <thead class="bg-[#fcfcfd] text-gray-500 border-b border-gray-200">
            <tr>
              <th scope="col" class="px-4 py-3.5 font-normal text-[13px] align-middle border-r border-gray-100">
                Заголовок
              </th>
              <th scope="col" class="px-6 py-3.5 font-normal text-[13px] text-center align-middle">
                Деталі
              </th>
              <th scope="col" class="px-4 py-3.5 font-normal text-[13px] text-center align-middle">
                Тип
              </th>
              <th scope="col" class="px-4 py-3.5 font-normal text-[13px] align-middle text-center">
                Опубліковано
              </th>
              <th scope="col" class="px-4 py-3.5 font-normal text-[13px] align-middle">
                ID
              </th>
              <th scope="col" class="px-4 py-3.5 font-normal text-[13px] align-middle">
                Статус
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="pending">
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">Завантаження...</td>
            </tr>
            <tr v-else-if="error">
              <td colspan="6" class="px-4 py-8 text-center text-red-500">Не вдалося завантажити дані</td>
            </tr>
            <tr v-else-if="tableRows.length === 0">
              <td colspan="6" class="px-4 py-8 text-center text-gray-500">Немає публікацій</td>
            </tr>
            <tr 
              v-else
              v-for="row in tableRows" 
              :key="row.id"
              class="border-b border-gray-100 hover:bg-gray-50/80 transition-colors"
            >
              <td class="px-4 py-4 align-middle border-r border-gray-100">
                <div class="min-w-[280px]">
                  <a :href="row.editUrl" class="font-medium text-gray-700 hover:text-gray-900 hover:underline uppercase text-[13px] tracking-wide">
                    {{ row.title }}
                  </a>
                </div>
              </td>
              <td class="px-6 py-4 text-center align-middle">
                <div class="flex justify-center">
                  <UIcon name="i-lucide-zoom-in" class="w-5 h-5 text-gray-400" />
                </div>
              </td>
              <td class="px-4 py-4 text-center align-middle text-[13px] text-gray-600">
                0
              </td>
              <td class="px-4 py-4 text-center align-middle text-[13px] text-gray-600">
                {{ row.publishedAt !== '—' ? row.publishedAt : '—' }}
              </td>
              <td class="px-4 py-4 align-middle text-[13px] text-gray-600">
                {{ row.id }}
              </td>
              <td class="px-4 py-4 align-middle text-[13px] text-gray-600">
                Off Market (Published)
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="px-5 py-4 flex items-center justify-between bg-white text-sm text-gray-500">
        <div>{{ pageInfoText }}</div>
        <div class="flex items-center gap-2">
          <button 
            :disabled="currentPage <= 1" 
            @click="currentPage--" 
            class="px-3 py-1.5 rounded-md border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Попередня
          </button>
          <span class="px-3 py-1.5 font-medium text-gray-700">{{ currentPage }} / {{ totalPages }}</span>
          <button 
            :disabled="currentPage >= totalPages" 
            @click="currentPage++" 
            class="px-3 py-1.5 rounded-md border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            Наступна
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const currentPage = ref(1)

const { data, pending, error } = await useAsyncData<any>(
  'blog-posts-ui',
  () => $fetch('/api/admin/blog/posts', {
    query: {
      page: currentPage.value
    }
  }),
  {
    watch: [currentPage],
    server: false
  }
)

const tableRows = computed(() => {
  return (data.value?.data ?? []).map((post: any) => ({
    id: post.id,
    author: post.user?.name ?? '—',
    category: post.category?.title ?? '—',
    title: post.title,
    publishedAt: post.published_at ? new Date(post.published_at).toLocaleDateString('uk-UA') : '—',
    editUrl: `/admin/blog/posts/${post.id}/edit`
  }))
})

const totalItems = computed(() => data.value?.total ?? 0)
const totalPages = computed(() => data.value?.last_page ?? 1)

const pageInfoText = computed(() => {
  if (!data.value?.from || !data.value?.to) {
    return 'Поки немає даних для показу'
  }
  return `Показано ${data.value.from}-${data.value.to} з ${data.value.total}`
})
</script>
