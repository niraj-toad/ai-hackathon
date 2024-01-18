import ky, {Options} from 'ky';
import handleInvalidXsrfToken from '@/common/api/hooks/handleInvalidXsrfToken';
import handleValidationError from '@/common/api/hooks/handleValidationError';
import injectXsrfToken from '@/common/api/hooks/injectXsrfToken';
import useMetaStore from '@/common/stores/meta.store';

export default function useClient(options: Options = {}) {
    const metaStore = useMetaStore();

    if (!metaStore.appUrl) {
        throw new Error('Cannot create API client without appUrl.');
    }

    return ky.create({
        headers: {
            // If we don't include this, when Laravel throws a validation exception it will not know we want JSON and
            // redirect instead.
            'Accept': 'application/json',
        },
        hooks: {
            afterResponse: [handleInvalidXsrfToken],
            beforeError: [handleValidationError],
            beforeRequest: [injectXsrfToken],
        },
        prefixUrl: metaStore.appUrl,
        retry: {
            statusCodes: [408, 413, 429, 502, 503, 504],
        },
    }).extend(options);
}
