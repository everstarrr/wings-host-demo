<script setup>
defineProps({
    disabled: Boolean,
});
</script>

<template>
    <button
        type="button"
        :disabled="disabled"
        class="px-6 py-auto h-12 leading-4 font-medium rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out"
        :class="{ 'bg-neutral-500 hover:bg-neutral-500 focus:bg-neutral-500': disabled }">
        <slot />
    </button>
</template>
