import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

// Lazy load views
const Home = () => import('../views/Home.vue');
const BugList = () => import('../views/BugList.vue');
const BugDetail = () => import('../views/BugDetail.vue');
const BugSubmit = () => import('../views/BugSubmit.vue');
const Login = () => import('../views/Login.vue');
const Register = () => import('../views/Register.vue');
const Profile = () => import('../views/Profile.vue');
const AdminDashboard = () => import('../views/AdminDashboard.vue');

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
        meta: { title: 'Home' }
    },
    {
        path: '/bugs',
        name: 'bugs',
        component: BugList,
        meta: { title: 'Bug Reports' }
    },
    {
        path: '/bugs/:id',
        name: 'bug-detail',
        component: BugDetail,
        meta: { title: 'Bug Detail' }
    },
    {
        path: '/bugs/submit',
        name: 'bug-submit',
        component: BugSubmit,
        meta: {
            title: 'Submit Bug Report',
            requiresAuth: true
        }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: {
            title: 'Login',
            guest: true
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            title: 'Register',
            guest: true
        }
    },
    {
        path: '/profile',
        name: 'profile',
        component: Profile,
        meta: {
            title: 'Profile',
            requiresAuth: true
        }
    },
    {
        path: '/admin',
        name: 'admin',
        component: AdminDashboard,
        meta: {
            title: 'Admin Dashboard',
            requiresAuth: true,
            requiresAdmin: true
        }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

// Navigation guards
router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // Set page title
    document.title = `${to.meta.title || 'Bug Report'} - Devkaki`;

    // Check if route requires authentication
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: 'login', query: { redirect: to.fullPath } });
        return;
    }

    // Check if route is for guests only
    if (to.meta.guest && authStore.isAuthenticated) {
        next({ name: 'home' });
        return;
    }

    // Check if route requires admin
    if (to.meta.requiresAdmin && !authStore.isAdmin) {
        next({ name: 'home' });
        return;
    }

    next();
});

export default router;
