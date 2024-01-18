<script lang="ts" setup>
import {useForm} from 'vee-validate';
import {ref} from 'vue';
import FormButton from '@/common/components/FormButton.vue';
import FormInput from '@/common/components/FormInput.vue';
import useSendPasswordResetEmailMutation from '@/common/composables/mutations/useSendPasswordResetEmailMutation';
import useUnexpectedErrorHandler from '@/common/composables/useUnexpectedErrorHandler';
import ValidationError from '@/common/errors/ValidationError';

const {mutateAsync: sendPasswordResetEmail} = useSendPasswordResetEmailMutation();
const handleUnexpectedError = useUnexpectedErrorHandler();

const {handleSubmit, setErrors, setFieldError, isSubmitting} = useForm<{
    email: string;
}>({
    initialValues: {
        email: '',
    },
});

const emailSent = ref(false);

const onSubmit = handleSubmit(async (values) => {
    try {
        await sendPasswordResetEmail(values);
        emailSent.value = true;
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
    }
});
</script>

<template>
    <main>
        <h1>{{ $t('forgot_password') }}</h1>
        <p v-if="emailSent">
            {{ $t('password_reset_link_sent') }}
        </p>
        <form v-else novalidate @submit="onSubmit">
            <FormInput
                :label="$t('email')"
                name="email"
                placeholder="email@example.com"
                type="email"
            />
            <FormButton
                :label="$t('send_password_reset_link')"
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
