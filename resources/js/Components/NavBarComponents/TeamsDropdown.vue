<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { router } from "@inertiajs/vue3";

defineProps({
    balance: Object,
});

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        },
    );
};
</script>

<template>
    <Dropdown
        v-if="$page.props.jetstream.hasTeamFeatures"
        align="right"
        width="60">
        <template #trigger="{ open }">
            <span class="inline-flex rounded-md">
                <button
                    type="button"
                    class="inline-flex items-center px-3 py-2 text-md leading-4 font-['OpenSans-SemiBold'] rounded-md border-neutral-800 border text-neutral-400 bg-transparent hover:text-white focus:outline-none focus:border-wings focus:text-white focus:border-2 transition ease-in-out duration-150 hover:border-white">
                    {{ $page.props.auth.user.current_team.name }}

                    <svg
                        class="ml-2 -mr-0.5 h-4 w-4"
                        :class="{ 'rotate-180': open }"
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
            <div class="w-60">
                <!-- Team Management -->
                <template v-if="$page.props.jetstream.hasTeamFeatures">
                    <div class="px-4 py-2 text-xs text-neutral-400">
                        {{ $t("groups.groups") }}
                    </div>

                    <!-- Team Settings -->
                    <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                        {{ $t("groups.settings") }}
                    </DropdownLink>

                    <DropdownLink
                        v-if="$page.props.jetstream.canCreateTeams"
                        :href="route('teams.create')">
                        {{ $t("groups.create") }}
                    </DropdownLink>

                    <!-- Team Switcher -->
                    <template v-if="$page.props.auth.user.all_teams.length > 1">
                        <div class="border-t border-neutral-400" />

                        <div class="px-4 py-2 text-xs text-gray-400">
                            {{ $t("groups.switch") }}
                        </div>

                        <template
                            v-for="team in $page.props.auth.user.all_teams"
                            :key="team.id">
                            <form @submit.prevent="switchToTeam(team)">
                                <DropdownLink as="button">
                                    <div class="flex items-center">
                                        <svg
                                            v-if="team.id === $page.props.auth.user.current_team_id"
                                            class="mr-2 h-5 w-5 text-green-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>

                                        <div>{{ team.name }}</div>
                                        <div
                                            v-if="
                                                balance &&
                                                (!team.use_main_amount || team.id === balance.personal.team_id)
                                            "
                                            class="ml-3">
                                            {{ balance.all[team.id].amount }}
                                            {{ balance.all[team.id].currency }}
                                        </div>
                                        <div
                                            v-else-if="!balance"
                                            class="ml-3 animate-pulse">
                                            Загрузка...
                                        </div>
                                    </div>
                                </DropdownLink>
                            </form>
                        </template>
                    </template>
                </template>
            </div>
        </template>
    </Dropdown>
</template>

<style scoped></style>
