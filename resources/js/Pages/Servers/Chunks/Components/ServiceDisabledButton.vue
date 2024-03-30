<script setup></script>

<template>
    <button
        class="flex items-center justify-center rounded-lg shadow-none h-36"
        disabled>
        <slot name="image" />
        <span class="text-3xl md:text-2xl xl:text-3xl tracking-wider text-neutral-500 no-underline absolute">
            <slot name="title" />
        </span>
    </button>
</template>
