import {MaybeRef} from '@vueuse/core';
import {storeToRefs} from 'pinia';
import {computed, unref} from 'vue';
import Permission from '@/common/enums/Permission';
import useAuthStore from '@/common/stores/auth.store';

export default function useHasPermission(expectedPermission: MaybeRef<Permission>) {
    const authStore = useAuthStore();
    const {permissions} = storeToRefs(authStore);

    return computed(() => !!permissions.value.includes(unref(expectedPermission)));
}
