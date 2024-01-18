<script setup lang="ts">
import {MenuItem} from '@headlessui/vue';
import {RouteLocationRaw} from 'vue-router';

withDefaults(defineProps<{
    exact?: boolean;
    to: RouteLocationRaw;
}>(), {
    exact: false,
});

function onClick(navigate: () => void, close: () => void): void {
    navigate();
    close();
}
</script>

<template>
    <MenuItem as="template" v-slot="{close}">
        <RouterLink custom :to="to" v-slot="{href, isActive, isExactActive, navigate}">
            <a
                @click.prevent="onClick(navigate, close)"
                :class="{active: (isActive && !exact) || (isExactActive && exact)}"
                :href="href"
                v-bind="$attrs"
            >
                <slot />
            </a>
        </RouterLink>
    </MenuItem>
</template>
