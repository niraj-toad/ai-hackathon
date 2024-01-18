import {BeforeErrorHook} from 'ky';
import {HTTP_UNPROCESSABLE_ENTITY} from '@/common/constants/httpStatus';
import ValidationError from '@/common/errors/ValidationError';

const handleValidationError: BeforeErrorHook = async error => {
    if (error.response.status === HTTP_UNPROCESSABLE_ENTITY) {
        return await ValidationError.new(error);
    }

    return error;
};

export default handleValidationError;
