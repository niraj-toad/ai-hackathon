<script lang="ts" setup>
import {useForm} from 'vee-validate';
import {useRouter} from 'vue-router';
import FormButton from '@/common/components/FormButton.vue';
import FormInput from '@/common/components/FormInput.vue';
import useLoginMutation from '@/common/composables/mutations/useLoginMutation';
import useUnexpectedErrorHandler from '@/common/composables/useUnexpectedErrorHandler';
import ValidationError from '@/common/errors/ValidationError';
import useAuthStore from '@/common/stores/auth.store';

const {mutateAsync: login} = useLoginMutation();
const authStore = useAuthStore();
const handleUnexpectedError = useUnexpectedErrorHandler();
const router = useRouter();

const {handleSubmit, setErrors, setFieldError, setFieldValue, isSubmitting} = useForm<{
    email: string;
    password: string;
    remember: boolean;
}>({
    initialValues: {
        email: '',
        password: '',
        remember: false,
    },
});

const onSubmit = handleSubmit(async (values) => {
    try {
        await login(values);
        const route = authStore.intendedRoute ?? authStore.mainRoute;
        authStore.setIntendedRoute(undefined);
        await router.push(route);
    } catch (err) {
        if (err instanceof ValidationError) {
            setErrors(err.messages);

            if (err.messages._) {
                setFieldError('email', err.messages._);
            }
        } else {
            setFieldError('email', 'Something went wrong.');
            handleUnexpectedError(err);
        }
    } finally {
        setFieldValue('password', '');
    }
});
</script>

<template>
    <main id="login">
        <h1>{{ $t('welcome') }}</h1>
        <form novalidate @submit="onSubmit">
            <FormInput
                :label="$t('email')"
                name="email"
                placeholder="email@example.com"
                type="email"
            />
            <FormInput
                :label="$t('password')"
                name="password"
                :placeholder="$t('your_password')"
                type="password"
            />
            <FormButton
                :label="$t('login')"
                :loading="isSubmitting"
                type="submit"
            />
        </form>
        <div>
            Forgot your password? <router-link :to="{ name: 'password.email' }">Reset it</router-link>
        </div>
    </main>
</template>

<style scoped>
main {
    @apply flex flex-1 flex-col gap-8 items-center justify-center;
    @apply max-w-lg w-full;
    @apply mx-auto p-8;

    @apply md:p-8;
}

h1 {
    @apply font-bold text-3xl;
}

form {
    @apply flex flex-col gap-4 self-stretch;
}
</style>
