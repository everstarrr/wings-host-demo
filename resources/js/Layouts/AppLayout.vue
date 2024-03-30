<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { computed, ref } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import Banner from "@/Components/Banner.vue";
import NavBar from "@/Components/NavBar.vue";
import { faAngleRight } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import Footer from "@/Components/Footer.vue";
//import { loadLanguageAsync } from 'laravel-vue-i18n';

defineProps({
    title: String,
    showAngle: Boolean,
});

const showingNavigationDropdown = ref(false);
const showingAsideNav = ref(false);

const logout = () => {
    router.post(route("logout"));
};

const page = usePage();
const user = computed(() => page.props.auth.user);

//loadLanguageAsync(page.props.auth.user.locale);
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-neutral-950 overflow-y-hidden">
            <NavBar />

            <header
                v-if="$slots.header && !$slots.aside"
                class="bg-neutral-950 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Side Panel -->
            <button
                class="p-2 fixed text-neutral-400 focus:outline-none focus:text-white lg:hidden ml-4 mt-20 z-20 transition duration-300"
                :class="{ 'transition-transform translate-x-[80vw] duration-300': showingAsideNav }"
                v-if="showAngle"
                @click="showingAsideNav = !showingAsideNav">
                <font-awesome-icon
                    :icon="faAngleRight"
                    size="2xl"
                    style="color: #d4d4d4"
                    class="transition-transform duration-300"
                    :class="{ 'rotate-180': showingAsideNav }" />
            </button>
            <nav
                id="asidePanel"
                v-if="$slots.aside"
                class="bg-neutral-900 lg:border-neutral-700 lg:border-t lg:w-1/5 w-4/5 h-full fixed overflow-y-scroll float-left hide-scrollbar lg:mt-20 mt-16 z-10 duration-300 lg:translate-x-0 -translate-x-[100%]"
                :class="{ 'transition-transform translate-x-0 duration-300': showingAsideNav }">
                <div class="max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                    <slot
                        name="aside"
                        :showingAsideNav="showingAsideNav" />
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <slot
                name="nofooter"
                class="hidden" />
        </div>
        <Footer v-if="!$slots.aside && !$slots.nofooter" />
    </div>
</template>

<style></style>
