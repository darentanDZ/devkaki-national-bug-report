<template>
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
        <div v-if="loading" class="text-center py-12">
            <p class="text-gray-500">Loading bug details...</p>
        </div>
        <div v-else-if="error" class="text-center py-12">
            <p class="text-red-500">{{ error }}</p>
        </div>
        <div v-else-if="bug" class="space-y-6">
            <!-- Bug Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold mb-2">{{ bug.title }}</h1>
                        <div class="flex items-center gap-3">
                            <span :class="severityClass" class="px-3 py-1 text-sm rounded">
                                {{ bug.severity }}
                            </span>
                            <span :class="statusClass" class="px-3 py-1 text-sm rounded">
                                {{ bug.status }}
                            </span>
                            <span class="text-sm" :style="{ color: bug.category?.color }">
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
                            <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z" />
                            </svg>
                        </button>
                        <span class="text-lg font-semibold">{{ bug.upvotes }}</span>
                        <span class="text-xs text-gray-500">upvotes</span>
                    </div>
                </div>

                <div class="border-t pt-4 space-y-4">
                    <div>
                        <h3 class="font-semibold mb-2">Description</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ bug.description }}</p>
                    </div>

                    <div v-if="bug.steps_to_reproduce">
                        <h3 class="font-semibold mb-2">Steps to Reproduce</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ bug.steps_to_reproduce }}</p>
                    </div>

                    <div v-if="bug.expected_behavior">
                        <h3 class="font-semibold mb-2">Expected Behavior</h3>
                        <p class="text-gray-700">{{ bug.expected_behavior }}</p>
                    </div>

                    <div v-if="bug.actual_behavior">
                        <h3 class="font-semibold mb-2">Actual Behavior</h3>
                        <p class="text-gray-700">{{ bug.actual_behavior }}</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-semibold">App:</span> {{ bug.app?.name }}
                        </div>
                        <div>
                            <span class="font-semibold">Reported by:</span> {{ bug.user?.name }}
                        </div>
                        <div v-if="bug.device_info">
                            <span class="font-semibold">Device:</span> {{ bug.device_info }}
                        </div>
                        <div v-if="bug.os_version">
                            <span class="font-semibold">OS:</span> {{ bug.os_version }}
                        </div>
                        <div v-if="bug.app_version">
                            <span class="font-semibold">App Version:</span> {{ bug.app_version }}
                        </div>
                        <div>
                            <span class="font-semibold">Views:</span> {{ bug.views }}
                        </div>
                    </div>
                </div>

                <div v-if="canEdit" class="border-t pt-4 mt-4 flex gap-2">
                    <button
                        @click="$router.push(`/bugs/${bug.id}/edit`)"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    >
                        Edit
                    </button>
                    <button
                        @click="deleteBug"
                        class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
                    >
                        Delete
                    </button>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">
                    Comments ({{ bug.comments_count || 0 }})
                </h2>

                <!-- Add Comment Form -->
                <div v-if="authStore.isAuthenticated" class="mb-6">
                    <textarea
                        v-model="newComment"
                        placeholder="Write a comment..."
                        class="w-full border rounded p-3 mb-2"
                        rows="4"
                    ></textarea>
                    <button
                        @click="submitComment"
                        :disabled="!newComment.trim()"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50"
                    >
                        Post Comment
                    </button>
                </div>
                <div v-else class="mb-6 text-center py-4 bg-gray-50 rounded">
                    <router-link to="/login" class="text-blue-600 hover:underline">
                        Login to comment
                    </router-link>
                </div>

                <!-- Comments List -->
                <CommentThread
                    v-if="bug.comments && bug.comments.length"
                    :comments="bug.comments"
                    :bugId="bug.id"
                    @comment-deleted="refreshBug"
                    @comment-updated="refreshBug"
                />
                <p v-else class="text-gray-500 text-center py-8">No comments yet.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useBugStore } from '../stores/bug';
import { useAuthStore } from '../stores/auth';
import { commentAPI } from '../services/api';
import CommentThread from '../components/CommentThread.vue';

const route = useRoute();
const router = useRouter();
const bugStore = useBugStore();
const authStore = useAuthStore();

const bug = computed(() => bugStore.currentBug);
const loading = computed(() => bugStore.loading);
const error = computed(() => bugStore.error);
const newComment = ref('');

const severityClass = computed(() => {
    const classes = {
        low: 'bg-green-100 text-green-800',
        medium: 'bg-yellow-100 text-yellow-800',
        high: 'bg-orange-100 text-orange-800',
        critical: 'bg-red-100 text-red-800'
    };
    return classes[bug.value?.severity] || 'bg-gray-100 text-gray-800';
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
    return classes[bug.value?.status] || 'bg-gray-100 text-gray-800';
});

const canEdit = computed(() => {
    return bug.value && (
        authStore.currentUser?.id === bug.value.user_id ||
        authStore.isModerator
    );
});

onMounted(() => {
    refreshBug();
});

const refreshBug = async () => {
    await bugStore.fetchBugById(route.params.id);
};

const handleVote = async () => {
    if (!authStore.isAuthenticated) return;
    await bugStore.toggleVote(bug.value.id);
};

const submitComment = async () => {
    try {
        await commentAPI.create({
            bug_id: bug.value.id,
            content: newComment.value
        });
        newComment.value = '';
        await refreshBug();
    } catch (error) {
        console.error('Failed to post comment:', error);
    }
};

const deleteBug = async () => {
    if (!confirm('Are you sure you want to delete this bug report?')) return;
    try {
        await bugStore.deleteBug(bug.value.id);
        router.push('/bugs');
    } catch (error) {
        console.error('Failed to delete bug:', error);
    }
};
</script>
