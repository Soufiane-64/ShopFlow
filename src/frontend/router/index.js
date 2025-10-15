import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
    meta: { guest: true }
  },
  {
    path: '/',
    component: () => import('../layouts/MainLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: () => import('../views/Dashboard.vue')
      },
      {
        path: '/projects',
        name: 'Projects',
        component: () => import('../views/Projects.vue')
      },
      {
        path: '/projects/:id',
        name: 'ProjectDetail',
        component: () => import('../views/ProjectDetail.vue')
      },
      {
        path: '/releases',
        name: 'Releases',
        component: () => import('../views/Releases.vue')
      },
      {
        path: '/qa',
        name: 'QA',
        component: () => import('../views/QA.vue')
      },
      {
        path: '/client-portal',
        name: 'ClientPortal',
        component: () => import('../views/ClientPortal.vue')
      }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()
  
  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
  } else if (to.meta.guest && authStore.isAuthenticated) {
    next('/')
  } else {
    next()
  }
})

export default router
