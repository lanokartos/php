<template>
  <div class="min-h-screen bg-white p-8 font-sans text-black">
    <div class="max-w-2xl mx-auto rounded-xl border border-gray-200 bg-white shadow-sm p-8">
      <h1 class="text-2xl font-bold mb-6">Створення категорії</h1>

      <UForm :schema="schema" :state="state" @submit="onSubmit" class="space-y-6">
        <UFormField label="Назва категорії" name="title">
          <UInput v-model="state.title" class="w-full text-black bg-white" />
        </UFormField>

        <UFormField label="Slug" name="slug">
          <UInput v-model="state.slug" class="w-full text-black bg-white" />
        </UFormField>

        <UFormField label="Опис" name="description">
          <UTextarea v-model="state.description" class="w-full text-black bg-white" />
        </UFormField>

        <UFormField label="Батьківська категорія" name="parent_id">
          <USelect
            v-model="state.parent_id"
            :items="categories"
            value-key="id"
            label-key="title"
            class="w-full text-black bg-white"
          />
        </UFormField>

        <div class="flex gap-4">
          <UButton type="submit" color="primary">Створити</UButton>
          <UButton to="/categories" color="neutral" variant="outline">Скасувати</UButton>
        </div>
      </UForm>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { z } from 'zod'
import type { FormSubmitEvent } from '#ui/types'

const router = useRouter()

const state = ref({
  title: '',
  slug: '',
  description: '',
  parent_id: 1
})

const categories = ref<any[]>([])

const schema = z.object({
  title: z.string().min(5, 'Назва повинна бути не менше 5 символів').max(200),
  slug: z.string().max(200).optional().or(z.literal('')),
  description: z.string().min(3, 'Опис повинен бути не менше 3 символів').max(500).optional().or(z.literal('')),
  parent_id: z.coerce.number().int()
})

type Schema = z.output<typeof schema>

const loadCategoriesList = async () => {
  try {
    const data = await $fetch<any[]>('/api/admin/blog/categories?all=1')
    categories.value = data
  } catch (e) {
    console.error(e)
  }
}

onMounted(() => {
  loadCategoriesList()
})

const onSubmit = async (event: FormSubmitEvent<Schema>) => {
  try {
    await $fetch('/api/admin/blog/categories', {
      method: 'POST',
      body: event.data
    })
    router.push('/categories')
  } catch (e: any) {
    alert('Помилка при створенні: ' + (e.data?.message || e.message))
  }
}
</script>
