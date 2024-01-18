import {useMutation} from '@tanstack/vue-query';
import useClient from '@/common/api/client';
import {resetPasswordRoute} from '@/common/api/routes';

interface ResetPasswordPayload {
    email: string;
    password: string;
    password_confirmation: string;
    token: string;
}

interface ResetPasswordResponse {
    message: string;
}

export default function useResetPasswordMutation() {
    return useMutation({
        mutationFn: async (payload: ResetPasswordPayload): Promise<ResetPasswordResponse> => {
            const client = useClient();

            return await client.post(resetPasswordRoute(), {
                json: payload,
            }).json();
        },
    });
}
