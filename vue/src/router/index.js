import Vue from 'vue';
import VueRouter from 'vue-router';
import Inicio from '../views/Inicio.vue';
import NotFound from '../views/NotFound.vue';

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Inicio',
    component: Inicio
  },
  {
    path: '*',
    name: 'Not Found',
    component: NotFound
  }
];

const router = new VueRouter({
  routes
});

export default router;