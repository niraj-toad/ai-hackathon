import {useMutation} from '@tanstack/vue-query';
import useClient from '@/common/api/client';
import {forgotPasswordRoute} from '@/common/api/routes';

interface ForgotPasswordPayload {
    email: string;
}

export default function useSendPasswordResetEmailMutation() {
    return useMutation({
        mutationFn: async (payload: ForgotPasswordPayload) => {
            const client = useClient();

            return await client.post(forgotPasswordRoute(), {
                json: payload,
            }).json();
        },
    });
}
