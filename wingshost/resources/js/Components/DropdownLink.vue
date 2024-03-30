<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
    as: String,
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            class="block w-full px-4 py-2 text-left text-sm leading-5 text-neutral-300 hover:bg-neutral-900 focus:outline-none focus:bg-neutral-950 transition duration-150 ease-in-out">
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-neutral-300 hover:bg-neutral-800 focus:outline-none focus:bg-neutral-950 transition duration-150 ease-in-out">
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="block px-4 py-2 text-sm leading-5 text-neutral-300 hover:bg-neutral-900 focus:outline-none focus:bg-neutral-950 transition duration-150 ease-in-out">
            <slot />
        </Link>
    </div>
</template>
