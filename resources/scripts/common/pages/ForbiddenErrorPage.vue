<script lang="ts" setup>
import {computed,ref} from 'vue';
import Permission from '@/common/enums/Permission';
import useAuthStore from '@/common/stores/auth.store';
import useMetaStore from '@/common/stores/meta.store';

interface Props {
    permissions: Permission[];
}
const props = defineProps<Props>();

const authStore = useAuthStore();
const metaStore = useMetaStore();
const user = ref(authStore.user);

const permissionList = computed(() => props.permissions.map(permission => ({
    has: user.value?.permissions.includes(permission),
    name: permission,
})));
</script>

<template>
    <main id="forbidden">
        <h1>403 Forbidden</h1>
        <p>You do not have the necessary permissions.</p>
        <div class="permissions-list" v-if="metaStore.debug">
            <div
                :class="['permission', { lacking: !permission.has }]"
                v-for="permission in permissionList"
                :key="permission.name"
            >
                {{ permission.name }}
                <span class="indicator" v-if="permission.has">You have this permission.</span>
                <span class="indicator" v-else>You lack this permission.</span>
            </div>
        </div>
    </main>
</template>

<style>
#forbidden {
    @apply min-h-full;
    @apply flex flex-col gap-4 items-center justify-center;
    @apply py-8;

    h1 {
        @apply text-3xl text-red-600;
    }

    p {
        @apply text-lg;
    }

    .permissions-list {
        @apply flex flex-col gap-4 container max-w-md;
        .permission {
            @apply bg-secondary-100;
            @apply flex flex-1 flex-col gap-1;
            @apply px-6 py-3;
            @apply rounded-lg;
            @apply font-bold leading-none text-primary-900 text-lg;

            .indicator {
                @apply font-normal leading-none text-green-600 text-sm;
            }

            &.lacking {
                .indicator {
                    @apply text-red-600;
                }
            }
        }
    }
}
</style>
