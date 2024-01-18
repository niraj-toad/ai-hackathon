<script lang="ts" setup>
import App from '@/App.vue';
import SuspensefulErrorBoundary from '@/common/components/SuspensefulErrorBoundary.vue';
import ForbiddenError from '@/common/errors/ForbiddenError';
import ForbiddenErrorPage from '@/common/pages/ForbiddenErrorPage.vue';
import GenericErrorPage from '@/common/pages/GenericErrorPage.vue';
import {isErrorClass} from '@/common/utilities/isError';
</script>

<template>
    <SuspensefulErrorBoundary>
        <App />
        <template #error="{error}">
            <ForbiddenErrorPage
                :permissions="error.permissions"
                v-if="isErrorClass(error, ForbiddenError)"
            />
            <GenericErrorPage
                :error="error"
                v-else
            />
        </template>
    </SuspensefulErrorBoundary>
</template>

<style>
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
    html {
        font-family: sans-serif;
    }

    html, body, #app {
        @apply bg-primary-200;
        @apply h-full;
    }

    #app {
        @apply flex flex-col;
    }

    .error {
        @apply h-full;
        @apply flex flex-col gap-4 items-center justify-center;

        h1 {
            @apply text-3xl text-red-600;
        }

        p {
            @apply text-lg;
        }
    }

    a, button, input, textarea {
        @apply outline-0;

        @apply focus-visible:ring-4 focus-visible:ring-accent-800/50;
    }
}
</style>
