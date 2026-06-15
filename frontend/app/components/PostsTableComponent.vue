<template>
    <div class="container">
        <div class="flex justify-center">
            <div class="w-full">
                <nav class="navbar bg-gray-100">
                    <a href="/admin/blog/posts/create" class="">Додати</a>
                </nav>
                <div v-if="error" class="mb-4 rounded border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                    Не вдалося завантажити пости.
                </div>
                <div class="card">
                    <div class="card-body">
                        <div v-if="pending" class="mb-4 text-sm text-gray-500">
                            Завантаження...
                        </div>
                        <table class="table table-auto">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Автор</th>
                                <th>Категорія</th>
                                <th>Заголовок</th>
                                <th>Дата публікації</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr v-for="post in posts" :key="post.id">
                                    <td>{{ post.id }}</td>
                                    <td>{{ post.user?.name }}</td>
                                    <td>{{ post.category?.title }}</td>
                                    <td><a :href="'/admin/blog/posts/' + post.id + '/edit'">{{ post.title }}</a></td>
                                    <td>{{ post.published_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const posts = ref<any[]>([]);
const pending = ref(false);
const error = ref(false);

const getPosts = async () => {
    pending.value = true;
    error.value = false;

    try {
        const response = await $fetch<any>('/api/admin/blog/posts');
        posts.value = response.data;
    } catch (e) {
        error.value = true;
        posts.value = [];
    } finally {
        pending.value = false;
    }
};

getPosts();
</script>
