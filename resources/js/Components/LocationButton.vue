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
    enabled: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <button
        :type="type"
        :disabled="!enabled"
        :class="{ 'hover:border-neutral-700 hover:text-neutral-400': !enabled }"
        class="inline-flex px-4 py-2 border-2 border-neutral-700 rounded-md font-medium text-s text-neutral-400 bg-neutral-800 tracking-widest hover:border-white hover:text-white focus:border-white focus:text-white [&.active]:border-white [&.active]:text-white transition-all ease-in-out duration-150">
        <slot />
    </button>
</template>
