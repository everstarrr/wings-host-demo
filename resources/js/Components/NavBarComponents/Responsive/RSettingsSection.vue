<script setup>
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { router } from "@inertiajs/vue3";

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div
        class="flex items-center px-4"
        v-if="$page.props.auth.user">
        <div
            v-if="$page.props.jetstream.managesProfilePhotos"
            class="shrink-0 mr-3">
            <img
                class="h-10 w-10 rounded-full object-cover"
                src="https://ui-avatars.com/api/?name='{{($page.props.auth.user.u_name)}}'&color=7F9CF5&background=EBF4FF"
                :alt="$page.props.auth.user.u_name" />
        </div>
    </div>

    <div class="space-y-1">
        <ResponsiveNavLink
            v-if="$page.props.jetstream.hasApiFeatures"
            :href="route('api-tokens.index')"
            :active="route().current('api-tokens.index')">
            {{ $t("account.api_keys") }}
        </ResponsiveNavLink>

        <!-- Authentication -->
        <form
            method="POST"
            @submit.prevent="logout">
            <ResponsiveNavLink as="button">
                {{ $t("auth.logout") }}
            </ResponsiveNavLink>
        </form>
    </div>
</template>

<style scoped></style>
