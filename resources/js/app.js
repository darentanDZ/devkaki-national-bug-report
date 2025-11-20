import './bootstrap';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import Header from './components/Header.vue';

const app = createApp({
    components: {
        Header
    }
});

app.use(createPinia());
app.use(router);

app.mount('#app');
