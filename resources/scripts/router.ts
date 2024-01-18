import {createRouter, createWebHistory} from 'vue-router';
import commonRoutes from '@/common/commonRoutes';
import useAuthStore from '@/common/stores/auth.store';
import staffRoutes from '@/staff/staffRoutes';
import userRoutes from '@/user/userRoutes';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        ...commonRoutes,
        ...staffRoutes,
        ...userRoutes,
    ],
});

router.beforeEach(async () => {
    const authStore = useAuthStore();

    await authStore.initialize();
});

export default router;
