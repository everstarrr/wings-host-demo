<script setup>
defineProps({
    showingNavigationDropdown: false,
});

const emit = defineEmits(["show"]);
</script>

<template>
    <button
        class="inline-flex items-center justify-center p-2 rounded-md text-neutral-400 focus:outline-none focus:text-white transition duration-150 ease-in-out"
        @click="
            showingNavigationDropdown = !showingNavigationDropdown;
            emit('show');
        ">
        <svg
            class="h-8 w-8"
            stroke="currentColor"
            fill="none"
            viewBox="0 0 24 24">
            <path
                :class="{
                    hidden: showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
            <path
                :class="{
                    hidden: !showingNavigationDropdown,
                }"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</template>
