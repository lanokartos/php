<template>
  <div class="min-h-screen bg-white p-8 font-sans text-black">
    <div class="max-w-2xl mx-auto rounded-xl border border-gray-200 bg-white shadow-sm p-8">
      <h1 class="text-2xl font-bold mb-6">Редагування статті #{{ route.params.id }}</h1>

      <UForm :schema="schema" :state="state" @submit="onSubmit" class="space-y-6">
        <UFormField label="Заголовок" name="title">
          <UInput v-model="state.title" class="w-full text-black bg-white" />
        </UFormField>

        <UFormField label="Вміст статті" name="content_raw">
          <UTextarea v-model="state.content_raw" :rows="8" class="w-full text-black bg-white" />
        </UFormField>

        <UFormField label="ID Категорії" name="category_id">
          <UInput type="number" v-model="state.category_id" class="w-full text-black bg-white" />
        </UFormField>

        <UFormField label="Опублікувати статтю" name="is_published">
          <UCheckbox v-model="state.is_published" label="Опубліковано" />
        </UFormField>

        <div class="flex gap-4">
          <UButton type="submit" color="primary">Зберегти</UButton>
          <UButton to="/BlogPostsUi" color="neutral" variant="outline">Скасувати</UButton>
        </div>
      </UForm>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { z } from 'zod'
import type { FormSubmitEvent } from '#ui/types'

const route = useRoute()
const router = useRouter()

const state = ref({
  title: '',
  content_raw: '',
  category_id: 1,
  is_published: false
})

const schema = z.object({
  title: z.string().min(5, 'Заголовок має бути не менше 5 символів').max(200),
  content_raw: z.string().min(5, 'Вміст статті має бути не менше 5 символів').max(10000),
  category_id: z.coerce.number().int(),
  is_published: z.boolean().optional()
})

type Schema = z.output<typeof schema>

const loadPost = async () => {
  try {
    const data = await $fetch<any>(`/api/admin/blog/posts/${route.params.id}`)
    state.value = {
      title: data.title || '',
      content_raw: data.content_raw || '',
      category_id: data.category_id || 1,
      is_published: !!data.is_published
    }
  } catch (e) {
    alert('Статтю не знайдено')
    router.push('/BlogPostsUi')
  }
}

onMounted(async () => {
  await loadPost()
})

const onSubmit = async (event: FormSubmitEvent<Schema>) => {
  try {
    await $fetch(`/api/admin/blog/posts/${route.params.id}`, {
      method: 'PUT',
      body: event.data
    })
    router.push('/BlogPostsUi')
  } catch (e: any) {
    alert('Помилка при збереженні статті: ' + (e.data?.message || e.message))
  }
}
</script>
