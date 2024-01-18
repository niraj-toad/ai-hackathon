## Chatbot

AI assistant powered by OpenAI. Feature includes:
* A chat popup component
* Chat Session and Message endpoints, models, controllers, and migrations
* A chatbot interface and OpenAI service

### Added files
* `app/Http/Controllers/ChatMessageController.php`
* `app/Http/Controllers/ChatSessionController.php`
* `app/Http/Requests/ChatMessage/StoreRequest.php`
* `app/Http/Requests/ChatSession/StoreRequest.php`
* `app/Http/Resources/ChatMessageResource.php`
* `app/Http/Resources/ChatSessionResource.php`
* `app/Models/ChatSession.php`
* `app/Models/ChatMessage.php`
* `app/Services/Chatbot/ChatbotInterface.php`
* `app/Services/Chatbot/OpenAI.php`
* `config/openai.php`
* `database/migrations/2023_07_23_181030_create_chat_sessions_table.php`
* `database/migrations/2023_07_23_181037_create_chat_messages_table.php`
* `resources/scripts/common/api/requests/useChatMessageRequest.ts`
* `resources/scripts/common/api/requests/useChatSessionRequest.ts`
* `resources/scripts/common/components/ChatbotPopover.vue`
* `resources/scripts/common/parsers/chatMessageParser.ts`
* `resources/scripts/common/parsers/chatSessionParser.ts`

### Modified files
* `.env.example`
* `app/Providers/AppServiceProvider.php`
* `resources/scripts/App.vue`
* `resources/scripts/common/api/routes.ts`
* `routes/api.php`

### Dependencies
#### Composer
* `openai-php/laravel`
