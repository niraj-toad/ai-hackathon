<script lang="ts" setup generic="RowData extends Record<string, unknown>">
import {faCaretDown, faCaretUp, faSpinner} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {trimStart} from 'lodash';
import {computed, Ref, toRef} from 'vue';

export type Field = {
    name: string;
    label: string;
    align?: 'left' | 'center' | 'right';
    sortBy?: string | string[];
};

export type Sorts = {
    [key: string]: 'asc' | 'desc',
};

const props = withDefaults(defineProps<{
    fields: Field[],
    rows: RowData[],
    sort?: string,
    rowKey: keyof RowData,
    loading?: boolean,
}>(), {
    sort: undefined,
});

const rowKey: Ref<keyof RowData> = toRef(props, 'rowKey');

const emit = defineEmits<{
    'update:sort': [string|undefined],
}>();

const textAlignClassMap = {
    left: 'text-left',
    center: 'text-center',
    right: 'text-right',
    start: 'text-start',
    end: 'text-end',
};

const sorts = computed<Sorts>(
    () => props.sort?.split(',').reduce<Sorts>((acc, sort) => {
        const name = trimStart(sort, '-');
        acc[name] = sort.startsWith('-') ? 'desc' : 'asc';
        return acc;
    }, {}) ?? {},
);

function updateSort(field: Field) {
    if (!field.sortBy) {
        return;
    }

    const newSorts = {
        ...sorts.value,
    };

    if (Array.isArray(field.sortBy)) {
        field.sortBy.forEach(name => {
            const newDirection = nextSortDirection(field);
            if (newDirection) {
                newSorts[name] = newDirection;
            } else {
                delete newSorts[name];
            }
        });
    } else {
        const newDirection = nextSortDirection(field);
        if (newDirection) {
            newSorts[field.sortBy] = newDirection;
        } else {
            delete newSorts[field.sortBy];
        }
    }

    emit('update:sort', serializeSorts(newSorts));
}

function nextSortDirection(field: Field) {
    switch (getSortDirection(field)) {
        case undefined:
            return 'asc';
        case 'asc':
            return 'desc';
        case 'desc':
            return undefined;
    }
}

function serializeSorts(sorts: Sorts) {
    const entries = Object.entries(sorts);
    if (!entries.length) {
        return undefined;
    }
    return entries.map(([name, direction]) => {
        if (direction === 'asc') {
            return name;
        }

        return `-${name}`;
    }).join(',');
}

function getSortDirection(field: Field) {
    if (!field.sortBy) {
        return;
    }

    const key = Array.isArray(field.sortBy) ? field.sortBy[0] : field.sortBy;
    return sorts.value[key];
}
</script>

<template>
    <table>
        <thead>
            <tr>
                <th
                    :class="[
                        textAlignClassMap[field.align ?? 'center'],
                        {'cursor-pointer': !!field.sortBy},
                    ]"
                    v-for="field in fields" :key="field.name"
                    @click="updateSort(field)"
                >
                    {{ field.label }}
                    <span v-if="getSortDirection(field)">
                        <span v-if="getSortDirection(field) === 'asc'">
                            <FontAwesomeIcon :icon="faCaretUp" />
                        </span>
                        <span v-else-if="getSortDirection(field) === 'desc'">
                            <FontAwesomeIcon :icon="faCaretDown" />
                        </span>
                    </span>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="row in rows" :key="`${row[rowKey]}`">
                <td
                    :class="[textAlignClassMap[field.align ?? 'center']]"
                    v-for="field in fields" :key="field.name"
                >
                    <slot :name="field.name" :row="row" :field="field">
                        <slot name="default" :row="row" :field="field">
                            {{ row[field.name] }}
                        </slot>
                    </slot>
                </td>
            </tr>
        </tbody>
    </table>
    <div v-if="loading" class="bg-primary p-2 text-center">
        <FontAwesomeIcon :icon="faSpinner" spin />
    </div>
</template>

<style lang="postcss">
table {
    @apply w-full;
    @apply bg-primary-0;
    @apply rounded;
    @apply divide-y divide-secondary-200;

    & > thead {
        > tr > th {
            @apply bg-accent-800 text-primary-100;
            @apply p-2;
            @apply font-bold;
            &:first-child {
                @apply rounded-tl;
            }
            &:last-child {
                @apply rounded-tr;
            }
        }
    }

    & > tbody {
        @apply divide-y divide-secondary-200;
        > tr > td {
            @apply p-2;
        }
    }
}
</style>
