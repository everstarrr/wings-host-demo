<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
defineProps({
    type: {
        type: String,
        default: "submit",
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-neutral-200 border border-transparent rounded-md font-['OpenSans-SemiBold'] text-xs text-neutral-800 uppercase tracking-widest hover:bg-white focus:bg-white active:bg-neutral-300 focus:outline-none focus:ring-2 focus:ring-wings focus:ring-offset-neutral-800 focus:ring-offset-2 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
