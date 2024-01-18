<script lang="ts" setup>
import NavigationItem from '@/common/components/NavigationItem.vue';
import SiteHeader from '@/common/components/SiteHeader.vue';
import SiteNavigation from '@/common/components/SiteNavigation.vue';
import UserNavigation from '@/common/components/UserNavigation.vue';
import usePermissionGuard from '@/common/composables/usePermissionGuard';
import {STAFF_MAIN} from '@/common/constants/staffRouteNames';
import Permission from '@/common/enums/Permission';

usePermissionGuard([Permission.AccessStaffPanel]);
</script>

<template>
    <SiteHeader :logo-link-to="{name: STAFF_MAIN}">
        <template #site-nav>
            <SiteNavigation>
                <!-- Remove the "Home" link from the menu once another link is added -->
                <NavigationItem exact :to="{name: STAFF_MAIN}">
                    Home
                </NavigationItem>
            </SiteNavigation>
        </template>
        <template #user-nav>
            <UserNavigation />
        </template>
    </SiteHeader>
    <RouterView />
</template>
