import * as Sentry from '@sentry/vue';
import {VueQueryPlugin} from '@tanstack/vue-query';
import {DateTime} from 'luxon';
import {createPinia} from 'pinia';
import {createApp} from 'vue';
import useAuthStore from '@/common/stores/auth.store';
import useMetaStore from '@/common/stores/meta.store';
import {setupI18n} from '@/i18n';
import queryClient from '@/queryClient';
import Root from '@/Root.vue';
import router from '@/router';

const pinia = createPinia();

const i18n = setupI18n();

const authStore = useAuthStore(pinia);
const metaStore = useMetaStore(pinia);

const app = createApp(Root)
    .use(VueQueryPlugin, {queryClient})
    .use(pinia)
    .use(i18n)
    .use(router);

Sentry.init({
    app,
    autoSessionTracking: true,
    beforeSend(event) {
        event.extra ??= {};
        event.extra.viewportHeight = window.innerHeight;
        event.extra.viewportWidth = window.innerWidth;

        const localDate = DateTime.local();
        event.contexts ??= {};
        event.contexts.culture ??= {};

        if (localDate.locale && localDate.zoneName) {
            event.contexts.culture.locale = localDate.locale;
            event.contexts.culture.timezone = localDate.zoneName;
        }

        event.user ??= {};
        event.user.id = authStore.user?.id.toString();
        event.user.ip_address = '{{auto}}';

        return event;
    },
    debug: metaStore.debug,
    dsn: metaStore.sentry.dsn,
    environment: metaStore.sentry.environment,
    integrations: [
        new Sentry.BrowserTracing({
            routingInstrumentation: Sentry.vueRouterInstrumentation(router),
            tracePropagationTargets: [...metaStore.sentry.tracePropagationTargets, /^\//],
        }),
    ],
    release: metaStore.sentry.release,
    tracesSampleRate: metaStore.sentry.tracesSampleRate ?? 0,
});

app.mount('#app');
