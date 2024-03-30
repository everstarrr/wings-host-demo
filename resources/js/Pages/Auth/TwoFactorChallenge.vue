<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { nextTick, ref } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const recovery = ref(false);

const form = useForm({
    code: "",
    recovery_code: "",
});

const recoveryCodeInput = ref(null);
const codeInput = ref(null);

const toggleRecovery = async () => {
    recovery.value ^= true;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value.focus();
        form.code = "";
    } else {
        codeInput.value.focus();
        form.recovery_code = "";
    }
};

const submit = () => {
    form.post(route("two-factor.login"));
};
</script>

<template>
    <Head :title="$t('auth.2fa')" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-neutral-400">
            <template v-if="!recovery">
                {{ $t("auth.2fa_enter_code") }}
            </template>

            <template v-else>
                {{ $t("auth.2fa_enter_recovery_code") }}
            </template>
        </div>

        <form @submit.prevent="submit">
            <div v-if="!recovery">
                <InputLabel
                    for="code"
                    :value="$t('auth.2fa_code')" />
                <TextInput
                    id="code"
                    ref="codeInput"
                    v-model="form.code"
                    type="text"
                    inputmode="numeric"
                    class="mt-1 block w-full"
                    autofocus
                    autocomplete="one-time-code" />
                <InputError
                    class="mt-2"
                    :message="form.errors.code" />
            </div>

            <div v-else>
                <InputLabel
                    for="recovery_code"
                    :value="$t('auth.2fa_recovery_code')" />
                <TextInput
                    id="recovery_code"
                    ref="recoveryCodeInput"
                    v-model="form.recovery_code"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="one-time-code" />
                <InputError
                    class="mt-2"
                    :message="form.errors.recovery_code" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button
                    type="button"
                    class="text-sm text-neutral-400 hover:text-neutral-900 underline cursor-pointer"
                    @click.prevent="toggleRecovery">
                    <template v-if="!recovery">
                        {{ $t("auth.2fa_recovery_code") }}
                    </template>

                    <template v-else>
                        {{ $t("auth.2fa_use_otp_code") }}
                    </template>
                </button>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    {{ $t("auth.login") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
