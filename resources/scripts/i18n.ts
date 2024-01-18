import {createI18n, I18n} from 'vue-i18n';
import en from '@/languages/en.json';

type MessageSchema = typeof en;

export function setupI18n() {
    const i18n = createI18n({
        locale: navigator.language,
        fallbackLocale: 'en-US',
    });

    loadLocaleMessages(i18n, navigator.language).then(messages => {
        i18n.global.setLocaleMessage(navigator.language, messages);
    });

    return i18n;
}

async function loadLocaleMessages(i18n: I18n, language: string): Promise<MessageSchema> {
    switch (language) {
        case 'es':
            return await import('@/languages/es.json');
        case 'en-US':
        default:
            return await import('@/languages/en.json');
    }
}
