import { defineStore } from 'pinia';
import { authAPI } from '../services/api';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('auth_token') || null,
        loading: false,
        error: null
    }),

    getters: {
        isAuthenticated: (state) => !!state.token && !!state.user,
        isAdmin: (state) => state.user?.role === 'admin',
        isModerator: (state) => state.user?.role === 'moderator' || state.user?.role === 'admin',
        currentUser: (state) => state.user
    },

    actions: {
        async register(credentials) {
            this.loading = true;
            this.error = null;
            try {
                const response = await authAPI.register(credentials);
                this.setAuth(response.data.user, response.data.token);
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Registration failed';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async login(credentials) {
            this.loading = true;
            this.error = null;
            try {
                const response = await authAPI.login(credentials);
                this.setAuth(response.data.user, response.data.token);
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Login failed';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await authAPI.logout();
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.clearAuth();
            }
        },

        async fetchUser() {
            if (!this.token) return;

            try {
                const response = await authAPI.me();
                this.user = response.data.user;
                localStorage.setItem('user', JSON.stringify(this.user));
            } catch (error) {
                this.clearAuth();
                throw error;
            }
        },

        setAuth(user, token) {
            this.user = user;
            this.token = token;
            localStorage.setItem('user', JSON.stringify(user));
            localStorage.setItem('auth_token', token);
        },

        clearAuth() {
            this.user = null;
            this.token = null;
            localStorage.removeItem('user');
            localStorage.removeItem('auth_token');
        }
    }
});
