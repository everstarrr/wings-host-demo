<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    link: String,
    hot: Boolean,
    free: Boolean,
});
</script>

<template>
    <button
        v-if="!link"
        class="scale-100 p-5 bg-cover rounded-lg shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 ease-in-out duration-150 flex flex-col text-left space-y-4 py-6"
        :class="{ 'outline outline-4 outline-red-600': props.hot || props.free }">
        <span
            class="border rounded-2xl text-center w-max text-sm px-3 py-1"
            v-if="!hot && !free"
            ><slot name="type"
        /></span>
        <g
            class="flex justify-between w-full"
            v-if="hot || free">
            <span class="border rounded-2xl text-center w-max text-sm px-3 py-1"><slot name="type" /></span>
            <span
                v-if="hot"
                class="rounded-bl-lg rounded-tr-lg text-center text-sm bg-red-600 bottom-6 -mr-5 px-4 relative py-1"
                >HOT!</span
            >
            <span
                v-if="free"
                class="rounded-bl-lg rounded-tr-lg text-center text-sm bg-red-600 bottom-6 -mr-5 px-4 relative py-1"
                >FREE!</span
            >
        </g>
        <span class="text-xl samsung-text-bold text-white"><slot name="title" /></span>
        <span class="text-neutral-300 text-sm leading-relaxed"><slot name="rate" /></span>
    </button>

    <Link
        v-else
        :href="link"
        class="scale-100 p-5 bg-cover rounded-lg shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 ease-in-out duration-150 flex flex-col text-left space-y-4 py-6"
        :class="{ 'outline outline-4 outline-red-600': props.hot || props.free }">
        <span
            class="border rounded-2xl text-center w-max text-sm px-3 py-1"
            v-if="!hot && !free"
            ><slot name="type"
        /></span>
        <g
            class="flex justify-between w-full"
            v-if="hot || free">
            <span class="border rounded-2xl text-center w-max text-sm px-3 py-1"><slot name="type" /></span>
            <span
                v-if="hot"
                class="rounded-bl-lg rounded-tr-lg text-center text-sm bg-red-600 bottom-6 -mr-5 px-4 relative py-1"
                >HOT!</span
            >
            <span
                v-if="free"
                class="rounded-bl-lg rounded-tr-lg text-center text-sm bg-red-600 bottom-6 -mr-5 px-4 relative py-1"
                >FREE!</span
            >
        </g>
        <span class="text-xl samsung-text-bold text-white"><slot name="title" /></span>
        <span class="text-neutral-300 text-sm leading-relaxed"><slot name="rate" /></span>
    </Link>
</template>
