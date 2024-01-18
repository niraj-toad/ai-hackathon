<script setup lang="ts">
import ErrorBoundary from '@/common/components/ErrorBoundary.vue';

type ErrorFilter = boolean | ((error: Error) => boolean);

withDefaults(defineProps<{
    captureErrorFilter?: ErrorFilter,
    reportErrorFilter?: ErrorFilter,
}>(), {
    captureErrorFilter: true,
    reportErrorFilter: true,
});
</script>

<template>
    <ErrorBoundary
        :capture-error-filter="captureErrorFilter"
        :report-error-filter="reportErrorFilter"
    >
        <template #error="{error}">
            <slot name="error" :error="error" />
        </template>
        <template #default>
            <Suspense>
                <template #default>
                    <slot />
                </template>
                <template #fallback>
                    <slot name="fallback" />
                </template>
            </Suspense>
        </template>
    </ErrorBoundary>
</template>
