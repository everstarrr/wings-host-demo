<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    team: Object,
    permissions: Object,
});

const form = useForm({
    name: props.team.name,
});

const updateTeamName = () => {
    form.put(route("teams.update", props.team), {
        errorBag: "updateTeamName",
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateTeamName">
        <template #title>
            {{ $t("groups.name") }}
        </template>

        <template #description>
            {{ $t("groups.name_notice") }}
        </template>

        <template #form>
            <!-- Team Owner Information -->
            <div class="col-span-6">
                <InputLabel :value="$t('groups.owner')" />

                <div class="flex items-center mt-2">
                    <img
                        class="w-12 h-12 rounded-full object-cover"
                        :src="team.owner.profile_photo_url"
                        :alt="team.owner.name" />

                    <div class="ml-4 leading-tight">
                        <div class="text-neutral-300">{{ team.owner.f_name }}</div>
                        <div class="text-neutral-300 text-sm">{{ team.owner.u_name }} | {{ team.owner.email }}</div>
                    </div>
                </div>
            </div>

            <!-- Team Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="name"
                    :value="$t('groups.name')" />

                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    :disabled="!permissions.canUpdateTeam" />

                <InputError
                    :message="form.errors.name"
                    class="mt-2" />
            </div>
        </template>

        <template
            v-if="permissions.canUpdateTeam"
            #actions>
            <ActionMessage
                :on="form.recentlySuccessful"
                class="mr-3">
                {{ $t("base.saved") }}
            </ActionMessage>

            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing">
                {{ $t("base.save") }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
