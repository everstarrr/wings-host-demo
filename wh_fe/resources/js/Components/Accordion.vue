<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="text-white mx-auto lg:w-[1000px] md:w-[620px] w-screen90 border-b border-neutral-700 py-6 z-20">
        <button
            @click="show = !show"
            class="text-neutral-300 text-center flex justify-between items-center w-full">
            <span class="text-xl font-['OpenSans-Medium'] text-left">
                <slot name="title" />
            </span>

            <span>
                <svg
                    v-if="!show"
                    fill="#BD66FF"
                    class="w-10 h-10"
                    viewBox="0 0 256 256"
                    id="Flat"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M220,128a4.0002,4.0002,0,0,1-4,4H132v84a4,4,0,0,1-8,0V132H40a4,4,0,0,1,0-8h84V40a4,
                        4,0,0,1,8,0v84h84A4.0002,4.0002,0,0,1,220,128Z" />
                </svg>
                <svg
                    v-if="show"
                    fill="#BD66FF"
                    class="w-10 h-10"
                    viewBox="0 0 256 256"
                    id="Flat"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M216,132H40a4,4,0,0,1,0-8H216a4,4,0,0,1,0,8Z" />
                </svg>
            </span>
        </button>
        <transition
            enter-active-class="transition-all duration-300"
            enter-from-class="transform max-h-0 opacity-0"
            enter-to-class="transform max-h-screen opacity-100"
            leave-active-class="transition-all duration-150"
            leave-from-class="transform max-h-screen opacity-100"
            leave-to-class="transform max-h-0 opacity-0">
            <div
                class="text-neutral-400 max-h-0"
                v-show="show">
                <slot name="content" />
            </div>
        </transition>
    </div>
</template>
