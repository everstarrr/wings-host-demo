<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import ConfirmationModal from "@/Components/ConfirmationModal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    team: Object,
});

const confirmingTeamDeletion = ref(false);
const form = useForm({});

const confirmTeamDeletion = () => {
    confirmingTeamDeletion.value = true;
};

const deleteTeam = () => {
    form.delete(route("teams.destroy", props.team), {
        errorBag: "deleteTeam",
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            {{ $t("groups.delete") }}
        </template>

        <template #description>
            {{ $t("groups.delete_notice_mini") }}
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-neutral-400">
                {{ $t("groups.delete_notice") }}
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmTeamDeletion">
                    {{ $t("groups.delete") }}
                </DangerButton>
            </div>

            <!-- Delete Team Confirmation Modal -->
            <ConfirmationModal
                :show="confirmingTeamDeletion"
                @close="confirmingTeamDeletion = false">
                <template #title>
                    {{ $t("groups.delete") }}
                </template>

                <template #content>
                    {{ $t("groups.delete_notice") }}
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingTeamDeletion = false">
                        {{ $t("base.cancel") }}
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteTeam">
                        {{ $t("groups.delete") }}
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>
