<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: Object,
    bg: Object,
});
</script>

<template>
    <Link
        :href="href"
        :class="bg"
        class="p-5 bg-cover ring-1 ring-inset ring-white/5 rounded-lg motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-wings">
        <div>
            <span class="border rounded-2xl text-center text-sm px-3 py-1">
                <slot name="category" />
            </span>

            <p class="mt-4 text-xl samsung-text-bold text-white">
                <slot name="title" />
            </p>

            <p class="mt-4 text-neutral-300 text-sm leading-relaxed">
                <slot name="description" />
            </p>
        </div>
    </Link>
</template>
