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
        class="block raise md:inline-flex justify-center align-middle px-4 py-2 bg-gradient-to-r from-wings from-50% to-wings-light to-100% bg-size-200 bg-pos-90 rounded-3xl font-medium text-neutral-950 tracking-wide transition-all border border-transparent hover:opacity-90 focus:outline-none focus:opacity-80 active:bg-gray-300 ease-in-out duration-150 text-lg">
        <slot />
    </button>
</template>

<style scoped>
.raise:hover,
.raise:focus {
    box-shadow: 0 0.5em 0.5em -0.4em #7c00db;
    transform: translateY(-0.25em);
}
</style>
