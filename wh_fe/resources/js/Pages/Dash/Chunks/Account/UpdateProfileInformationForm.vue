<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { ref } from "vue";
import { Link, router, useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { loadLanguageAsync } from "laravel-vue-i18n";
import DropdownButton from "@/Components/DropdownButton.vue";
import DropdownItem from "@/Components/DropdownItem.vue";

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: "PUT",
    f_name: props.user.f_name,
    l_name: props.user.l_name,
    //u_name: props.user.u_name, - disabled for security, only support can change u_name
    email: props.user.email,
    telegram_id: props.user.telegram_id,
    //locale: props.user.locale,
    //is_dark: props.user.is_dark,
    //is_formal: props.user.is_formal,
    photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route("user-profile-information.update"), {
        errorBag: "updateProfileInformation",
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};

const switchLocale = (locale) => {
    loadLanguageAsync(locale);

    router.put(
        route("locale.update"),
        {
            locale: locale,
        },
        {
            preserveScroll: true,
        },
    );
};

const switchFormal = (is_formal) => {
    router.put(
        route("formal.update"),
        {
            is_formal: is_formal,
        },
        {
            preserveScroll: true,
        },
    );
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (!photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    router.delete(route("current-user-photo.destroy"), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<template>
    <FormSection @submitted="updateProfileInformation">
        <template #title>
            {{ $t("account.account_info") }}
        </template>

        <template #description>
            {{ $t("account.account_info_notice") }}
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div
                v-if="$page.props.jetstream.managesProfilePhotos"
                class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview" />

                <InputLabel
                    for="photo"
                    :value="$t('account.photo')" />

                <!-- Current Profile Photo -->
                <div
                    v-show="!photoPreview"
                    class="mt-2">
                    <img
                        :src="user.profile_photo_url"
                        :alt="user.f_name"
                        class="rounded-full h-20 w-20 object-cover" />
                </div>

                <!-- New Profile Photo Preview -->
                <div
                    v-show="photoPreview"
                    class="mt-2">
                    <span
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'" />
                </div>

                <SecondaryButton
                    class="mt-2 mr-2"
                    type="button"
                    @click.prevent="selectNewPhoto">
                    {{ $t("account.select_photo") }}
                </SecondaryButton>

                <SecondaryButton
                    v-if="user.profile_photo_path"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto">
                    {{ $t("account.delete_photo") }}
                </SecondaryButton>

                <InputError
                    :message="form.errors.photo"
                    class="mt-2" />
            </div>

            <!-- First Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="f_name"
                    :value="$t('auth.f_name')" />
                <TextInput
                    id="f_name"
                    v-model="form.f_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="first_name" />
                <InputError
                    :message="form.errors.f_name"
                    class="mt-2" />
            </div>

            <!-- Last Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="l_name"
                    :value="$t('auth.l_name')" />
                <TextInput
                    id="l_name"
                    v-model="form.l_name"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="last_name" />
                <InputError
                    :message="form.errors.l_name"
                    class="mt-2" />
            </div>

            <!-- Username -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="u_name"
                    :value="$t('auth.u_name')" />
                <TextInput
                    id="u_name"
                    v-model="$page.props.auth.user.u_name"
                    type="text"
                    class="mt-1 block w-full text-neutral-500"
                    autocomplete="username"
                    :is_disabled="true" />
                <InputError
                    :message="form.errors.u_name"
                    class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="email"
                    :value="$t('auth.email')" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    autocomplete="email" />
                <InputError
                    :message="form.errors.email"
                    class="mt-2" />

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
                    <p class="text-sm mt-2 text-white">
                        Your email address is unverified.

                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="underline text-sm text-neutral-400 hover:text-neutral-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-wings focus:ring-offset-neutral-800"
                            @click.prevent="sendEmailVerification">
                            Click here to re-send the verification email.
                        </Link>
                    </p>

                    <div
                        v-show="verificationLinkSent"
                        class="mt-2 font-medium text-sm text-green-400">
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>

            <!-- Telegram ID -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="telegram_id"
                    :value="$t('auth.telegram_id')" />
                <TextInput
                    id="telegram_id"
                    v-model="form.telegram_id"
                    type="text"
                    class="mt-1 block w-full"
                    autocomplete="TelegramID" />
                <InputError
                    :message="form.errors.telegram_id"
                    class="mt-2" />
            </div>

            <!-- Locale -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="locale"
                    :value="$t('account.locale')" />
                <Dropdown
                    width="full"
                    align="left">
                    <template #trigger>
                        <DropdownButton>
                            {{ $t("locales." + $page.props.auth.user.locale) }}
                        </DropdownButton>
                    </template>

                    <template #content>
                        <DropdownItem
                            :active="$page.props.auth.user.locale"
                            :func="switchLocale"
                            :entries="[
                                ['ru', 'locales.ru'],
                                ['en', 'locales.en'],
                            ]" />
                    </template>
                </Dropdown>
            </div>
        </template>

        <template #actions>
            <ActionMessage
                :on="form.recentlySuccessful"
                class="mr-3">
                {{ $t("base.saved") }}
            </ActionMessage>

            <button
                :disabled="form.processing"
                type="button"
                class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block mb-3">
                Сохранить
            </button>
        </template>
    </FormSection>
</template>
