<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import GrayButton from "@/Components/GrayButton.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faDollar, faGear, faLink, faUser, faUsers } from "@fortawesome/free-solid-svg-icons";
import Payments from "@/Pages/Dash/Chunks/Payments.vue";
import Account from "@/Pages/Dash/Chunks/Account.vue";
import Referrals from "@/Pages/Dash/Chunks/Referrals.vue";
import Integrations from "@/Pages/Dash/Chunks/Integrations.vue";
import Summary from "@/Pages/Dash/Chunks/Summary.vue";
import { usePage } from "@inertiajs/vue3";
import { loadLanguageAsync } from "laravel-vue-i18n";

const page = usePage();

const props = defineProps({
    toShow: String,
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
    transactions: Object,
});

loadLanguageAsync(page.props.auth.user.locale);
</script>

<template>
    <AppLayout
        :title="$t('nav.dash')"
        :show-angle="true">
        <template #aside="{ showingAsideNav }">
            <g
                :class="{ block: showingAsideNav, hidden: !showingAsideNav }"
                class="lg:block">
                <GrayButton
                    class="my-2.5 float-left w-full inline-flex justify-between items-center"
                    :class="{ active: toShow === 'summary' }"
                    :link="route('dash')">
                    <p>Аккаунт</p>
                    <font-awesome-icon
                        :icon="faUser"
                        class="w-4 h-4 pr-4" />
                </GrayButton>
                <GrayButton
                    class="my-2.5 float-left w-full justify-between items-center"
                    :class="{ active: toShow === 'payments' }"
                    :link="route('dash.payments')">
                    <p>Платежи</p>
                    <font-awesome-icon
                        :icon="faDollar"
                        class="w-5 h-5 pr-4" />
                </GrayButton>
                <button
                    class="my-2.5 float-left w-full justify-between items-center inline-flex px-4 py-2 border-2 border-neutral-700 rounded-md font-medium text-s text-neutral-700 bg-neutral-900 tracking-widest"
                    :class="{ active: toShow === 'referrals' }"
                    disabled>
                    <!--@click="$page.props.toShow = 'referrals'"-->
                    <span>Рефералы</span>
                    <font-awesome-icon
                        :icon="faUsers"
                        class="w-5 h-5 pr-4" />
                </button>
                <button
                    class="my-2.5 float-left w-full justify-between items-center inline-flex px-4 py-2 border-2 border-neutral-700 rounded-md font-medium text-s text-neutral-700 bg-neutral-900 tracking-widest"
                    :class="{ active: toShow === 'integrations' }"
                    disabled>
                    <!--@click="$page.props.toShow = 'integrations'" -->
                    <span>Интеграции</span>
                    <font-awesome-icon
                        :icon="faLink"
                        class="w-5 h-5 pr-4" />
                </button>
                <GrayButton
                    class="my-2.5 float-left w-full justify-between items-center"
                    :class="{ active: toShow === 'account' }"
                    :link="route('dash.account')">
                    <p>Настройки</p>
                    <font-awesome-icon
                        :icon="faGear"
                        class="w-5 h-5 pr-4" />
                </GrayButton>
            </g>
        </template>

        <div class="lg:w-4/5 lg:px-9 float-right w-full mt-10 lg:mt-28 mb-6 lg:mb-10">
            <div class="bg-neutral-900 rounded-2xl border border-neutral-700">
                <Account
                    v-if="toShow === 'account'"
                    :confirms-two-factor-authentication="confirmsTwoFactorAuthentication"
                    :sessions="sessions" />
                <Payments
                    v-if="toShow === 'payments'"
                    :transactions="transactions" />
                <Referrals v-if="toShow === 'referrals'" />
                <Integrations v-if="toShow === 'integrations'" />
                <Summary v-if="toShow === 'summary'" />
            </div>
        </div>
    </AppLayout>
</template>

<style>
GrayButton font-awesome-icon {
    color: #d4d4d4;
}

GrayButton:hover font-awesome-icon {
    color: #171717;
}
</style>
