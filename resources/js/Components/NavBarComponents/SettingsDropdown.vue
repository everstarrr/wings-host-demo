<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { router } from "@inertiajs/vue3";

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <Dropdown
        align="right"
        width="48">
        <template #trigger>
            <button
                v-if="true"
                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-wings transition">
                <img
                    class="h-8 w-8 rounded-full object-cover"
                    :src="
                        'https://ui-avatars.com/api/?name=' +
                        $page.props.auth.user.u_name +
                        '&color=7F9CF5&background=EBF4FF'
                    "
                    :alt="$page.props.auth.user.f_name + ' | ' + $page.props.auth.user.u_name" />
            </button>

            <span
                v-else
                class="inline-flex rounded-md">
                <button
                    type="button"
                    class="inline-flex items-center px-3 py-2 text-md leading-4 font-['OpenSans-SemiBold'] rounded-md border-neutral-800 border text-neutral-400 bg-neutral-900 hover:text-white focus:outline-none focus:border-wings focus:text-white focus:border-2 transition ease-in-out duration-150 hover:border-white">
                    {{ $page.props.auth.user.f_name }} | {{ $page.props.auth.user.u_name }}

                    <svg
                        class="ml-2 -mr-0.5 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>
            </span>
        </template>

        <template #content>
            <!-- Account Management -->
            <div class="block px-4 py-2 text-xs text-gray-400">
                {{ $t("nav.manage_account") }}
            </div>

            <DropdownLink :href="route('profile.show')">
                {{ $t("account.settings") }}
            </DropdownLink>

            <DropdownLink
                v-if="$page.props.jetstream.hasApiFeatures"
                :href="route('api-tokens.index')">
                {{ $t("account.api_keys") }}
            </DropdownLink>

            <!-- Support -->
            <div class="block px-4 py-2 text-xs text-gray-400">
                {{ $t("support.support") }}
            </div>

            <DropdownLink :href="route('social.telegram', 'support')">
                {{ $t("support.tg") }}
            </DropdownLink>

            <DropdownLink :href="route('social.vk', 'support')">
                {{ $t("support.vk") }}
            </DropdownLink>

            <DropdownLink
                as="a"
                href="mailto:support@wingshost.ru">
                {{ $t("support.email") }}
            </DropdownLink>

            <div class="border-t border-neutral-400" />

            <!-- Authentication -->
            <form @submit.prevent="logout">
                <DropdownLink as="button">
                    {{ $t("auth.logout") }}
                </DropdownLink>
            </form>
        </template>
    </Dropdown>
</template>
