export const routes = [
  {
    name: 'Dashboard',
    meta: {
      auth: true
    },
    path: '/',
    component: () => import("@/views/DashboardView.vue")
  },
  {
    name: 'Login',
    path: '/login',
    meta: {
      auth: false
    },
    component: () => import("@/views/LoginView.vue")
  },
  {
    name: 'AuthCallback',
    path: '/auth/callback',
    component: () => import('@/views/AuthCallback.vue')
  },
  {
    name: 'DocumentsToSign',
    path: '/documents',
    meta: {
      auth: true
    },
    component: () => import('@/views/DocumentsForSignatureView.vue')
  },
  {
    name: 'NotFound',
    path: '/:pathMatch(.*)*',
    component: () => import('@/views/NotFoundView.vue')
  }
];
