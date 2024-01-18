export function isError(error: unknown, name?: string): error is Error {
    return error instanceof Error
        && (name === undefined || error.name === name);
}

export function isErrorClass<ErrorType extends Error>(
    error: unknown,
    // eslint-disable-next-line @typescript-eslint/no-explicit-any -- We need `any` here to support various constructors
    errorConstructor: new (...args: any[]) => ErrorType,
): error is ErrorType {
    return isError(error, errorConstructor.name);
}
