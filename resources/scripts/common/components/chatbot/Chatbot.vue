<script setup lang="ts">
import {faPaperPlane} from '@fortawesome/free-solid-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
import {marked} from 'marked';
import {computed, ref} from 'vue';
import useCreateChatMessageMutation from '@/common/composables/mutations/useCreateChatMessageMutation';
import useCreateChatSessionMutation from '@/common/composables/mutations/useCreateChatSessionMutation';
import {ChatMessage} from '@/common/parsers/chatMessageParser';
import {ChatSession} from '@/common/parsers/chatSessionParser';

const {mutateAsync: createChatSession} = useCreateChatSessionMutation();
const {mutateAsync: createChatMessage} = useCreateChatMessageMutation();

const chatSession = ref<ChatSession | null>(null);

const message = ref<string>('');
const errorMessage = ref<string>('');
const messageLog = computed(() => [
    ...(chatSession.value?.messages ?? []),
].reverse());

async function submit() {
    if (!chatSession.value || !message.value) {
        return;
    }

    const lastMessage = {
        role: 'user',
        content: message.value,
    };
    chatSession.value.messages.push(lastMessage);
    message.value = '';

    const loadingMessage = typeMessage({
        role: 'assistant',
        content: '...',
    }, 250, true);

    try {
        const responseMessage = await createChatMessage({
            chatSessionId: chatSession.value.id,
            payload: {content: lastMessage.content},
        });

        loadingMessage?.cancel();

        typeMessage(responseMessage);
    } catch (e) {
        loadingMessage?.cancel();
        errorMessage.value = 'Something went wrong. Reload the window to restart chat.';
    }
}

function typeMessage(message: ChatMessage, delay = 10, loop = false) {
    if (!chatSession.value?.messages) {
        return null;
    }

    const messageIndex = chatSession.value.messages.push({
        ...message,
        content: '',
    }) - 1;

    const currentMessage = chatSession.value?.messages[messageIndex];

    let charsTyped = 0;
    const interval = setInterval(() => {
        if (charsTyped === message.content.length) {
            if (loop) {
                replaceMessage(messageIndex, {
                    ...currentMessage,
                    content: '',
                });
                charsTyped = 0;
            } else {
                clearInterval(interval);
                return;
            }
        }
        appendToMessage(messageIndex, message.content[charsTyped]);
        charsTyped++;
    }, delay);

    return {
        cancel: () => {
            clearInterval(interval);
            chatSession.value?.messages.splice(messageIndex, 1);
        },
    };
}

function appendToMessage(messageIndex: number, content: string) {
    const message = chatSession.value?.messages[messageIndex];
    if (!message) {
        return;
    }
    replaceMessage(messageIndex, {
        role: message.role,
        content: message.content + content,
    });
}

function replaceMessage(messageIndex: number, message: ChatMessage) {
    chatSession.value?.messages.splice(messageIndex, 1, message);
}

chatSession.value = await createChatSession();
</script>

<template>
    <div class="chatbot">
        <div>
            <div class="body flex-col-reverse">
                <div v-if="!!errorMessage" class="message err">{{ errorMessage }}</div>
                <div
                    v-for="(message, i) in messageLog"
                    :key="`chat-message-${i}`"
                    :class="['message', message.role]"
                >
                    <span v-html="marked.parse(message.content)" />
                    <span class="tail" />
                </div>
            </div>
            <div class="footer">
                <form @submit.prevent="submit">
                    <input
                        v-model="message"
                        :disabled="!!errorMessage"
                        type="text"
                        placeholder="Type your message here..."
                    />
                    <button :disabled="!!errorMessage">
                        <FontAwesomeIcon :icon="faPaperPlane" />
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
.chatbot {
    @apply w-full h-full;
    @apply rotate-180;
    > div {
        @apply bg-primary-0;
        @apply flex flex-col;
        @apply w-full h-full;
        @apply rotate-180;
        @apply overflow-hidden;

        .body {
            @apply flex-1;
            @apply flex flex-col-reverse;
            @apply p-4;
            @apply overflow-y-auto;

            .message {
                @apply px-5 py-3;
                @apply rounded-3xl;
                @apply mb-4;
                @apply break-words;
                @apply min-w-20;
                @apply max-w-[60%];

                &.user {
                    @apply relative;
                    @apply bg-accent-800;
                    @apply text-primary-100;
                    @apply self-end;

                    .tail {
                        @apply absolute;
                        @apply w-0 h-0;
                        @apply border-t-[1.6rem] border-t-accent-800;
                        @apply border-l-[1.6rem] border-l-transparent;
                        @apply -bottom-4 right-8;
                        @apply -rotate-[25deg];
                    }
                }
                &.assistant {
                    @apply relative;
                    @apply bg-primary-200;
                    @apply text-primary-900;
                    @apply self-start;

                    .tail {
                        @apply absolute;
                        @apply w-0 h-0;
                        @apply border-t-[1.6rem] border-t-primary-200;
                        @apply border-r-[1.6rem] border-r-transparent;
                        @apply -bottom-4 left-8;
                        @apply rotate-[25deg];
                    }
                }
                &.err {
                    @apply text-red-600;
                    @apply w-full;
                }
            }
        }
        .footer {
            @apply p-4;

            form {
                @apply w-full;
                @apply flex items-center justify-between;

                input {
                    @apply flex-1;
                    @apply border-2 border-primary-100;
                    @apply rounded-3xl;
                    @apply p-2;
                }

                button {
                    @apply text-accent-800;
                    @apply p-2;
                    @apply ml-2;
                }
            }
        }
    }
}
</style>
