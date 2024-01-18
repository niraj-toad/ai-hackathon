<script lang="ts" setup>
import useAuthStore from '@/common/stores/auth.store';
import useMetaStore from '@/common/stores/meta.store';

// Initialize the meta store to allow errors to bubble up to the root.
useMetaStore().initialize();

const authStore = useAuthStore();
</script>

<template>
    <RouterView />
</template>
