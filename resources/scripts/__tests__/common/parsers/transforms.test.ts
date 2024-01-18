import {DateTime} from 'luxon';
import {describe, expect, it} from 'vitest';
import {nullableZuluStringToDateTime} from '@/common/parsers/transforms';

describe('transforms', () => {
    describe('nullableZuluStringToDateTime', () => {
        it.each([
            ['valid date', {
                value: '2022-11-15T22:24:41Z',
                expected: DateTime.fromISO('2022-11-15T22:24:41Z'),
            }],
            ['invalid date', {
                value: '2022-02-30',
                expected: null,
            }],
            ['null', {value: null, expected: null}],
        ])('%s', (_, {value, expected}) => {
            expect(nullableZuluStringToDateTime.parse(value)).toEqual(expected);
        });
    });
});
