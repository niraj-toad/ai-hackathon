import {array, nativeEnum, object, output, string} from 'zod';
import Permission from '@/common/enums/Permission';
import Role from '@/common/enums/Role';
import {nullableZuluStringToDateTime} from '@/common/parsers/transforms';

export const authenticatedUserParser = object({
    created_at: nullableZuluStringToDateTime,
    email: string(),
    first_name: string(),
    id: string(),
    last_name: string(),
    permissions: array(nativeEnum(Permission)),
    role: nativeEnum(Role),
    updated_at: nullableZuluStringToDateTime,
});

export type AuthenticatedUser = output<typeof authenticatedUserParser>;
