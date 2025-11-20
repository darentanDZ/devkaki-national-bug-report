import { defineStore } from 'pinia';
import { appAPI, categoryAPI } from '../services/api';

export const useAppStore = defineStore('app', {
    state: () => ({
        apps: [],
        categories: [],
        loading: false,
        error: null
    }),

    actions: {
        async fetchApps() {
            this.loading = true;
            this.error = null;
            try {
                const response = await appAPI.getAll();
                this.apps = response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch apps';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchCategories() {
            this.loading = true;
            this.error = null;
            try {
                const response = await categoryAPI.getAll();
                this.categories = response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch categories';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createApp(appData) {
            try {
                const response = await appAPI.create(appData);
                this.apps.push(response.data.app);
                return response.data.app;
            } catch (error) {
                throw error;
            }
        },

        async updateApp(id, appData) {
            try {
                const response = await appAPI.update(id, appData);
                const index = this.apps.findIndex(app => app.id === id);
                if (index !== -1) {
                    this.apps[index] = response.data.app;
                }
                return response.data.app;
            } catch (error) {
                throw error;
            }
        },

        async deleteApp(id) {
            try {
                await appAPI.delete(id);
                this.apps = this.apps.filter(app => app.id !== id);
            } catch (error) {
                throw error;
            }
        },

        async createCategory(categoryData) {
            try {
                const response = await categoryAPI.create(categoryData);
                this.categories.push(response.data.category);
                return response.data.category;
            } catch (error) {
                throw error;
            }
        },

        async updateCategory(id, categoryData) {
            try {
                const response = await categoryAPI.update(id, categoryData);
                const index = this.categories.findIndex(cat => cat.id === id);
                if (index !== -1) {
                    this.categories[index] = response.data.category;
                }
                return response.data.category;
            } catch (error) {
                throw error;
            }
        },

        async deleteCategory(id) {
            try {
                await categoryAPI.delete(id);
                this.categories = this.categories.filter(cat => cat.id !== id);
            } catch (error) {
                throw error;
            }
        }
    }
});
