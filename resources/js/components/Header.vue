<template>
    <header class="bg-white shadow">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between items-center">
                <div class="flex items-center">
                    <router-link to="/" class="text-xl font-bold text-blue-600">
                        Devkaki Bug Report
                    </router-link>
                    <div class="hidden md:ml-10 md:flex md:space-x-8">
                        <router-link to="/bugs" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                            Browse Bugs
                        </router-link>
                        <router-link v-if="authStore.isAuthenticated" to="/bugs/submit" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                            Submit Bug
                        </router-link>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <template v-if="authStore.isAuthenticated">
                        <router-link v-if="authStore.isAdmin" to="/admin" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                            Admin
                        </router-link>
                        <router-link to="/profile" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                            {{ authStore.currentUser?.name }}
                        </router-link>
                        <button @click="handleLogout" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            Logout
                        </button>
                    </template>
                    <template v-else>
                        <router-link to="/login" class="text-gray-700 hover:text-blue-600 px-3 py-2">
                            Login
                        </router-link>
                        <router-link to="/register" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Register
                        </router-link>
                    </template>
                </div>
            </div>
        </nav>
    </header>
</template>

<script setup>
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
    await authStore.logout();
    router.push('/');
};
</script>
