<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import CreateTeamForm from "@/Pages/Teams/Chunks/CreateTeamForm.vue";
</script>

<template>
    <AppLayout
        :title="$t('groups.create')"
        :show-angle="false">
        <template #header>
            <h2 class="text-2xl text-neutral-300 leading-tight mt-24">
                {{ $t("groups.create") }}
            </h2>
        </template>

        <div class="bg-neutral-900 rounded-xl border border-neutral-700 max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div>
                <CreateTeamForm />
            </div>
        </div>
    </AppLayout>
</template>
