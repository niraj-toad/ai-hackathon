import {number, object, output} from 'zod';

export const paginationMetaParser = object({
    current_page: number().int(),
    last_page: number().int(),
    per_page: number().int(),
    total: number().int(),
});

export type PaginationMeta = output<typeof paginationMetaParser>;
