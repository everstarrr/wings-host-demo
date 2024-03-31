<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
defineProps({
    type: {
        type: String,
        default: "button",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-neutral-800 border border-neutral-500 rounded-lg font-semibold text-xs text-neutral-300 uppercase tracking-widest shadow-sm hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-wings focus:ring-offset-2 focus:ring-offset-neutral-800 disabled:opacity-25 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
