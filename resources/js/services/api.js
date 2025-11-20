import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Add auth token to requests
api.interceptors.request.use(config => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Handle response errors
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

// Auth API
export const authAPI = {
    register: (data) => api.post('/register', data),
    login: (data) => api.post('/login', data),
    logout: () => api.post('/logout'),
    me: () => api.get('/me')
};

// Bug API
export const bugAPI = {
    getAll: (params) => api.get('/bugs', { params }),
    getById: (id) => api.get(`/bugs/${id}`),
    create: (data) => api.post('/bugs', data),
    update: (id, data) => api.put(`/bugs/${id}`, data),
    delete: (id) => api.delete(`/bugs/${id}`)
};

// Comment API
export const commentAPI = {
    create: (data) => api.post('/comments', data),
    update: (id, data) => api.put(`/comments/${id}`, data),
    delete: (id) => api.delete(`/comments/${id}`)
};

// Vote API
export const voteAPI = {
    toggle: (bugId) => api.post(`/bugs/${bugId}/vote`),
    check: (bugId) => api.get(`/bugs/${bugId}/vote/check`)
};

// App API
export const appAPI = {
    getAll: () => api.get('/apps'),
    getById: (id) => api.get(`/apps/${id}`),
    create: (data) => api.post('/apps', data),
    update: (id, data) => api.put(`/apps/${id}`, data),
    delete: (id) => api.delete(`/apps/${id}`)
};

// Category API
export const categoryAPI = {
    getAll: () => api.get('/categories'),
    getById: (id) => api.get(`/categories/${id}`),
    create: (data) => api.post('/categories', data),
    update: (id, data) => api.put(`/categories/${id}`, data),
    delete: (id) => api.delete(`/categories/${id}`)
};

export default api;
