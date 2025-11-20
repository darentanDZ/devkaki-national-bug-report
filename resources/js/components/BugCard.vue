<template>
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow">
        <div class="flex justify-between items-start mb-3">
            <div class="flex-1">
                <router-link :to="`/bugs/${bug.id}`" class="text-xl font-semibold text-gray-900 hover:text-blue-600">
                    {{ bug.title }}
                </router-link>
                <div class="flex items-center gap-2 mt-2">
                    <span :class="severityClass" class="px-2 py-1 text-xs rounded">
                        {{ bug.severity }}
                    </span>
                    <span :class="statusClass" class="px-2 py-1 text-xs rounded">
                        {{ bug.status }}
                    </span>
                    <span class="text-xs text-gray-500" :style="{ color: bug.category?.color }">
                        {{ bug.category?.name }}
                    </span>
                </div>
            </div>
            <div class="flex flex-col items-center">
                <button
                    @click="handleVote"
                    :disabled="!authStore.isAuthenticated"
                    class="text-gray-600 hover:text-blue-600 disabled:opacity-50"
                >
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                    </svg>
                </button>
                <span class="text-sm font-semibold">{{ bug.upvotes || 0 }}</span>
            </div>
        </div>

        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ bug.description }}</p>

        <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center gap-3">
                <span>{{ bug.app?.name }}</span>
                <span>{{ bug.user?.name }}</span>
                <span>{{ formatDate(bug.created_at) }}</span>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    {{ bug.comments_count || 0 }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    {{ bug.views || 0 }}
                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useBugStore } from '../stores/bug';

const props = defineProps({
    bug: {
        type: Object,
        required: true
    }
});

const authStore = useAuthStore();
const bugStore = useBugStore();

const severityClass = computed(() => {
    const classes = {
        low: 'bg-green-100 text-green-800',
        medium: 'bg-yellow-100 text-yellow-800',
        high: 'bg-orange-100 text-orange-800',
        critical: 'bg-red-100 text-red-800'
    };
    return classes[props.bug.severity] || 'bg-gray-100 text-gray-800';
});

const statusClass = computed(() => {
    const classes = {
        open: 'bg-blue-100 text-blue-800',
        investigating: 'bg-purple-100 text-purple-800',
        confirmed: 'bg-orange-100 text-orange-800',
        fixed: 'bg-green-100 text-green-800',
        closed: 'bg-gray-100 text-gray-800',
        'wont-fix': 'bg-red-100 text-red-800'
    };
    return classes[props.bug.status] || 'bg-gray-100 text-gray-800';
});

const handleVote = async () => {
    if (!authStore.isAuthenticated) return;
    try {
        await bugStore.toggleVote(props.bug.id);
    } catch (error) {
        console.error('Failed to toggle vote:', error);
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
