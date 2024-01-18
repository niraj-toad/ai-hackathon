import {array, object, output, ZodTypeAny} from 'zod';
import {paginationMetaParser} from '@/common/parsers/paginationMetaParser';

export function paginatedResponse<ResourceType extends ZodTypeAny>(type: ResourceType) {
    return object({
        data: array(type),
        meta: paginationMetaParser,
    });
}
export type PaginatedResponse<ResourceType extends ZodTypeAny> = output<
    ReturnType<typeof paginatedResponse<ResourceType>>
>;
