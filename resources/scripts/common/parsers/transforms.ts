import {DateTime} from 'luxon';
import {string} from 'zod';

export const nullableZuluStringToDateTime = string().nullable().transform(value => {
    if (value === null) {
        return null;
    }

    const parsed = DateTime.fromISO(value);

    return parsed.isValid ? parsed : null;
});
