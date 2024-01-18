import {preprocess, ZodType} from 'zod';

type Wrapped<Key extends string> = {
    [key in Key]: unknown;
};

function isWrapped(payload: unknown, wrapper: string): payload is Wrapped<typeof wrapper> {
    if (!payload) {
        return false;
    }

    return typeof payload === 'object' && Object.prototype.hasOwnProperty.call(payload, wrapper);
}

export default function unwrap<ResourceType extends ZodType>(
    parser: ResourceType,
    wrapper = 'data',
) {
    return preprocess(payload => {
        if (!isWrapped(payload, wrapper)) {
            throw new Error(`Did not receive a response payload wrapped with ${wrapper}.`);
        }

        return payload[wrapper];
    }, parser);
}
