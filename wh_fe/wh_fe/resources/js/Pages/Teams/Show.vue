<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import DeleteTeamForm from "@/Pages/Teams/Chunks/DeleteTeamForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TeamMemberManager from "@/Pages/Teams/Chunks/TeamMemberManager.vue";
import UpdateTeamNameForm from "@/Pages/Teams/Chunks/UpdateTeamNameForm.vue";

defineProps({
    team: Object,
    availableRoles: Array,
    permissions: Object,
});
</script>

<template>
    <AppLayout
        :title="$t('groups.settings')"
        :show-angle="false">
        <template #header>
            <h2 class="text-2xl text-neutral-300 leading-tight mt-24 font-['OpenSans-Medium']">
                {{ $t("groups.settings") }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 bg-neutral-900 rounded-xl border border-neutral-700">
                <UpdateTeamNameForm
                    :team="team"
                    :permissions="permissions" />

                <TeamMemberManager
                    class="mt-10 sm:mt-0"
                    :team="team"
                    :available-roles="availableRoles"
                    :user-permissions="permissions" />

                <template v-if="permissions.canDeleteTeam && !team.personal_team">
                    <SectionBorder />

                    <DeleteTeamForm
                        class="mt-10 sm:mt-0"
                        :team="team" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
