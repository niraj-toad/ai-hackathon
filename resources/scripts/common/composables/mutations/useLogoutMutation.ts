import {useMutation, useQueryClient} from '@tanstack/vue-query';
import useClient from '@/common/api/client';
import {logoutRoute} from '@/common/api/routes';
import useAuthStore from '@/common/stores/auth.store';

export default function useLogoutMutation() {
    const queryClient = useQueryClient();

    return useMutation({
        mutationFn: async () => {
            const client = useClient();
            const {setUser} = useAuthStore();

            try {
                await client.post(logoutRoute()).json();
            } finally {
                setUser(undefined);
            }
        },
        onSuccess() {
            queryClient.clear();
        },
    });
}
