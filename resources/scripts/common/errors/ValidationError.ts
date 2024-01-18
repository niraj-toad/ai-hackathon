import {HTTPError} from 'ky';
import {array,object, record, string} from 'zod';

export default class ValidationError extends HTTPError {
    public readonly name = 'ValidationError';

    constructor(
        public readonly error: HTTPError,
        public readonly messages: Record<string, string[]> = {},
    ) {
        super(
            error.response,
            error.request,
            error.options,
        );
    }

    public static async new(error: HTTPError): Promise<ValidationError> {
        const parser = object({
            errors: record(array(string())),
        });

        const payload = await error.response.clone().json();

        return new ValidationError(error, parser.parse(payload).errors);
    }
}
