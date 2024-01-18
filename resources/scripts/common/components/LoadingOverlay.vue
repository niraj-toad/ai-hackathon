<script setup lang="ts">
import {faSpinner} from '@fortawesome/free-solid-svg-icons/faSpinner';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
</script>

<template>
    <div class="overlay">
        <FontAwesomeIcon :icon="faSpinner" spin />
    </div>
</template>

<style scoped>
.overlay {
    @apply fixed inset-0;
    @apply flex items-center justify-center;
    @apply bg-primary-400 bg-opacity-75;
}
</style>
