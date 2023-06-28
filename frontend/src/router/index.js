import Vue from 'vue';
import VueRouter from 'vue-router';
import AccountManagement from '../views/admin/AccountManagement.vue';
import adminIndex from '../views/admin/Index.vue';
import userRegistration from '../views/user/registration.vue';
import userIndex from '../views/user/Index.vue';
import deck from '../views/user/deck.vue';
import Login from '../views/Login.vue';

Vue.use(VueRouter);

const routes = [
  // Set login as index
  {
    path: '/',
    redirect: { name: 'Login' } // Redirect to the named route
  },
  // Admin block
  {
    path: '/adminIndex',
    name: 'adminIndex',
    component: adminIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/AccountManagement',
    name: 'AccountManagement',
    component: AccountManagement,
    meta: { requiresAuth: true }
  },
  // User block
  {
    path: '/userIndex',
    name: 'userIndex',
    component: userIndex,
    meta: { requiresAuth: true }
  },
  {
    path: '/deck',
    name: 'deck',
    component: deck,
    meta: { requiresAuth: true }
  },
  {
    path: '/userRegistration',
    name: 'userRegistration',
    component: userRegistration,
  },
  // General
  {
    path: '/Login',
    name: 'Login',
    component: Login
  },
];

const router = new VueRouter({
  mode: 'hash',
  routes
});

router.beforeEach((to, from, next) => {
  const role = localStorage.getItem('role');
  console.log(role);
  // Checking if there is an active account, redirect to login if none
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (role == 'null' || role == null) {
      next({ name: 'Login' });
      return;
    }
  }
  // Checking if the account is already logged in
  else {
    if (role != 'null') {
      if (role == 'admin') {
        next({ name: 'adminIndex' });
        return;
      } else if (role == 'user') {
        next({ name: 'userIndex' });
        return;
      }
    }
  }
  next();
});

export default router;
