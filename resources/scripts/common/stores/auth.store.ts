import {defineStore} from 'pinia';
import {computed, ref} from 'vue';
import {RouteLocationNormalized} from 'vue-router';
import useClient from '@/common/api/client';
import {authRoute} from '@/common/api/routes';
import {STAFF_MAIN} from '@/common/constants/staffRouteNames';
import {USER_MAIN} from '@/common/constants/userRouteNames';
import Permission from '@/common/enums/Permission';
import unwrap from '@/common/parsers/unwrap';
import {AuthenticatedUser, authenticatedUserParser} from '@/common/parsers/userParser';
import {isError} from '@/common/utilities/isError';

const useAuthStore = defineStore('auth', () => {
    const checkController = ref<AbortController>();
    const initialized = ref(false);
    const intendedRoute = ref<RouteLocationNormalized>();
    const user = ref<AuthenticatedUser>();
    const isAuthenticated = computed(() => !!user.value);
    const mainRoute = computed(() => {
        if (user.value?.permissions.includes(Permission.AccessStaffPanel)) {
            return {name: STAFF_MAIN};
        }

        return {name: USER_MAIN};
    });

    const permissions = computed(() => user.value?.permissions ?? []);

    function setUser(newUser?: AuthenticatedUser) {
        user.value = newUser;
    }

    async function check() {
        checkController.value?.abort();
        checkController.value = new AbortController();

        try {
            const client = useClient();
            const payload = await client.get(authRoute()).json();
            const currentUser = unwrap(authenticatedUserParser).parse(payload);

            setUser(currentUser);
        } catch (err) {
            if (!isError(err, 'AbortError')) {
                setUser(undefined);
            }
        }
    }

    async function initialize() {
        if (initialized.value) {
            return;
        }

        await check();

        initialized.value = true;
    }

    return {
        check,
        initialize,
        intendedRoute,
        isAuthenticated,
        mainRoute,
        permissions,
        setIntendedRoute: (route?: RouteLocationNormalized) => { intendedRoute.value = route; },
        setUser,
        user,
    };
});

export default useAuthStore;
