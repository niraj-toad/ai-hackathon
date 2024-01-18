<script lang="ts" setup>
import {useField} from 'vee-validate';

interface Props {
    label: string;
    name: string;
    placeholder?: string;
    type?: 'email' | 'password' | 'text';
}
const props = withDefaults(defineProps<Props>(), {
    type: 'text',
});

const {errorMessage, value} = useField(props.name);
</script>

<template>
    <div :class="['field-container', { error: !!errorMessage }]">
        <label :for="name">{{ label }}</label>
        <div class="field">
            <input
                :id="name"
                :name="name"
                :placeholder="placeholder"
                :type="type"
                v-model="value"
            >
        </div>
        <div v-if="errorMessage" class="message">{{ errorMessage }}</div>
    </div>
</template>

<style>
.field-container {
    @apply flex flex-col gap-1 items-start;

    label {
        @apply leading-none font-normal text-base;
    }

    .field {
        @apply flex flex-col gap-2 self-stretch;
        @apply rounded-md;

        input {
            @apply bg-primary-0;
            @apply border-0;
            @apply p-4 rounded-md;

            @apply hover:ring-2 hover:ring-accent-800/50;
            @apply focus:ring-4;

            &::placeholder {
                @apply italic text-primary-500;
            }
        }
    }

    .message {
        @apply text-sm;
    }

    &.error {
        label {
            @apply text-red-600;
        }

        input {
            @apply hover:ring-red-600/50;
            @apply ring-red-600/50;
        }

        .message {
            @apply text-red-600;
        }
    }
}
</style>
