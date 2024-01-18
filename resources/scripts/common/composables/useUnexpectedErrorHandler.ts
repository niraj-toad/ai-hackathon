import useMetaStore from '@/common/stores/meta.store';

export default function useUnexpectedErrorHandler() {
    const metaStore = useMetaStore();

    return (err: unknown) => {
        if (metaStore.debug) {
            // We're in development mode, so we can just throw the error.
            throw err;
        } else {
            // We're in production mode, so we need to log the error.
            console.error('\x1B[107;31m UNEXPECTED ERROR \x1B[m', err);
        }
    };
}
