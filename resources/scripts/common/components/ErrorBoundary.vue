<script setup lang="ts">
import {captureException} from '@sentry/vue';
import {onErrorCaptured, ref, watch} from 'vue';
import {useRoute} from 'vue-router';

export type ErrorFilter = boolean | ((error: Error) => boolean);

const props = withDefaults(defineProps<{
    captureErrorFilter?: ErrorFilter,
    keepDefaultSlotOnError?: boolean,
    reportErrorFilter?: ErrorFilter,
}>(), {
    captureErrorFilter: true,
    keepDefaultSlotOnError: false,
    reportErrorFilter: true,
});

const route = useRoute();

const capturedError = ref<Error>();

function clear() {
    capturedError.value = undefined;
}

function should(error: Error, filter: ErrorFilter): boolean {
    if (typeof filter === 'boolean') {
        return filter;
    }

    return filter(error);
}

onErrorCaptured(error => {
    if (should(error, props.captureErrorFilter)) {
        console.error(error);
        capturedError.value = error;

        if (should(error, props.reportErrorFilter)) {
            captureException(error);
        }

        return false;
    }

    return true;
});

watch(route, () => {
    capturedError.value = undefined;
});
</script>

<template>
    <slot v-if="capturedError" name="error" :clear="clear" :error="capturedError" />
    <slot v-if="!capturedError || keepDefaultSlotOnError" :clear="clear" />
</template>
