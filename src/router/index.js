import { createRouter, createWebHashHistory } from "vue-router";

const routes = [
  {
    path: "/",
    name: "login",
    component: () => import("@/views/LoginView.vue"),
  },
  {
    path: "/home",
    name: "home",
    component: () => import("@/views/HomeView.vue"),
  },
  {
    path: "/:pathMatch(.*)*",
    name: "view404",
    component: () => import("@/views/View404.vue"),
  },
  // {
  //   path: "/cat/:tagname",
  //   name: "article",
  //   component: () => import("../views/CategoryView.vue"),
  // },
  // ,
  // {
  //   path: '/article/:article_id',../views/404View.vue
  //   name: 'article',
  //   component: () => import('../views/AnArticleView.vue')
  // }
];

const router = createRouter({
  history: createWebHashHistory(process.env.BASE_URL),
  routes,
});

export default router;
