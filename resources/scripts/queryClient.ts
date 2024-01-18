import {QueryClient} from '@tanstack/vue-query';

const queryClient = new QueryClient({
    defaultOptions: {
        queries: {
            retry: false,
            staleTime: 30_000,
        },
    },
});

export default queryClient;
