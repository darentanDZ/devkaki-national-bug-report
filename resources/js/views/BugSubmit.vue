<template>
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold mb-6">Submit Bug Report</h1>

        <form @submit.prevent="submitBug" class="bg-white rounded-lg shadow p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">App *</label>
                <select v-model="formData.app_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Select an app</option>
                    <option v-for="app in apps" :key="app.id" :value="app.id">
                        {{ app.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select v-model="formData.category_id" required class="w-full border rounded px-3 py-2">
                    <option value="">Select a category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                <input
                    v-model="formData.title"
                    type="text"
                    required
                    class="w-full border rounded px-3 py-2"
                    placeholder="Brief description of the bug"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description *</label>
                <textarea
                    v-model="formData.description"
                    required
                    class="w-full border rounded px-3 py-2"
                    rows="4"
                    placeholder="Detailed description of the bug"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Steps to Reproduce</label>
                <textarea
                    v-model="formData.steps_to_reproduce"
                    class="w-full border rounded px-3 py-2"
                    rows="3"
                    placeholder="1. Go to...&#10;2. Click on...&#10;3. See error"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Expected Behavior</label>
                <textarea
                    v-model="formData.expected_behavior"
                    class="w-full border rounded px-3 py-2"
                    rows="2"
                    placeholder="What should happen?"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Actual Behavior</label>
                <textarea
                    v-model="formData.actual_behavior"
                    class="w-full border rounded px-3 py-2"
                    rows="2"
                    placeholder="What actually happens?"
                ></textarea>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Device Info</label>
                    <input
                        v-model="formData.device_info"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        placeholder="iPhone 13 Pro"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">OS Version</label>
                    <input
                        v-model="formData.os_version"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        placeholder="iOS 17.1"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">App Version</label>
                    <input
                        v-model="formData.app_version"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                        placeholder="2.3.1"
                    />
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Severity *</label>
                <select v-model="formData.severity" required class="w-full border rounded px-3 py-2">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="critical">Critical</option>
                </select>
            </div>

            <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded">
                {{ error }}
            </div>

            <div class="flex gap-3">
                <button
                    type="submit"
                    :disabled="loading"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
                >
                    {{ loading ? 'Submitting...' : 'Submit Bug Report' }}
                </button>
                <button
                    type="button"
                    @click="$router.push('/bugs')"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useBugStore } from '../stores/bug';
import { useAppStore } from '../stores/app';

const router = useRouter();
const bugStore = useBugStore();
const appStore = useAppStore();

const apps = ref([]);
const categories = ref([]);
const loading = ref(false);
const error = ref(null);

const formData = ref({
    app_id: '',
    category_id: '',
    title: '',
    description: '',
    steps_to_reproduce: '',
    expected_behavior: '',
    actual_behavior: '',
    device_info: '',
    os_version: '',
    app_version: '',
    severity: 'medium'
});

onMounted(async () => {
    await appStore.fetchApps();
    await appStore.fetchCategories();
    apps.value = appStore.apps;
    categories.value = appStore.categories;
});

const submitBug = async () => {
    loading.value = true;
    error.value = null;
    try {
        const bug = await bugStore.createBug(formData.value);
        router.push(`/bugs/${bug.id}`);
    } catch (err) {
        error.value = err.response?.data?.message || 'Failed to submit bug report';
    } finally {
        loading.value = false;
    }
};
</script>
