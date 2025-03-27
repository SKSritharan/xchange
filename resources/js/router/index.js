import { createRouter, createWebHistory } from 'vue-router';

import Home from '../pages/Home.vue';
import Dashboard from '../pages/Dashboard.vue';
import DataEntry from '../pages/DataEntry.vue';

const routes = [
    { path: '/', component: Home },
    {path: '/dashboard', component: Dashboard},
    { path: '/data-entry', component: DataEntry },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
