import Permission from '@/common/enums/Permission';

export default class ForbiddenError extends Error {
    public readonly name = 'ForbiddenError';

    constructor(public readonly permissions: Permission[]) {
        super('You lack the permission to access this page.');
    }
}
