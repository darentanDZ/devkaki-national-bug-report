<template>
    <div class="bg-white rounded-lg shadow p-4 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input
                v-model="localFilters.search"
                type="text"
                placeholder="Search bugs..."
                class="w-full border rounded px-3 py-2"
                @input="debounceSearch"
            />
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">App</label>
            <select v-model="localFilters.app_id" @change="applyFilters" class="w-full border rounded px-3 py-2">
                <option :value="null">All Apps</option>
                <option v-for="app in apps" :key="app.id" :value="app.id">
                    {{ app.name }}
                </option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select v-model="localFilters.category_id" @change="applyFilters" class="w-full border rounded px-3 py-2">
                <option :value="null">All Categories</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                    {{ category.name }}
                </option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select v-model="localFilters.status" @change="applyFilters" class="w-full border rounded px-3 py-2">
                <option :value="null">All Statuses</option>
                <option value="open">Open</option>
                <option value="investigating">Investigating</option>
                <option value="confirmed">Confirmed</option>
                <option value="fixed">Fixed</option>
                <option value="closed">Closed</option>
                <option value="wont-fix">Won't Fix</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
            <select v-model="localFilters.severity" @change="applyFilters" class="w-full border rounded px-3 py-2">
                <option :value="null">All Severities</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
            <select v-model="localFilters.sort_by" @change="applyFilters" class="w-full border rounded px-3 py-2">
                <option value="created_at">Date</option>
                <option value="upvotes">Upvotes</option>
                <option value="views">Views</option>
            </select>
        </div>

        <button
            @click="reset"
            class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300"
        >
            Reset Filters
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useBugStore } from '../stores/bug';
import { useAppStore } from '../stores/app';

const bugStore = useBugStore();
const appStore = useAppStore();

const apps = ref([]);
const categories = ref([]);
const localFilters = ref({ ...bugStore.filters });

let searchTimeout;

onMounted(async () => {
    await appStore.fetchApps();
    await appStore.fetchCategories();
    apps.value = appStore.apps;
    categories.value = appStore.categories;
});

const debounceSearch = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        applyFilters();
    }, 500);
};

const applyFilters = () => {
    Object.keys(localFilters.value).forEach(key => {
        bugStore.setFilter(key, localFilters.value[key]);
    });
    bugStore.fetchBugs();
};

const reset = () => {
    bugStore.resetFilters();
    localFilters.value = { ...bugStore.filters };
    bugStore.fetchBugs();
};
</script>
