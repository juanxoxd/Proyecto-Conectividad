import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Inicio",
    component: () => import("../views/Home.vue"),
  },
  {
    path: "/empresa",
    name: "Empresa",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import("../views/Empresa.vue"),
  },
  {
    path: '/mediopago',
    name: 'MedioPago',
    component: () => import("../views/MedioPago.vue"),
  },
  {
    path: '/cuenta_bancaria',
    name: 'Cuenta Bancaria',
    component: () => import("../views/CuentaBancaria.vue"),
  },
  {
    path: "/usuario",
    name: "Usuario",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import("../views/Usuario.vue"),
  },
  {
    path: "/personal",
    name: "Personal",
    component: () => import("../views/Personal.vue"),
  },
  {
    path: "/documentopersonal",
    name: "DocumentoPersonal",
    component: () => import("../views/DocumentoPersonal.vue"),
  },
  {
    path: "/sistemadepensiones",
    name: "SistemaDePensiones",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import("../views/SistemaDePensiones.vue"),
  },
  {
    path: "/entidadBancaria",
    name: "EntidadBancaria",
    component: () => import("../views/EntidadBancaria.vue"),
  },


];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes,
});

export default router;
