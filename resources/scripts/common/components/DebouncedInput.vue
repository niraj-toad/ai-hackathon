<script setup lang="ts">
import {computed, ref} from 'vue';

const props = defineProps<{
    value: string,
    msDelay: number,
}>();

const emit = defineEmits<{
    (event: 'update:value', value: string): void,
}>();

const timeout = ref<ReturnType<typeof setTimeout> | null>(null);

const proxy = computed<string>({
    get() {
        return props.value;
    },
    set(value) {
        if (timeout.value) {
            clearTimeout(timeout.value);
        }
        timeout.value = setTimeout(() => {
            emit('update:value', value);
            timeout.value = null;
        }, props.msDelay);
    },
});
</script>

<template>
    <input v-bind="props" v-model="proxy">
</template>
