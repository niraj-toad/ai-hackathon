<script lang="ts" setup>
interface Props {
    error: Error;
}
defineProps<Props>();

const devMode = import.meta.env.DEV;
</script>

<template>
    <main id="forbidden">
        <h1>Something went wrong.</h1>
        <p v-if="devMode">{{ error.message }}</p>
    </main>
</template>

<style>
#forbidden {
    @apply min-h-full;
    @apply flex flex-col gap-4 items-center justify-center;
    @apply py-8;

    h1 {
        @apply text-3xl text-red-600;
    }

    p {
        @apply text-lg;
    }

    .permissions-list {
        @apply flex flex-col gap-4 container max-w-md;
        .permission {
            @apply bg-secondary-100;
            @apply flex flex-1 flex-col gap-1;
            @apply px-6 py-3;
            @apply rounded-lg;
            @apply font-bold leading-none text-primary-900 text-lg;

            .indicator {
                @apply font-normal leading-none text-green-600 text-sm;
            }

            &.lacking {
                .indicator {
                    @apply text-red-600;
                }
            }
        }
    }
}
</style>
