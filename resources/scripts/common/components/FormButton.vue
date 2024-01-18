<script lang="ts" setup>
import {faSpinner} from '@fortawesome/free-solid-svg-icons/faSpinner';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
defineProps<{
    disabled?: boolean;
    label: string;
    loading?: boolean;
    type: 'button' | 'submit' | 'reset';
}>();
</script>

<template>
    <button
        :class="{loading}"
        :disabled="disabled || loading"
        :type="type"
    >
        <span v-if="loading" class="loader">
            <FontAwesomeIcon v-if="loading" :icon="faSpinner" class="animate-spin" />
        </span>
        {{ label }}
    </button>
</template>

<style scoped>
button {
    @apply bg-accent-800 text-primary-100 font-bold;
    @apply rounded-md px-8;
    @apply p-4;
    @apply uppercase;
    @apply outline-none;
    @apply hover:ring-2 hover:ring-accent-800/50 focus-visible:ring-4 focus-visible:ring-accent-800/50;
    @apply disabled:bg-primary-400 disabled:pointer-events-none;
    @apply relative;
    .loader {
        @apply absolute;
        @apply top-1/2 left-1/2;
        @apply transform -translate-x-1/2 -translate-y-1/2;
    }
    &.loading {
        @apply text-transparent;
        svg {
            @apply text-primary-100;
        }
    }
}
</style>
