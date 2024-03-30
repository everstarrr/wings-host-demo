<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        class="scale-100 items-center justify-center ring-1 ring-inset ring-white/5 rounded-lg shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 hover:outline hover:outline-3 hover:outline-wings hover:opacity-80 h-36 relative">
        <slot name="image" />
        <!-- for v-lazy-image -->
        <p class="text-3xl md:text-2xl xl:text-3xl tracking-wider text-white no-underline absolute">
            <slot name="title" />
            <!-- for title -->
        </p>
    </Link>
</template>
