import {useRouteQuery} from '@vueuse/router';
import {fromPairs, toPairs} from 'lodash';
import {computed} from 'vue';
import {string} from 'zod';

export type Params = {
    page: number;
    per_page: number;
}

export default function useListControls(filterBy: string[]|undefined) {
    const page = useRouteQuery<number>('page', 1, {
        transform: Number,
    });
    const sort = useRouteQuery('sort', undefined, {
        transform: string().optional().parse,
    });
    const filters = filterBy?.map(name => ({
        name,
        value: useRouteQuery(`filter[${name}]`, ''),
    })) ?? [];

    const filterParams = computed<{[key: string]: string}>(() => {
        const params = {} as {[key: string]: string};

        for (const filter of filters) {
            if (filter.value.value) {
                const key = `filter[${filter.name}]`;
                params[key] = filter.value.value as string;
            }
        }

        return params;
    });

    const params = computed<Params>(() => {
        const initialParams = {
            page: page.value,
            per_page: 15,
            sort: sort.value,
            ...filterParams.value,
        };
        const entries = toPairs(initialParams).filter(entry => !!entry[1]);
        return fromPairs(entries) as Params;
    });

    return {
        page,
        sort,
        params,
        filters,
    };
}
