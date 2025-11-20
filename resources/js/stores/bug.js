import { defineStore } from 'pinia';
import { bugAPI, voteAPI } from '../services/api';

export const useBugStore = defineStore('bug', {
    state: () => ({
        bugs: [],
        currentBug: null,
        loading: false,
        error: null,
        pagination: {
            current_page: 1,
            last_page: 1,
            per_page: 20,
            total: 0
        },
        filters: {
            app_id: null,
            category_id: null,
            status: null,
            severity: null,
            search: '',
            sort_by: 'created_at',
            sort_order: 'desc'
        }
    }),

    actions: {
        async fetchBugs(page = 1) {
            this.loading = true;
            this.error = null;
            try {
                const params = {
                    page,
                    ...this.filters
                };
                const response = await bugAPI.getAll(params);
                this.bugs = response.data.data;
                this.pagination = {
                    current_page: response.data.current_page,
                    last_page: response.data.last_page,
                    per_page: response.data.per_page,
                    total: response.data.total
                };
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch bugs';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async fetchBugById(id) {
            this.loading = true;
            this.error = null;
            try {
                const response = await bugAPI.getById(id);
                this.currentBug = response.data;
                return response.data;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to fetch bug';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async createBug(bugData) {
            this.loading = true;
            this.error = null;
            try {
                const response = await bugAPI.create(bugData);
                return response.data.bug;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to create bug';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async updateBug(id, bugData) {
            this.loading = true;
            this.error = null;
            try {
                const response = await bugAPI.update(id, bugData);
                if (this.currentBug?.id === id) {
                    this.currentBug = response.data.bug;
                }
                return response.data.bug;
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to update bug';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async deleteBug(id) {
            this.loading = true;
            this.error = null;
            try {
                await bugAPI.delete(id);
                this.bugs = this.bugs.filter(bug => bug.id !== id);
                if (this.currentBug?.id === id) {
                    this.currentBug = null;
                }
            } catch (error) {
                this.error = error.response?.data?.message || 'Failed to delete bug';
                throw error;
            } finally {
                this.loading = false;
            }
        },

        async toggleVote(bugId) {
            try {
                const response = await voteAPI.toggle(bugId);
                // Update bug upvotes count
                if (this.currentBug?.id === bugId) {
                    this.currentBug.upvotes = response.data.upvotes;
                }
                const bug = this.bugs.find(b => b.id === bugId);
                if (bug) {
                    bug.upvotes = response.data.upvotes;
                }
                return response.data;
            } catch (error) {
                throw error;
            }
        },

        setFilter(key, value) {
            this.filters[key] = value;
        },

        resetFilters() {
            this.filters = {
                app_id: null,
                category_id: null,
                status: null,
                severity: null,
                search: '',
                sort_by: 'created_at',
                sort_order: 'desc'
            };
        }
    }
});
