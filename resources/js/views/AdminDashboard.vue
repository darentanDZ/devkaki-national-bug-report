<template>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-6">Admin Dashboard</h1>

        <div class="grid md:grid-cols-2 gap-6">
            <!-- Manage Apps -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Manage Apps</h2>
                <div class="space-y-2 mb-4">
                    <div v-for="app in apps" :key="app.id" class="flex justify-between items-center p-2 border-b">
                        <span>{{ app.name }}</span>
                        <button @click="deleteApp(app.id)" class="text-red-600 text-sm hover:underline">
                            Delete
                        </button>
                    </div>
                </div>
                <form @submit.prevent="addApp" class="space-y-2">
                    <input
                        v-model="newApp.name"
                        type="text"
                        placeholder="App name"
                        class="w-full border rounded px-3 py-2"
                        required
                    />
                    <input
                        v-model="newApp.company"
                        type="text"
                        placeholder="Company"
                        class="w-full border rounded px-3 py-2"
                    />
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Add App
                    </button>
                </form>
            </div>

            <!-- Manage Categories -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Manage Categories</h2>
                <div class="space-y-2 mb-4">
                    <div v-for="category in categories" :key="category.id" class="flex justify-between items-center p-2 border-b">
                        <div class="flex items-center gap-2">
                            <span
                                class="w-4 h-4 rounded"
                                :style="{ backgroundColor: category.color }"
                            ></span>
                            <span>{{ category.name }}</span>
                        </div>
                        <button @click="deleteCategory(category.id)" class="text-red-600 text-sm hover:underline">
                            Delete
                        </button>
                    </div>
                </div>
                <form @submit.prevent="addCategory" class="space-y-2">
                    <input
                        v-model="newCategory.name"
                        type="text"
                        placeholder="Category name"
                        class="w-full border rounded px-3 py-2"
                        required
                    />
                    <input
                        v-model="newCategory.color"
                        type="color"
                        class="w-full border rounded px-3 py-2"
                    />
                    <button
                        type="submit"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
                    >
                        Add Category
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useAppStore } from '../stores/app';

const appStore = useAppStore();

const apps = ref([]);
const categories = ref([]);

const newApp = ref({ name: '', company: '' });
const newCategory = ref({ name: '', color: '#3B82F6' });

onMounted(async () => {
    await fetchData();
});

const fetchData = async () => {
    await appStore.fetchApps();
    await appStore.fetchCategories();
    apps.value = appStore.apps;
    categories.value = appStore.categories;
};

const addApp = async () => {
    try {
        await appStore.createApp(newApp.value);
        newApp.value = { name: '', company: '' };
        await fetchData();
    } catch (error) {
        alert('Failed to add app');
    }
};

const deleteApp = async (id) => {
    if (!confirm('Are you sure?')) return;
    try {
        await appStore.deleteApp(id);
        await fetchData();
    } catch (error) {
        alert('Failed to delete app');
    }
};

const addCategory = async () => {
    try {
        await appStore.createCategory(newCategory.value);
        newCategory.value = { name: '', color: '#3B82F6' };
        await fetchData();
    } catch (error) {
        alert('Failed to add category');
    }
};

const deleteCategory = async (id) => {
    if (!confirm('Are you sure?')) return;
    try {
        await appStore.deleteCategory(id);
        await fetchData();
    } catch (error) {
        alert('Failed to delete category');
    }
};
</script>
