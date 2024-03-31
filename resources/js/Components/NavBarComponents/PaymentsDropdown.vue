<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

defineProps({
    balance: Object,
});
</script>

<template>
    <Dropdown
        align="right"
        width="48">
        <template #trigger>
            <button
                v-if="$page.props.jetstream.managesProfilePhotos"
                class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-neutral-300 transition">
                <img
                    class="h-8 w-8 rounded-full object-cover"
                    :src="$page.props.auth.user.profile_photo_url"
                    :alt="$page.props.auth.user.f_name + ' | ' + $page.props.auth.user.u_name" />
            </button>

            <div
                v-else
                class="inline-flex rounded-md">
                <button
                    type="button"
                    class="inline-flex items-center px-3 py-2 text-md leading-4 font-['OpenSans-SemiBold'] rounded-md border-transparent border text-neutral-400 bg-transparent hover:text-white focus:outline-none focus:text-white focus:border-2 transition ease-in-out duration-150"
                    :class="{ 'animate-pulse': !balance }">
                    <template v-if="balance">
                        <span
                            v-if="
                                $page.props.auth.user.all_teams.length === 1 ||
                                $page.props.auth.user.amount_ui_mode === 'personal'
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
                </button>
            </div>
        </template>

        <template #content>
            <!-- Payments management -->
            <div class="block px-4 py-2 text-xs text-neutral-400">
                {{ $t("payments.payments") }}
            </div>

            <DropdownLink :href="route('dash.payments')">
                {{ $t("payments.top_up") }}
            </DropdownLink>

            <!--<div class="border-t border-neutral-400" />-->
        </template>
    </Dropdown>
</template>
