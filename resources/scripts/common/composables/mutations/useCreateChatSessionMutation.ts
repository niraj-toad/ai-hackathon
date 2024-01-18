import {useMutation} from '@tanstack/vue-query';
import useClient from '@/common/api/client';
import {chatSessionRoute} from '@/common/api/routes';
import {ChatSession, chatSessionParser} from '@/common/parsers/chatSessionParser';
import unwrap from '@/common/parsers/unwrap';

export default function useCreateChatSessionMutation() {
    return useMutation<ChatSession>({
        mutationFn: async (): Promise<ChatSession> => {
            const client = useClient();

            const payload = await client.post(chatSessionRoute()).json();

            return unwrap(chatSessionParser).parse(payload);
        },
    });
}
