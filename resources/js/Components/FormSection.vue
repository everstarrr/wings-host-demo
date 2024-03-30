<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "./SectionTitle.vue";

defineEmits(["submitted"]);

const hasActions = computed(() => !!useSlots().actions);
</script>

<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <SectionTitle>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </SectionTitle>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="$emit('submitted')">
                <div
                    class="px-4 py-5 bg-neutral-800 sm:p-6 shadow border-t border-x border-neutral-700"
                    :class="hasActions ? 'sm:rounded-tl-lg sm:rounded-tr-lg' : 'sm:rounded-lg'">
                    <div class="grid grid-cols-6 gap-6">
                        <slot name="form" />
                    </div>
                </div>

                <div
                    v-if="hasActions"
                    class="flex items-center justify-end px-4 py-3 bg-neutral-800 text-right sm:px-6 shadow sm:rounded-bl-lg sm:rounded-br-lg border-b border-x border-neutral-700">
                    <slot name="actions" />
                </div>
            </form>
        </div>
    </div>
</template>
