<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
    f_name: "",
    l_name: "",
    u_name: "",
    email: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <Head :title="$t('auth.register')" />

    <AuthenticationCard class="pb-20">
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <form @submit.prevent="submit">
            <div>
                <InputLabel
                    for="f_name"
                    :value="$t('auth.f_name')" />
                <TextInput
                    id="f_name"
                    v-model="form.f_name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="first_name" />
                <InputError
                    class="mt-2"
                    :message="form.errors.f_name" />
            </div>

            <div>
                <InputLabel
                    class="mt-3"
                    for="l_name"
                    :value="$t('auth.l_name')" />
                <TextInput
                    id="l_name"
                    v-model="form.l_name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="last_name" />
                <InputError
                    class="mt-2"
                    :message="form.errors.l_name" />
            </div>

            <div>
                <InputLabel
                    class="mt-3"
                    for="u_name"
                    :value="$t('auth.u_name')" />
                <TextInput
                    id="u_name"
                    v-model="form.u_name"
                    :placeholder="$t('auth.u_name_placeholder')"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username" />
                <InputError
                    class="mt-2"
                    :message="form.errors.u_name" />
            </div>

            <div class="mt-4">
                <InputLabel
                    class="mt-3"
                    for="email"
                    :value="$t('auth.email')" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="email" />
                <InputError
                    class="mt-2"
                    :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel
                    class="mt-3"
                    for="password"
                    :value="$t('auth.password_field')" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password" />
                <InputError
                    class="mt-2"
                    :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    class="mt-3"
                    for="password_confirmation"
                    :value="$t('auth.confirm_password')" />
                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password" />
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation" />
            </div>

            <div
                v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature"
                class="mt-4">
                <InputLabel for="terms">
                    <div class="flex items-center">
                        <Checkbox
                            id="terms"
                            v-model:checked="form.terms"
                            name="terms"
                            required />

                        <div class="ml-2">
                            {{ $t("auth.terms_1") }}
                            <a
                                target="_blank"
                                :href="route('articles.terms-of-service')"
                                class="underline text-sm text-neutral-400 hover:text-neutral-100 rounded-md"
                                >{{ $t("auth.terms_2") }}</a
                            >
                            &
                            <a
                                target="_blank"
                                :href="route('articles.privacy-policy')"
                                class="underline text-sm text-neutral-400 hover:text-neutral-100 rounded-md"
                                >{{ $t("auth.terms_3") }}</a
                            >
                        </div>
                    </div>
                    <InputError
                        class="mt-2"
                        :message="form.errors.terms" />
                </InputLabel>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('login')"
                    class="underline text-sm text-neutral-400 hover:text-neutral-100 rounded-md">
                    {{ $t("auth.already_registered") }}
                </Link>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    {{ $t("auth.register") }}
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
