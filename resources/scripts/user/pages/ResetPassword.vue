<script lang="ts" setup>
import {useForm} from 'vee-validate';
import {ref} from 'vue';
import {useRoute} from 'vue-router';
import FormButton from '@/common/components/FormButton.vue';
import FormInput from '@/common/components/FormInput.vue';
import useResetPasswordMutation from '@/common/composables/mutations/useResetPasswordMutation';
import useUnexpectedErrorHandler from '@/common/composables/useUnexpectedErrorHandler';
import ValidationError from '@/common/errors/ValidationError';

const route = useRoute();

const {mutateAsync: resetPassword} = useResetPasswordMutation();
const handleUnexpectedError = useUnexpectedErrorHandler();

const {handleSubmit, setErrors, setFieldError, resetForm, isSubmitting} = useForm<{
    email: string;
    password: string;
    password_confirmation: string;
    token: string;
}>({
    initialValues: {
        email: route.query.email?.toString() ?? '',
        password: '',
        password_confirmation: '',
        token: route.query.token?.toString() ?? '',
    },
});

const successMessage = ref<string|null>(null);

const onSubmit = handleSubmit(async (values) => {
    if (values.password !== values.password_confirmation) {
        setFieldError('password_confirmation', 'Passwords do not match.');
        return;
    }
    try {
        const {message} = await resetPassword(values);
        successMessage.value = message;
    } catch (err) {
        resetForm();
        if (err instanceof ValidationError) {
            if ('email' in err.messages || 'token' in err.messages) {
                setFieldError('password', 'Something went wrong.');
            } else {
                setErrors(err.messages);
            }
        } else {
            setFieldError('password', 'Something went wrong.');
            handleUnexpectedError(err);
        }
    }
});
</script>

<template>
    <main>
        <h1>{{ $t('reset_password') }}</h1>
        <p v-if="successMessage">{{ successMessage }}</p>
        <form v-else novalidate @submit="onSubmit">
            <FormInput
                :label="$t('enter_a_new_password')+':'"
                name="password"
                :placeholder="$t('new_password')"
                type="password"
            />
            <FormInput
                :label="$t('confirm_password')+':'"
                name="password_confirmation"
                :placeholder="$t('new_password')"
                type="password"
            />
            <FormButton
                :label="$t('set_new_password')"
                :loading="isSubmitting"
                type="submit"
            />
        </form>
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
