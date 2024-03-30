<script setup>
import NavLink from "@/Components/NavLink.vue";
import { faChevronDown } from "@fortawesome/free-solid-svg-icons";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
</script>

<template>
    <NavLink
        :href="route('dash')"
        :active="route().current('dash')">
        {{ $t("nav.dash") }}
    </NavLink>

    <NavLink
        :href="route('dash.servers')"
        :active="route().current('dash.servers') || route().current('dash.servers.server*')">
        {{ $t("nav.servers") }}
    </NavLink>

    <NavLink
        :href="route('articles')"
        :active="route().current('articles')">
        {{ $t("nav.articles") }}
    </NavLink>

    <Dropdown
        align="right"
        width="48"
        class="relative">
        <template #trigger="{ open }">
            <button
                type="button"
                class="inline-flex items-center px-1 pt-6 pb-5 lg:pt-8 lg:pb-7 border-b-2 border-transparent text-lg font-medium leading-5 text-neutral-400 hover:text-white hover:border-wings focus:outline-none focus:text-white focus:border-wings transition duration-150 ease-in-out">
                Услуги
                <font-awesome-icon
                    :class="{ 'rotate-180': open }"
                    :icon="faChevronDown"
                    class="w-3.5 h-3.5 ml-2 transition-transform duration-150" />
            </button>
        </template>
        <template #content>
            <div class="block px-4 py-2 text-xs text-neutral-400">
                {{ $t("services.games") }}
            </div>
            <DropdownLink :href="route('services.minecraft')"> Minecraft</DropdownLink>
        </template>
    </Dropdown>
</template>
