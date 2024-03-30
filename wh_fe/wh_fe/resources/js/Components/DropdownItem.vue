<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import DropdownLink from "@/Components/DropdownLink.vue";

defineProps({
    entries: Array,
    active: String,
    func: Function,
    img: Array,
});
</script>

<template>
    <div class="w-60">
        <template v-for="entry in entries">
            <form @submit.prevent="func(entry[0])">
                <DropdownLink as="button">
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2.5 items-center">
                            <v-lazy-image
                                v-if="img && entry[1] === img[0]"
                                :class="img[1]"
                                :src="img[2]" />
                            <p>{{ $t(entry[1]) }}</p>
                        </div>
                        <svg
                            v-if="entry[0] === active"
                            class="mr-2 h-5 w-5 text-green-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </DropdownLink>
            </form>
        </template>
    </div>
</template>
