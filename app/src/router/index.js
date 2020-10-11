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
