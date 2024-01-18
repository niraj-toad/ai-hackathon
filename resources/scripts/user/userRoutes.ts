import {RouteRecordRaw} from 'vue-router';
import {USER_LOGIN,USER_MAIN} from '@/common/constants/userRouteNames';
import requireGuest from '@/common/guards/requireGuest';

const userRoutes: RouteRecordRaw[] = [
    {
        children: [
            {
                component: () => import('@/user/pages/LandingPage.vue'),
                name: USER_MAIN,
                path: '',
            },
            {
                beforeEnter: requireGuest,
                component: () => import('@/user/pages/Login.vue'),
                name: USER_LOGIN,
                path: 'login',
            },
            {
                beforeEnter: requireGuest,
                component: () => import('@/user/pages/ForgotPassword.vue'),
                name: 'password.email',
                path: 'forgot-password',
            },
            {
                beforeEnter: requireGuest,
                component: () => import('@/user/pages/ResetPassword.vue'),
                name: 'password.reset',
                path: 'reset-password',
            },
        ],
        component: () => import('@/user/UserRoot.vue'),
        path: '/',
    },
];

export default userRoutes;
