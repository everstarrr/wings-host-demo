<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? "block w-full pl-3 pr-4 py-2 border-l-4 border-wings text-left text-base" +
              " font-medium text-neutral-300 bg-violet-900/50 focus:outline-none" +
              " focus:text-neutral-200 focus:bg-violet-900 focus:border-wings-light transition duration-150 ease-in-out"
        : "block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-neutral-400" +
              " hover:text-neutral-200 hover:bg-neutral-700 hover:border-neutral-600 focus:outline-none " +
              " focus:bg-neutral-700 focus:border-neutral-600 focus:text-neutral-200 transition duration-150" +
              " ease-in-out";
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            :class="classes"
            class="w-full text-left">
            <slot />
        </button>

        <Link
            v-else
            :href="href"
            :class="classes">
            <slot />
        </Link>
    </div>
</template>
