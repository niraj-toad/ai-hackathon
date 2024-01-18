import {useMutation} from '@tanstack/vue-query';
import useClient from '@/common/api/client';
import {loginRoute} from '@/common/api/routes';
import unwrap from '@/common/parsers/unwrap';
import {AuthenticatedUser, authenticatedUserParser} from '@/common/parsers/userParser';
import useAuthStore from '@/common/stores/auth.store';

interface AuthenticateRequestPayload {
    email: string;
    password: string;
    remember: boolean;
}

export default function useLoginMutation() {
    return useMutation({
        mutationFn: async (payload: AuthenticateRequestPayload): Promise<AuthenticatedUser> => {
            const client = useClient();
            const {setUser} = useAuthStore();

            const response = await client.post(loginRoute(), {
                json: payload,
            }).json();

            const user = unwrap(authenticatedUserParser).parse(response);
            setUser(user);

            return user;
        },
    });
}
