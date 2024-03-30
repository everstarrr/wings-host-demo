<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import AppLogoLeft from "@/Components/AppLogoLeft.vue";
import AppLogoRight from "@/Components/AppLogoRight.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { computed, ref } from "vue";
import DefaultBar from "@/Components/NavBarComponents/DefaultBar.vue";
import PaymentsDropdown from "@/Components/NavBarComponents/PaymentsDropdown.vue";
import AuthButtons from "@/Components/NavBarComponents/AuthButtons.vue";
import TeamsDropdown from "@/Components/NavBarComponents/TeamsDropdown.vue";
import SettingsDropdown from "@/Components/NavBarComponents/SettingsDropdown.vue";
import Hamburger from "@/Components/NavBarComponents/Hamburger.vue";
import RDefaultSection from "@/Components/NavBarComponents/Responsive/RDefaultSection.vue";
import RServicesSection from "@/Components/NavBarComponents/Responsive/RServicesSection.vue";
import RTeamManagementSection from "@/Components/NavBarComponents/Responsive/RTeamManagementSection.vue";
import RSettingsSection from "@/Components/NavBarComponents/Responsive/RSettingsSection.vue";

defineProps({
    user: Object,
});

const showingNavigationDropdown = ref(false);

const balance = ref(null);
const get_balance = async () => {
    await axios
        .get(route("api.v1.payments.balance"))
        .then(function (response) {
            balance.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                balance.value = err;
            }
        });

    // TODO: use it in every api calls, make api_v1 global
    /*await api_v1.get(route("api.v1.payments.balance")).then(function(response) {
        balance.value = response.data;
    }).catch((err) => {
        if (!axios.isCancel(err)) {
            balance.value = err;
        }
    })*/
};

setTimeout(function () {
    get_balance();
}, 100);

const logout = () => {
    router.post(route("logout"));
};

const page = usePage();
const user = computed(() => page.props.auth.user);
</script>

<template>
    <nav class="bg-neutral-950 bg-opacity-50 backdrop-blur-md border-b border-neutral-700 fixed w-full z-50">
        <!-- Primary Navigation Menu -->
        <div class="max-w-10xl mx-auto px-8 sm:px-6 lg:-ml-0 -ml-4 border-b border-neutral-900">
            <!--All panel Flexbox -->
            <div class="flex justify-between lg:h-20 h-16">
                <!--Left side panel flexbox -->
                <div class="flex space-x-12 z-50">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <!--Logo Flexbox -->
                        <Link :href="route('home')">
                            <AppLogoLeft class="xs:inline-block w-auto lg:h-14 h-12" />
                            <AppLogoRight class="xs:inline-block w-auto h-14 ml-1.5 hidden" />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px lg:flex justify-center font-medium">
                        <DefaultBar />
                    </div>
                </div>

                <!-- Auth Buttons -->
                <div
                    v-if="!$page.props.auth.user"
                    class="-mr-5 p-6 text-right mt-1.5">
                    <div v-if="!$page.props.auth.user">
                        <AuthButtons />
                    </div>
                </div>

                <div
                    class="hidden lg:flex sm:items-center sm:ml-6"
                    v-if="$page.props.auth.user">
                    <!-- Payments dropdown -->
                    <div class="ml-3 relative text-lg font-medium">
                        <PaymentsDropdown :balance="balance" />
                    </div>

                    <div class="ml-3 relative">
                        <!-- Teams Dropdown -->
                        <TeamsDropdown :balance="balance" />
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="ml-3 relative">
                        <SettingsDropdown />
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center lg:hidden">
                    <Hamburger @show="showingNavigationDropdown = !showingNavigationDropdown" />
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <transition
            enter-active-class="transition-all duration-150 origin-top"
            enter-from-class="transform max-h-0 opacity-0"
            enter-to-class="transform max-h-screen opacity-100"
            leave-active-class="transition-all duration-150"
            leave-from-class="transform max-h-screen opacity-100"
            leave-to-class="transform max-h-0 opacity-0">
            <div v-if="showingNavigationDropdown">
                <!-- Default -->
                <div
                    class="py-1 border-b border-neutral-700 space-y-1"
                    v-if="$page.props.auth.user">
                    <RDefaultSection :balance="balance" />
                </div>

                <!-- Services -->
                <div class="py-1 border-b border-neutral-700">
                    <RServicesSection />
                </div>

                <!-- Team Management -->
                <div
                    v-if="$page.props.jetstream.hasTeamFeatures && $page.props.auth.user"
                    class="py-1 border-b border-neutral-700">
                    <RTeamManagementSection />
                </div>

                <!-- Responsive Settings Options -->
                <div
                    class="py-1"
                    v-if="$page.props.auth.user">
                    <RSettingsSection />
                </div>
            </div>
        </transition>
    </nav>
</template>
