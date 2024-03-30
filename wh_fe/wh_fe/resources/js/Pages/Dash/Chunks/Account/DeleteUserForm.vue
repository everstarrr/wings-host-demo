<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import DangerButton from "@/Components/DangerButton.vue";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route("current-user.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            <!--            Delete Account-->
            Удалить аккаунт
        </template>

        <template #description>
            <!--            Permanently delete your account.-->
            Безвозвратно удалить свой аккаунт.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-neutral-400">
                <!--                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.-->
                Удалить ваш аккаунт навсегда. Совсем навсегда. Вместе со всеми серверами и деньгами на балансе.
            </div>

            <div class="mt-5">
                <DangerButton
                    class="h-10 w-max px-6"
                    @click="confirmUserDeletion">
                    Удалить аккаунт
                </DangerButton>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <DialogModal
                :show="confirmingUserDeletion"
                @close="closeModal">
                <template #title> Удалить аккаунт </template>

                <template #content>
                    <!--                    Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.-->
                    Введите пароль, чтобы удалить аккаунт.

                    <div class="mt-4">
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-3/4"
                            :placeholder="$t('auth.password_field')"
                            autocomplete="current-password"
                            @keyup.enter="deleteUser" />

                        <InputError
                            :message="form.errors.password"
                            class="mt-2" />
                    </div>
                </template>

                <template #footer>
                    <button
                        @click="closeModal"
                        type="button"
                        class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-300 border border-neutral-500 hover:border-neutral-300 transition-all duration-150 ease-in-out block focus:border-neutral-300">
                        Отмена
                    </button>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser">
                        Удалить аккаунт
                    </DangerButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>
