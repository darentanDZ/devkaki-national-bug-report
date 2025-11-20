<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Create your account</h2>
                <p class="mt-2 text-gray-600">
                    Already have an account?
                    <router-link to="/login" class="text-blue-600 hover:underline">
                        Login here
                    </router-link>
                </p>
            </div>

            <form @submit.prevent="handleRegister" class="bg-white rounded-lg shadow p-8 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input
                        v-model="formData.name"
                        type="text"
                        required
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Your name"
                    />
                </div>

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
                        minlength="8"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="At least 8 characters"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input
                        v-model="formData.password_confirmation"
                        type="password"
                        required
                        minlength="8"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        placeholder="Confirm your password"
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
                    {{ loading ? 'Creating account...' : 'Create Account' }}
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const authStore = useAuthStore();

const formData = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
});

const loading = ref(false);
const error = ref(null);

const handleRegister = async () => {
    if (formData.value.password !== formData.value.password_confirmation) {
        error.value = 'Passwords do not match';
        return;
    }

    loading.value = true;
    error.value = null;
    try {
        await authStore.register(formData.value);
        router.push('/');
    } catch (err) {
        error.value = err.response?.data?.message || 'Registration failed. Please try again.';
    } finally {
        loading.value = false;
    }
};
</script>
