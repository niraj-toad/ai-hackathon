import {RouteRecordRaw} from 'vue-router';
import {NOT_FOUND} from '@/common/constants/commonRouteNames';

const commonRoutes: RouteRecordRaw[] = [
    {
        path: '/:pathMatch(.*)*',
        name: NOT_FOUND,
        component: () => import('@/common/pages/NotFoundErrorPage.vue'),
    },
];

export default commonRoutes;
