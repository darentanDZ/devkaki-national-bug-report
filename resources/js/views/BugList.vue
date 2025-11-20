<template>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Bug Reports</h1>
            <router-link
                v-if="authStore.isAuthenticated"
                to="/bugs/submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            >
                Submit Bug
            </router-link>
        </div>

        <div class="grid lg:grid-cols-4 gap-6">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <BugFilters />
            </div>

            <!-- Bugs List -->
            <div class="lg:col-span-3">
                <div v-if="loading" class="text-center py-12">
                    <p class="text-gray-500">Loading bugs...</p>
                </div>
                <div v-else-if="error" class="text-center py-12">
                    <p class="text-red-500">{{ error }}</p>
                </div>
                <div v-else-if="bugs.length === 0" class="text-center py-12">
                    <p class="text-gray-500">No bugs found.</p>
                </div>
                <div v-else class="space-y-4">
                    <BugCard v-for="bug in bugs" :key="bug.id" :bug="bug" />
                </div>

                <!-- Pagination -->
                <div v-if="pagination.last_page > 1" class="mt-8 flex justify-center gap-2">
                    <button
                        v-for="page in visiblePages"
                        :key="page"
                        @click="changePage(page)"
                        :class="[
                            'px-4 py-2 rounded',
                            page === pagination.current_page
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-100'
                        ]"
                    >
                        {{ page }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useBugStore } from '../stores/bug';
import { useAuthStore } from '../stores/auth';
import BugCard from '../components/BugCard.vue';
import BugFilters from '../components/BugFilters.vue';

const bugStore = useBugStore();
const authStore = useAuthStore();

const bugs = computed(() => bugStore.bugs);
const loading = computed(() => bugStore.loading);
const error = computed(() => bugStore.error);
const pagination = computed(() => bugStore.pagination);

const visiblePages = computed(() => {
    const pages = [];
    const current = pagination.value.current_page;
    const last = pagination.value.last_page;

    for (let i = Math.max(1, current - 2); i <= Math.min(last, current + 2); i++) {
        pages.push(i);
    }

    return pages;
});

const changePage = (page) => {
    bugStore.fetchBugs(page);
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    bugStore.fetchBugs();
});
</script>
