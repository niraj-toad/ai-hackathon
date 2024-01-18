/// <reference types="vite/client" />
import 'vue-router';

declare module '*.vue' {
    import type {DefineComponent} from 'vue';
    // eslint-disable-next-line @typescript-eslint/ban-types,@typescript-eslint/no-explicit-any
    const component: DefineComponent<{}, {}, any>;
    export default component;
}

declare module 'vue-router' {
    // interface RouteMeta {
    //     Custom meta goes here
    // }
}

declare module '@vue/runtime-core' {
    // export interface ComponentCustomProperties {
    //     Custom properties go here (typically injected via plugins)
    // }
}
