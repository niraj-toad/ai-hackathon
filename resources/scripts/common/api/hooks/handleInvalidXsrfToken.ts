import ky, {AfterResponseHook} from 'ky';
import {HTTP_INVALID_CSRF_TOKEN} from '@/common/constants/httpStatus';

const handleInvalidXsrfToken: AfterResponseHook = async (request, options, response) => {
    if (response.status === HTTP_INVALID_CSRF_TOKEN) {
        await ky('sanctum/csrf-cookie', {
            prefixUrl: options.prefixUrl,
        });

        return ky(request, options);
    }

    return response;
};

export default handleInvalidXsrfToken;
