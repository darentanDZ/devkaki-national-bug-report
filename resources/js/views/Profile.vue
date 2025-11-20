<template>
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-6">Profile</h1>

        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <p class="text-lg">{{ authStore.currentUser?.name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <p class="text-lg">{{ authStore.currentUser?.email }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <p class="text-lg capitalize">{{ authStore.currentUser?.role }}</p>
            </div>

            <div class="border-t pt-4 mt-4">
                <h2 class="text-xl font-semibold mb-4">Your Statistics</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-blue-50 rounded">
                        <p class="text-3xl font-bold text-blue-600">{{ userStats.bugsReported }}</p>
                        <p class="text-gray-600">Bugs Reported</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded">
                        <p class="text-3xl font-bold text-green-600">{{ userStats.commentsPosted }}</p>
                        <p class="text-gray-600">Comments Posted</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded">
                        <p class="text-3xl font-bold text-purple-600">{{ userStats.votesGiven }}</p>
                        <p class="text-gray-600">Votes Given</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';

const authStore = useAuthStore();
const userStats = ref({
    bugsReported: 0,
    commentsPosted: 0,
    votesGiven: 0
});

// Note: In a real app, you'd fetch these stats from an API endpoint
</script>
