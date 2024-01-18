import {NavigationGuard} from 'vue-router';
import {USER_LOGIN} from '@/common/constants/userRouteNames';
import useAuthStore from '@/common/stores/auth.store';

const requireAuthentication: NavigationGuard = to => {
    const authStore = useAuthStore();

    if (!authStore.isAuthenticated) {
        authStore.setIntendedRoute(to);

        return {name: USER_LOGIN};
    }
};

export default requireAuthentication;
