<script lang="ts" setup>
import NavigationItem from '@/common/components/NavigationItem.vue';
import SiteHeader from '@/common/components/SiteHeader.vue';
import SiteNavigation from '@/common/components/SiteNavigation.vue';
import UserNavigation from '@/common/components/UserNavigation.vue';
import useHasPermission from '@/common/composables/useHasPermission';
import {STAFF_MAIN} from '@/common/constants/staffRouteNames';
import {USER_MAIN} from '@/common/constants/userRouteNames';
import Permission from '@/common/enums/Permission';

const hasAccessStaffPanelPermission = useHasPermission(Permission.AccessStaffPanel);
</script>

<template>
    <SiteHeader :logo-link-to="{name: USER_MAIN}">
        <template #site-nav>
            <SiteNavigation>
                <!-- Remove the "Home" link from the menu once another link is added -->
                <NavigationItem :to="{name: USER_MAIN}">
                    Home
                </NavigationItem>
            </SiteNavigation>
        </template>
        <template #user-nav>
            <UserNavigation>
                <NavigationItem v-if="hasAccessStaffPanelPermission" :to="{name: STAFF_MAIN}">
                    {{ $t('go_to_staff_panel') }}
                </NavigationItem>
            </UserNavigation>
        </template>
    </SiteHeader>
    <RouterView />
</template>
