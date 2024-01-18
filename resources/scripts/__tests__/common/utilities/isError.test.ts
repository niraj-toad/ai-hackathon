import {describe, expect, it} from 'vitest';
import ForbiddenError from '@/common/errors/ForbiddenError';
import {isError, isErrorClass} from '@/common/utilities/isError';

describe('isError', () => {
    it.each([
        ['non-error', 'string', undefined, false],
        ['error without name', new Error, undefined, true],
        ['error with name that does not match', new ForbiddenError([]), 'AbortError', false],
        ['error with name that does match', new ForbiddenError([]), ForbiddenError.name, true],
    ])('%s', (_, value, name, expected) => {
        const result = isError(value, name);

        expect(result).toEqual(expected);
    });
});

describe('isErrorClass', () => {
    it.each([
        ['non-error', 'string', ForbiddenError, false],
        ['not expected error', new Error, ForbiddenError, false],
        ['expected error', new ForbiddenError([]), ForbiddenError, true],
    ])('%s', (_, value, constructor, expected) => {
        const result = isErrorClass(value, constructor);

        expect(result).toEqual(expected);
    });
});
