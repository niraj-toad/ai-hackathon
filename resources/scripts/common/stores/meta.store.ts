import {defineStore} from 'pinia';
import {ref} from 'vue';

const useMetaStore = defineStore('meta', () => {
    const appData = document.getElementById('app')?.dataset;
    const appUrl = ref<string>();
    const appVersion = ref<string>();
    const sentry = ref<{
        dsn: string;
        environment: string;
        release: string;
        tracePropagationTargets: string[];
        tracesSampleRate: number | undefined;
    }>({
        dsn: appData?.sentryDsn ?? '',
        environment: appData?.sentryEnvironment ?? '',
        release: appData?.sentryRelease ?? '',
        tracePropagationTargets: (appData?.sentryTracePropagationTargets ?? '').split(','),
        tracesSampleRate: appData?.sentryTracesSampleRate
            ? parseFloat(appData.sentryTracesSampleRate)
            : undefined,
    });
    const initialized = ref<boolean>(false);

    function initialize() {
        if (initialized.value) {
            return;
        }

        if (!appData?.appUrl) {
            throw new Error('App URL is not defined.');
        }

        appUrl.value = appData.appUrl;
        appVersion.value = appData.appVersion;

        initialized.value = true;
    }

    return {
        appUrl,
        appVersion,
        debug: import.meta.env.DEV,
        initialize,
        initialized,
        sentry,
    };
});

export default useMetaStore;
