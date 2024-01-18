<script setup lang="ts">
import {faAngleDoubleLeft, faAngleDoubleRight, faAngleLeft, faAngleRight} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {computed} from 'vue';
import {PaginationMeta} from '@/common/parsers/paginationMetaParser';
import range from '@/common/utilities/range';

const props = defineProps<{
    page: number,
    pagination: PaginationMeta|null,
}>();

const emit = defineEmits<{
    (event: 'update:page', value: number): void,
}>();

const totalPages = computed<number>(function () {
    const dividend = props.pagination?.total ?? 0;
    const divisor = props.pagination?.per_page ?? 0;
    return divisor !== 0 ? Math.ceil(dividend / divisor) : 0;
});
const pageNumbers = computed<number[]>(() => range(1, totalPages.value));

const page = computed<number>({
    get() {
        return props.pagination?.current_page ?? 1;
    },
    set(value) {
        emit('update:page', value);
    },
});

const onFirstPage = computed<boolean>(() => page.value === 1);
const onLastPage = computed<boolean>(() => page.value === totalPages.value);

function goToPreviousPage() {
    if (!onFirstPage.value) {
        page.value--;
    }
}

function goToNextPage() {
    if (!onLastPage.value) {
        page.value++;
    }
}

function goToFirstPage() {
    page.value = 1;
}

function goToLastPage() {
    page.value = totalPages.value;
}
</script>

<template>
    <div v-if="pagination && pagination.last_page > 1" class="container">
        <div>
            <button
                :disabled="onFirstPage"
                @click="goToFirstPage"
            >
                <FontAwesomeIcon :icon="faAngleDoubleLeft" />
            </button>
            <button
                :disabled="onFirstPage"
                @click="goToPreviousPage"
            >
                <FontAwesomeIcon :icon="faAngleLeft" />
            </button>
            <select v-model="page">
                <option
                    v-for="page in pageNumbers"
                    :key="page"
                    :value="page"
                >{{ page }}</option>
            </select>
            <button
                :disabled="onLastPage"
                @click="goToNextPage"
            >
                <FontAwesomeIcon :icon="faAngleRight" />
            </button>
            <button
                :disabled="onLastPage"
                @click="goToLastPage"
            >
                <FontAwesomeIcon :icon="faAngleDoubleRight" />
            </button>
        </div>
    </div>
</template>

<style scoped>
.container {
    @apply flex items-center justify-center;
    > div {
        @apply grid grid-cols-5 gap-1;
    }
}
select {
    @apply bg-primary-0;
    @apply p-1;
    @apply border border-secondary-300;
    @apply rounded-md shadow-sm;
    @apply text-sm;
}
button {
    @apply bg-primary-0;
    @apply p-1;
    @apply border border-secondary-300;
    @apply rounded-md shadow-sm;
    @apply text-sm;
}
</style>
