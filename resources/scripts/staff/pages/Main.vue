<script setup lang="ts">
import {computed} from 'vue';
import DataTable, {Field} from '@/common/components/DataTable.vue';
import FilterInputs from '@/common/components/FilterInputs.vue';
import Paginator from '@/common/components/Paginator.vue';
import useUserListQuery from '@/common/composables/queries/useUserListQuery';
import useListControls from '@/common/composables/useListControls';

const fields: Field[] = [
    {name: 'name', label: 'Name', sortBy: ['first_name', 'last_name'], align: 'left'},
    {name: 'email', label: 'Email', sortBy: 'email'},
    {name: 'role', label: 'Role'},
];
const {filters, sort, params, page} = useListControls(['name', 'email']);
const {isLoading, data: response} = useUserListQuery(params);
const rows = computed(() => response.value?.data ?? []);
const paginationMeta = computed(() => response.value?.meta ?? null);
</script>

<template>
    <div class="w-full h-full flex flex-col justify-start items-center p-8">
        <h1>Staff Section</h1>
        <div class="md:w-2/3 flex flex-col items-center">
            <div class="w-full">
                <h2 class="text-2xl text-bold border-b border-b-secondary-300">Users</h2>
            </div>
            <div class="w-full">
                <FilterInputs :filters="filters" />
                <DataTable :fields="fields" :rows="rows" row-key="id" :loading="isLoading" v-model:sort="sort">
                    <template #name="{row}">{{ row.first_name }} {{ row.last_name }}</template>
                </DataTable>
                <Paginator class="mt-2" v-model:page="page" :pagination="paginationMeta" />
            </div>
        </div>
    </div>
</template>
