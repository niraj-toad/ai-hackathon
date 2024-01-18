<script setup lang="ts">
import {faSignOut, faTimes, faUser} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {storeToRefs} from 'pinia';
import {computed} from 'vue';
import {useRouter} from 'vue-router';
import NavigationItem from '@/common/components/NavigationItem.vue';
import NavigationMenu from '@/common/components/NavigationMenu.vue';
import useLogoutMutation from '@/common/composables/mutations/useLogoutMutation';
import useUnexpectedErrorHandler from '@/common/composables/useUnexpectedErrorHandler';
import {USER_LOGIN} from '@/common/constants/userRouteNames';
import Role from '@/common/enums/Role';
import useAuthStore from '@/common/stores/auth.store';
import displayRole from '@/common/utilities/displayRole';

const {mutateAsync: logout} = useLogoutMutation();
const router = useRouter();
const authStore = useAuthStore();
const {isAuthenticated, user} = storeToRefs(authStore);
const name = computed(() => `${user.value?.first_name} ${user.value?.last_name}`);
const email = computed(() => user.value?.email);
const role = computed(() => user.value?.role);
const handleUnexpectedError = useUnexpectedErrorHandler();

async function onLogout() {
    try {
        await logout();
    } catch (err) {
        handleUnexpectedError(err);
    }

    await router.push({name: USER_LOGIN});
}
</script>

<template>
    <NavigationMenu v-if="isAuthenticated" class="user">
        <template #openButton>
            <FontAwesomeIcon :icon="faUser" />
            <span class="sr-only">{{$t('open_user_menu')}}</span>
        </template>
        <template #closeButton>
            <FontAwesomeIcon :icon="faTimes" />
            <span class="sr-only">{{$t('close_user_menu')}}</span>
        </template>
        <slot />
        <div class="authenticated-as">
            <div class="info">
                <span class="name">{{ name }}</span>
                <span v-if="role === Role.User" class="email">{{ email }}</span>
                <span v-else class="role">{{ displayRole(role) }}</span>
            </div>
            <button @click="onLogout">
                <span>{{ $t('logout') }}</span>
                <FontAwesomeIcon :icon="faSignOut" />
            </button>
        </div>
    </NavigationMenu>
    <NavigationMenu v-else class="user">
        <template #openButton>
            <FontAwesomeIcon :icon="faUser" />
        </template>
        <NavigationItem :to="{name: USER_LOGIN}">{{ $t('login') }}</NavigationItem>
    </NavigationMenu>
</template>

<style scoped lang="postcss">
.menu > :deep(nav.collapsed) {
    @apply sm:left-auto sm:rounded-bl-xl;
}

.authenticated-as {
    @apply bg-primary-200;
    @apply border border-primary-300 rounded-md;
    @apply flex gap-2 items-center justify-between;
    @apply p-4;

    & > .info {
        @apply flex flex-col gap-2;
        & > .name {
            @apply leading-none;
        }
        & > .email,
        & > .role {
            @apply leading-none text-primary-500 text-sm;
        }
    }

    & > button {
        @apply flex gap-2 items-center;
        @apply px-4 py-2;
        @apply rounded-md;
        @apply leading-none text-primary-500;

        @apply hover:bg-primary-50 hover:text-accent-800;

        & > span {
            @apply font-bold text-sm uppercase;
        }
    }
}
</style>
