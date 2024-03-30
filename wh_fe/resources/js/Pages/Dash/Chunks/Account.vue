<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import DeleteUserForm from "@/Pages/Dash/Chunks/Account/DeleteUserForm.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Dash/Chunks/Account/LogoutOtherBrowserSessionsForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TwoFactorAuthenticationForm from "@/Pages/Dash/Chunks/Account/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Dash/Chunks/Account/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Dash/Chunks/Account/UpdateProfileInformationForm.vue";

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <h2 class="font-medium text-2xl text-white leading-tight lg:text-left text-center lg:mt-8 mt-14 mx-8">
        {{ $t("account.settings") }}
    </h2>

    <div>
        <div class="mx-auto sm:space-y-2 my-8 sm:px-8">
            <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                <UpdateProfileInformationForm :user="$page.props.auth.user" />

                <SectionBorder />
            </div>

            <div v-if="$page.props.jetstream.canUpdatePassword">
                <UpdatePasswordForm />

                <SectionBorder />
            </div>

            <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                <TwoFactorAuthenticationForm :requires-confirmation="confirmsTwoFactorAuthentication" />

                <SectionBorder />
            </div>

            <LogoutOtherBrowserSessionsForm :sessions="sessions" />

            <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                <SectionBorder />

                <DeleteUserForm />
            </template>
        </div>
    </div>
</template>
