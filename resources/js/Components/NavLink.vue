<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active
        ? "inline-flex items-center px-1 pt-1 border-b-2 border-wings text-lg font-medium" +
              " leading-5 text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out"
        : "inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-lg font-medium leading-5" +
              " text-neutral-400 hover:text-white hover:border-wings " +
              " focus:outline-none focus:text-neutral-400 focus:border-neutral-400 transition duration-150 ease-in-out";
});
</script>

<template>
    <Link
        :href="href"
        :class="classes">
        <slot />
    </Link>
</template>
