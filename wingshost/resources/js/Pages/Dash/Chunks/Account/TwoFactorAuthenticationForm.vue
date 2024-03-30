<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { ref, computed, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import ConfirmsPassword from "@/Components/ConfirmsPassword.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    requiresConfirmation: Boolean,
});

const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: "",
});

const twoFactorEnabled = computed(() => !enabling.value && usePage().props.auth.user?.two_factor_enabled);

watch(twoFactorEnabled, () => {
    if (!twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(
        route("two-factor.enable"),
        {},
        {
            preserveScroll: true,
            onSuccess: () => Promise.all([showQrCode(), showSetupKey(), showRecoveryCodes()]),
            onFinish: () => {
                enabling.value = false;
                confirming.value = props.requiresConfirmation;
            },
        },
    );
};

const showQrCode = () => {
    return axios.get(route("two-factor.qr-code")).then((response) => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route("two-factor.secret-key")).then((response) => {
        setupKey.value = response.data.secretKey;
    });
};

const showRecoveryCodes = () => {
    return axios.get(route("two-factor.recovery-codes")).then((response) => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route("two-factor.confirm"), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios.post(route("two-factor.recovery-codes")).then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route("two-factor.disable"), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            {{ $t("auth.2fa") }}
        </template>

        <template #description>
            {{ $t("account.2fa_notice") }}
        </template>

        <template #content>
            <h3
                v-if="twoFactorEnabled && !confirming"
                class="text-lg font-medium text-neutral-100">
                {{ $t("account.2fa_enabled") }}
            </h3>

            <h3
                v-else-if="twoFactorEnabled && confirming"
                class="text-lg font-medium text-neutral-100">
                {{ $t("account.2fa_finish") }}
            </h3>

            <h3
                v-else
                class="text-lg font-medium text-neutral-100">
                {{ $t("account.2fa_disabled") }}
            </h3>

            <div class="mt-3 max-w-xl text-sm text-neutral-400">
                <p>
                    {{ $t("account.2fa_notice2") }}
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-neutral-400">
                        <p
                            v-if="confirming"
                            class="font-semibold">
                            {{ $t("account.2fa_setup_notice") }}
                        </p>

                        <p v-else>
                            {{ $t("account.2fa_setup_notice") }}
                        </p>
                    </div>

                    <div
                        class="mt-4 p-2 inline-block bg-white"
                        v-html="qrCode" />

                    <div
                        v-if="setupKey"
                        class="mt-4 max-w-xl text-sm text-neutral-400">
                        <p class="font-semibold">{{ $t("account.2fa_setup_key") }}: <span v-html="setupKey"></span></p>
                    </div>

                    <div
                        v-if="confirming"
                        class="mt-4">
                        <InputLabel
                            for="code"
                            :value="$t('auth.2fa_code')" />

                        <TextInput
                            id="code"
                            v-model="confirmationForm.code"
                            type="text"
                            name="code"
                            class="block mt-1 w-1/2"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                            @keyup.enter="confirmTwoFactorAuthentication" />

                        <InputError
                            :message="confirmationForm.errors.code"
                            class="mt-2" />
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && !confirming">
                    <div class="mt-4 max-w-xl text-sm text-neutral-400">
                        <p class="font-semibold">
                            {{ $t("account.2fa_recovery_codes_notice") }}
                        </p>
                    </div>

                    <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-neutral-900 rounded-lg">
                        <div
                            v-for="code in recoveryCodes"
                            :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="!twoFactorEnabled">
                    <ConfirmsPassword @confirmed="enableTwoFactorAuthentication">
                        <button
                            :disabled="enabling"
                            type="button"
                            class="w-32 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block">
                            Включить
                        </button>
                    </ConfirmsPassword>
                </div>

                <div v-else>
                    <ConfirmsPassword @confirmed="confirmTwoFactorAuthentication">
                        <button
                            v-if="confirming"
                            :disabled="enabling"
                            type="button"
                            class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block ml-3">
                            Подтвердить
                        </button>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length > 0 && !confirming"
                            class="mr-3">
                            {{ $t("account.2fa_recovery_codes_regenerate") }}
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length === 0 && !confirming"
                            class="mr-3">
                            {{ $t("account.2fa_recovery_codes_show") }}
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <SecondaryButton
                            v-if="confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling">
                            {{ $t("base.cancel") }}
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="disableTwoFactorAuthentication">
                        <DangerButton
                            v-if="!confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling">
                            {{ $t("base.disable") }}
                        </DangerButton>
                    </ConfirmsPassword>
                </div>
            </div>
        </template>
    </ActionSection>
</template>
