<template>
    <div>
        <div class="bg-blue-600 text-white py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl font-bold mb-4">Devkaki National Bug Report Platform</h1>
                <p class="text-xl mb-8">Report and track bugs in Malaysian applications</p>
                <div class="flex gap-4 justify-center">
                    <router-link
                        to="/bugs"
                        class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100"
                    >
                        Browse Bugs
                    </router-link>
                    <router-link
                        v-if="authStore.isAuthenticated"
                        to="/bugs/submit"
                        class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800"
                    >
                        Submit Bug Report
                    </router-link>
                    <router-link
                        v-else
                        to="/register"
                        class="bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800"
                    >
                        Get Started
                    </router-link>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-2xl font-bold mb-6">Recent Bug Reports</h2>
            <div v-if="loading" class="text-center py-8">
                <p class="text-gray-500">Loading...</p>
            </div>
            <div v-else class="grid gap-4">
                <BugCard v-for="bug in recentBugs" :key="bug.id" :bug="bug" />
            </div>
            <div class="text-center mt-8">
                <router-link
                    to="/bugs"
                    class="text-blue-600 hover:underline font-semibold"
                >
                    View All Bugs →
                </router-link>
            </div>
        </div>

        <div class="bg-gray-50 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold mb-8 text-center">Platform Features</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Report Bugs</h3>
                        <p class="text-gray-600">Submit detailed bug reports with attachments and reproduction steps</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Vote & Prioritize</h3>
                        <p class="text-gray-600">Upvote important bugs to help prioritize fixes</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold mb-2">Discuss Solutions</h3>
                        <p class="text-gray-600">Collaborate with the community through threaded discussions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useBugStore } from '../stores/bug';
import { useAuthStore } from '../stores/auth';
import BugCard from '../components/BugCard.vue';

const bugStore = useBugStore();
const authStore = useAuthStore();
const recentBugs = ref([]);
const loading = ref(true);

onMounted(async () => {
    try {
        await bugStore.fetchBugs();
        recentBugs.value = bugStore.bugs.slice(0, 5);
    } catch (error) {
        console.error('Failed to fetch bugs:', error);
    } finally {
        loading.value = false;
    }
});
</script>
