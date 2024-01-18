<script setup lang="ts">
import {Ref} from 'vue';
import DebouncedInput from '@/common/components/DebouncedInput.vue';

defineProps<{
    filters: {
        name: string,
        value: Ref<string>,
    }[],
}>();
</script>

<template>
    <div class="grid grid-cols-3 gap-2 py-2">
        <div v-for="filter in filters" :key="filter.name">
            <label :for="filter.name" class="capitalize">
                {{ filter.name }}
            </label>
            <DebouncedInput
                type="text"
                class="w-full rounded p-1"
                :name="filter.name"
                :ms-delay="500"
                v-model:value="filter.value.value"
            />
        </div>
    </div>
</template>
