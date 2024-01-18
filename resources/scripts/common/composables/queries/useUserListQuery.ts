import {useQuery} from '@tanstack/vue-query';
import {MaybeRef, unref} from 'vue';
import useClient from '@/common/api/client';
import {userRoute} from '@/common/api/routes';
import {paginatedResponse} from '@/common/parsers/paginatedResponseParser';
import {authenticatedUserParser} from '@/common/parsers/userParser';

interface QueryParams {
    page?: number;
    perPage?: number;
    search?: string;
}

export default function useUserListQuery(params: MaybeRef<QueryParams>) {
    return useQuery({
        queryKey: ['users', params],
        queryFn: async ({queryKey}) => {
            const client = useClient({
                searchParams: unref(queryKey[1]),
            });

            const payload = await client.get(userRoute()).json();

            return paginatedResponse(authenticatedUserParser).parse(payload);
        },
    });
}
