<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    type: {
        type: String,
        default: "submit",
    },
    link: {
        type: String,
        default: null,
    },
});
</script>

<template>
    <button
        v-if="!link"
        :type="type"
        class="inline-flex px-4 py-2 border-2 border-neutral-300 rounded-md font-medium text-s text-neutral-300 bg-neutral-900 tracking-widest hover:bg-neutral-300 hover:text-neutral-900 focus:bg-neutral-300 focus:text-neutral-900 [&.active]:bg-neutral-300 [&.active]:text-neutral-900 transition-all ease-in-out duration-150">
        <slot />
    </button>
    <Link
        v-else
        :href="link"
        class="inline-flex px-4 py-2 border-2 border-neutral-300 rounded-md font-medium text-s text-neutral-300 bg-neutral-900 tracking-widest hover:bg-neutral-300 hover:text-neutral-900 focus:bg-neutral-300 focus:text-neutral-900 [&.active]:bg-neutral-300 [&.active]:text-neutral-900 transition-all ease-in-out duration-150">
        <slot />
    </Link>
</template>
