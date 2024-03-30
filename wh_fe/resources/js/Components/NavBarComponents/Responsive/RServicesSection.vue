<script setup>
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
</script>

<template>
    <span
        class="block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-neutral-500 cursor-default"
        >{{ $t("nav.services") }}</span
    >
    <ResponsiveNavLink
        :href="route('services.minecraft')"
        :active="route().current('services.minecraft')">
        <span class="ml-4">Minecraft</span>
    </ResponsiveNavLink>

    <ResponsiveNavLink
        class="border-neutral-700 border-t pt-1"
        v-if="!$page.props.auth.user"
        :href="route('register')"
        :active="route().current('register')">
        {{ $t("auth.register") }}
    </ResponsiveNavLink>

    <ResponsiveNavLink
        v-if="!$page.props.auth.user"
        :href="route('login')"
        :active="route().current('login')">
        {{ $t("auth.login") }}
    </ResponsiveNavLink>
</template>
