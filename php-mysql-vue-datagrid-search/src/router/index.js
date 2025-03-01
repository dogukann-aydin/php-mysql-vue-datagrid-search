import { createRouter, createWebHistory } from 'vue-router';
import UrunListesi from '@/components/UrunListesi.vue';

const routes = [
  {
    path: '/',
    component: UrunListesi, // Ana sayfa
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
