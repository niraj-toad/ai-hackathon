import {RouteRecordRaw} from 'vue-router';
import {STAFF_MAIN} from '@/common/constants/staffRouteNames';
import requireAuthentication from '@/common/guards/requireAuthentication';

const staffRoutes: RouteRecordRaw[] = [
    {
        beforeEnter: [
            requireAuthentication,
        ],
        children: [
            {
                component: () => import('@/staff/pages/Main.vue'),
                name: STAFF_MAIN,
                path: '',
            },
        ],
        component: () => import('@/staff/StaffRoot.vue'),
        path: '/staff',
    },
];

export default staffRoutes;
