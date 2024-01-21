
import { createRouter, createWebHistory } from 'vue-router';

import Login from '../pages/user/Login.vue';
import Register from '../pages/user/Register.vue';
import Home from '../pages/auth/Home.vue';
import homeadmin from '../pages/Homeadmin.vue';
import authlayout from '../layouts/auth-layout.vue';
import adminlayout from '../layouts/admin-layout.vue';


const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/Login',
      component: Login
    },
    

    {
      path: '/Register',
      component: Register
    },

    {
      path: '/dashboard',
      component: adminlayout,
      
      children: [
        {
          path:'Homeadmin',
          component: homeadmin
        }
  
      ]
    },
    {
      path: '/auth',
      component: authlayout,
      children: [
        {
          path:'Home',
          component: Home
        }
      
  
      ]
    },
  
  ]


});


export default router;




