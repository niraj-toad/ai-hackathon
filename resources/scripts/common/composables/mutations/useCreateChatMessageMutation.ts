import {DefaultError, useMutation} from '@tanstack/vue-query';
import useClient from '@/common/api/client';
import {chatSessionMessageRoute} from '@/common/api/routes';
import {ChatMessage, chatMessageParser} from '@/common/parsers/chatMessageParser';
import unwrap from '@/common/parsers/unwrap';

interface CreateChatMessageRequestPayload {
    content: string;
}

interface CreateChatMessageMutationOptions {
    chatSessionId: string;
    payload: CreateChatMessageRequestPayload;
}

export default function useCreateChatMessageMutation() {
    return useMutation<
        ChatMessage,
        DefaultError,
        CreateChatMessageMutationOptions
    >({
        mutationFn: async ({
            chatSessionId,
            payload,
        }: CreateChatMessageMutationOptions): Promise<ChatMessage> => {
            const client = useClient();

            const responsePayload = await client.post(chatSessionMessageRoute({chatSessionId}), {
                json: payload,
            }).json();

            return unwrap(chatMessageParser).parse(responsePayload);
        },
    });
}
