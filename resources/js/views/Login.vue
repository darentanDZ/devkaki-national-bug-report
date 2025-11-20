<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Login to your account</h2>
                <p class="mt-2 text-gray-600">
                    Or
                    <router-link to="/register" class="text-blue-600 hover:underline">
                        create a new account
                    </router-link>
                </p>
            </div>

            <form @submit.prevent="handleLogin" class="bg-white rounded-lg shadow p-8 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        v-model="formData.email"
                        type="email"
                        required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="your@email.com"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        v-model="formData.password"
                        type="password"
                        required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="••••••••"
                    />
                </div>

                <div v-if="error" class="bg-red-50 text-red-600 p-3 rounded text-sm">
                    {{ error }}
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold disabled:opacity-50"
                >
                    {{ loading ? 'Logging in...' : 'Login' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();

const formData = ref({
    email: '',
    password: ''
});

const loading = ref(false);
const error = ref(null);

const handleLogin = async () => {
    loading.value = true;
    error.value = null;
    try {
        await authStore.login(formData.value);
        const redirect = route.query.redirect || '/';
        router.push(redirect);
    } catch (err) {
        error.value = err.response?.data?.message || 'Login failed. Please check your credentials.';
    } finally {
        loading.value = false;
    }
};
</script>
