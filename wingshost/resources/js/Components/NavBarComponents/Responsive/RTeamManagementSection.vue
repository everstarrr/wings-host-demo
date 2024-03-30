<script setup>
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { router } from "@inertiajs/vue3";

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
    <div class="block px-4 py-2 text-base font-medium text-neutral-500">
        {{ $t("groups.groups") }}
    </div>

    <!-- Team Settings -->
    <ResponsiveNavLink
        :href="route('teams.show', $page.props.auth.user.current_team)"
        :active="route().current('teams.show')">
        <span class="ml-4">{{ $t("groups.settings") }}</span>
    </ResponsiveNavLink>

    <ResponsiveNavLink
        v-if="$page.props.jetstream.canCreateTeams"
        :href="route('teams.create')"
        :active="route().current('teams.create')">
        <span class="ml-4">{{ $t("groups.create") }}</span>
    </ResponsiveNavLink>

    <!-- Team Switcher -->
    <div v-if="$page.props.auth.user.all_teams.length > 1">
        <div class="border-t border-neutral-600" />

        <div class="block px-4 py-2 text-xs text-gray-400">
            {{ $t("groups.switch") }}
        </div>

        <div
            v-for="team in $page.props.auth.user.all_teams"
            :key="team.id">
            <form @submit.prevent="switchToTeam(team)">
                <ResponsiveNavLink as="button">
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
                    </div>
                </ResponsiveNavLink>
            </form>
        </div>
    </div>
</template>

<style scoped></style>
