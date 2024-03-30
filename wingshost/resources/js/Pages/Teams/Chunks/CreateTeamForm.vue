<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { useForm } from "@inertiajs/vue3";
import FormSection from "@/Components/FormSection.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import Checkbox from "@/Components/Checkbox.vue";

const form = useForm({
    name: "",
});

const createTeam = () => {
    form.post(route("teams.store"), {
        errorBag: "createTeam",
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="createTeam">
        <template #title>
            {{ $t("groups.settings") }}
        </template>

        <template #description>
            {{ $t("groups.create_notice") }}
        </template>

        <template #form>
            <div class="col-span-6">
                <InputLabel :value="$t('groups.owner')" />

                <div class="flex items-center mt-2">
                    <img
                        class="object-cover w-12 h-12 rounded-full"
                        :src="$page.props.auth.user.profile_photo_url"
                        :alt="$page.props.auth.user.f_name" />

                    <div class="ml-4 leading-tight">
                        <div class="text-neutral-300">{{ $page.props.auth.user.f_name }}</div>
                        <div class="text-sm text-neutral-300">
                            {{ $page.props.auth.user.u_name }} | {{ $page.props.auth.user.email }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-span-6 sm:col-span-4">
                <InputLabel
                    for="name"
                    :value="$t('groups.name')" />
                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="block w-full mt-1"
                    autofocus />
                <InputError
                    :message="form.errors.name"
                    class="mt-2" />
            </div>

            <div class="col-span-6 flex items-center space-x-2">
                <Checkbox class="h-5 w-5" />
                <span class="text-neutral-300">Использовать баланс главной группы</span>
            </div>
        </template>

        <template #actions>
            <PrimaryButton
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing">
                {{ $t("base.create") }}
            </PrimaryButton>
        </template>
    </FormSection>
</template>
