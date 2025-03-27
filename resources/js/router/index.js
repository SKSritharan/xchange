import {createRouter, createWebHistory} from 'vue-router';

import Home from '../pages/Home.vue';
import Dashboard from '../pages/Dashboard.vue';
import DataEntry from '../pages/DataEntry.vue';
import Login from "../pages/Login.vue";
import Register from "../pages/Register.vue";

const routes = [
    {path: '/', component: Home},
    {path: '/login', component: Login},
    {path: '/register', component: Register},
    {path: '/dashboard', component: Dashboard},
    {path: '/data-entry', component: DataEntry},
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
