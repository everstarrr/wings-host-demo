<script setup>
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    balance: Object,
});
</script>

<template>
    <ResponsiveNavLink
        :href="route('dash')"
        :active="route().current('dash')">
        {{ $t("nav.dash") }}
    </ResponsiveNavLink>

    <ResponsiveNavLink
        :href="route('dash.servers')"
        :active="route().current('dash.servers')">
        {{ $t("nav.servers") }}
    </ResponsiveNavLink>

    <ResponsiveNavLink
        :href="route('articles')"
        :active="route().current('articles')">
        {{ $t("nav.articles") }}
    </ResponsiveNavLink>

    <ResponsiveNavLink
        :href="route('dash.payments')"
        :active="route().current('dash.payments')">
        <template v-if="balance">
            <span
                v-if="
                    $page.props.auth.user.all_teams.length === 1 || $page.props.auth.user.amount_ui_mode === 'personal'
                ">
                {{ $t("payments.balance") }}:
                {{ balance.personal.amount }}
                {{ balance.personal.currency }}
            </span>
            <span v-else-if="$page.props.auth.user.amount_ui_mode === 'owned'">
                {{ $t("payments.balance_sum") }}
            </span>
            <span v-else-if="$page.props.auth.user.amount_ui_mode === 'all'">
                {{ $t("payments.balance_sum") }}
            </span>
            <span v-else-if="$page.props.auth.user.amount_ui_mode === 'team'">
                {{ $t("payments.group_balance") }}:
                {{ balance.current.amount }}
                {{ balance.current.currency }}
            </span>
            <span v-else>
                {{ $t("payments.balance") }}:
                {{ balance.personal.amount }}
                {{ balance.personal.currency }}
            </span>
        </template>
        <template v-else>
            <span>{{ $t("payments.balance") }}: N RUB</span>
        </template>
    </ResponsiveNavLink>
</template>

<style scoped></style>
