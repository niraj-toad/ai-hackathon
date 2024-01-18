import {array, object, output, string} from 'zod';
import {chatMessageParser} from '@/common/parsers/chatMessageParser';

export const chatSessionParser = object({
    id: string(),
    messages: array(chatMessageParser),
});

export type ChatSession = output<typeof chatSessionParser>;
