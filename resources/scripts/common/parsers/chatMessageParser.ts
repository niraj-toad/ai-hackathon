import {object, output, string} from 'zod';

export const chatMessageParser = object({
    id: string().optional().nullable(),
    role: string(),
    content: string(),
});

export type ChatMessage = output<typeof chatMessageParser>;
