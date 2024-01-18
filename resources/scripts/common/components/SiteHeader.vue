<script lang="ts" setup>
import {RouteLocationRaw} from 'vue-router';

defineProps<{
    logoLinkTo: RouteLocationRaw;
}>();
</script>

<template>
    <div class="header-container">
        <header>
            <div class="logo-container">
                <RouterLink :to="logoLinkTo">
                    <img src="@assets/images/logo.svg" :alt="$t('project_name_logo')" class="logo">
                </RouterLink>
            </div>
            <div class="site-nav-container">
                <slot name="site-nav" />
            </div>
            <div class="user-nav-container">
                <slot name="user-nav" />
            </div>
        </header>
    </div>
</template>


<style scoped lang="postcss">
.header-container {
    @apply z-40;
}

header {
    @apply bg-white;
    @apply grid grid-cols-[minmax(0,auto),minmax(auto,1fr),minmax(0,auto)] gap-4 items-center;
    @apply px-4;
    @apply relative;
    @apply shadow;

    @apply md:gap-8;
}

.logo-container {
    @apply col-start-2 row-start-1;
    @apply py-4;

    @apply md:col-start-1;

    & .logo {
        @apply h-8;
    }
}

.site-nav-container {
    @apply col-start-1 row-start-1;
    @apply flex items-center;
    @apply self-stretch;

    @apply md:col-start-2;
}

.user-nav-container {
    @apply col-start-3 row-start-1;
}
</style>
