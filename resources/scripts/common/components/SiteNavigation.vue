<script setup lang="ts">
import {breakpointsTailwind, useBreakpoints} from '@vueuse/core';
import NavigationMenu from '@/common/components/NavigationMenu.vue';

const breakpoints = useBreakpoints(breakpointsTailwind);
const isCollapsed = breakpoints.smaller('md');
</script>

<template>
    <NavigationMenu :collapsed="isCollapsed">
        <slot />
    </NavigationMenu>
</template>

<style scoped lang="postcss">
.menu:has(nav:not(.collapsed)) {
    @apply h-full;
}

.menu > :deep(nav.collapsed) {
    @apply sm:right-auto sm:rounded-br-xl;
}
</style>
