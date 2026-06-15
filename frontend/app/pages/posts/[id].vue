<template>
  <div class="min-h-screen bg-[#f8f9fa] py-12 px-4 sm:px-6 lg:px-8 font-sans text-gray-900">
    <div class="max-w-3xl mx-auto">
      <div v-if="pending" class="flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-gray-200 shadow-sm">
        <UIcon name="i-lucide-loader-2" class="w-8 h-8 text-gray-400 animate-spin mb-4" />
        <p class="text-sm text-gray-500">Завантаження публікації...</p>
      </div>

      <div v-else-if="error || !post" class="text-center py-20 bg-white rounded-xl border border-gray-200 shadow-sm">
        <UIcon name="i-lucide-alert-circle" class="w-12 h-12 text-red-500 mx-auto mb-4" />
        <h2 class="text-xl font-semibold text-gray-950 mb-2">Не вдалося знайти публікацію</h2>
        <p class="text-sm text-gray-500 mb-6">Можливо, запис було видалено або посилання застаріло.</p>
        <NuxtLink 
          to="/BlogPostsUi" 
          class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 text-sm font-medium transition-colors"
        >
          Повернутися до списку
        </NuxtLink>
      </div>

      <article v-else class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden p-8 sm:p-12">
        <div class="flex flex-wrap items-center gap-2 text-sm text-gray-400 mb-6">
          <span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-700 font-medium text-xs uppercase tracking-wider">
            {{ post.category?.title || 'Без категорії' }}
          </span>
          <span class="text-gray-300">•</span>
          <span>Автор: <strong class="text-gray-700 font-medium">{{ post.user?.name || '—' }}</strong></span>
          <span class="text-gray-300">•</span>
          <time :datetime="post.published_at" class="text-gray-500">
            {{ post.published_at ? new Date(post.published_at).toLocaleDateString('uk-UA') : 'Не опубліковано' }}
          </time>
        </div>

        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
          {{ post.title }}
        </h1>

        <div v-if="post.excerpt" class="border-l-4 border-gray-200 pl-4 py-1 text-gray-500 italic text-lg mb-8">
          {{ post.excerpt }}
        </div>

        <hr class="border-gray-100 my-8" />

        <div class="prose prose-gray max-w-none">
          <div v-if="post.content_html" v-html="post.content_html"></div>
          <div v-else class="whitespace-pre-line text-gray-700 leading-relaxed">
            {{ post.content_raw }}
          </div>
        </div>
      </article>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const postId = route.params.id

const { data: post, pending, error } = await useAsyncData<any>(
  `blog-post-${postId}`,
  () => $fetch(`/api/admin/blog/posts/${postId}`),
  {
    server: false
  }
)
</script>
