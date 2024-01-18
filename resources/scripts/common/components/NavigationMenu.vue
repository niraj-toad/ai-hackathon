<script setup lang="ts">
import {faBars, faTimes} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {Menu, MenuButton, MenuItems} from '@headlessui/vue';

withDefaults(defineProps<{
    collapsed?: boolean;
}>(), {
    collapsed: true,
});
</script>

<template>
    <Menu as="div" class="menu" v-slot="{open}">
        <template v-if="collapsed">
            <MenuButton v-if="!open">
                <slot name="openButton">
                    <FontAwesomeIcon :icon="faBars" />
                    <span class="sr-only">{{ $t('open_menu') }}</span>
                </slot>
            </MenuButton>
            <MenuButton v-else>
                <slot name="closeButton">
                    <FontAwesomeIcon :icon="faTimes" />
                    <span class="sr-only">{{ $t('close_menu') }}</span>
                </slot>
            </MenuButton>
        </template>
        <Transition
            enter-active-class="enter-active"
            enter-from-class="enter-from"
            enter-to-class="enter-to"
            leave-active-class="leave-active"
            leave-from-class="leave-from"
            leave-to-class="leave-to"
        >
            <MenuItems as="nav" :class="{collapsed}" :static="!collapsed">
                <slot />
            </MenuItems>
        </Transition>
    </Menu>
</template>

<style scoped lang="postcss">
.menu {
    @apply flex items-center;
}

.menu > button {
    @apply aspect-square h-8;
    @apply bg-primary-100;
    @apply flex items-center justify-center;
    @apply rounded-md;
}

nav.collapsed {
    @apply absolute inset-x-0 top-full translate-y-0 -z-10;
    @apply bg-primary-100;
    @apply flex flex-col gap-2;
    @apply p-4;
    @apply shadow;

    @apply sm:max-w-sm sm:w-full;

    &.enter-active {
        @apply motion-safe:transition-transform motion-safe:duration-300 motion-safe:ease-in;
    }
    &.enter-from {
        @apply -translate-y-full;
    }
    &.enter-to {
        @apply translate-y-0;
    }

    &.leave-active {
        @apply motion-safe:transition-transform motion-safe:duration-150 motion-safe:ease-out;
    }
    &.leave-from {
        @apply translate-y-0;
    }
    &.leave-to {
        @apply -translate-y-full;
    }

    & > :deep(a) {
        @apply font-bold leading-none text-sm uppercase;
        @apply p-4;
        @apply rounded-md;

        @apply hover:bg-primary-50 hover:text-accent-800;

        &.active {
            @apply bg-accent-800;
            @apply text-primary-100;

            @apply hover:bg-accent-900;
        }
    }
}

nav:not(.collapsed) {
    @apply flex gap-2 items-stretch;
    @apply h-full;

    & > :deep(a) {
        @apply flex items-center justify-center;
        @apply border-y-2 border-transparent;
        @apply px-4;
        @apply font-medium leading-none text-primary-500 text-sm;

        @apply hover:border-b-primary-200 hover:text-primary-900;

        &.active {
            @apply border-b-accent-800;
            @apply text-primary-900;
        }
    }
}
</style>
