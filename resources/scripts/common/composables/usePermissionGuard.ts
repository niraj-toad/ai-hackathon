import {MaybeRef} from '@vueuse/core';
import {unref} from 'vue';
import useHasAllPermissions from '@/common/composables/useHasAllPermissions';
import Permission from '@/common/enums/Permission';
import ForbiddenError from '@/common/errors/ForbiddenError';

export default function usePermissionGuard(permissions: MaybeRef<Permission[]>) {
    const hasAllPermissions = useHasAllPermissions(permissions);

    if (!hasAllPermissions.value) {
        throw new ForbiddenError(unref(permissions));
    }
}
