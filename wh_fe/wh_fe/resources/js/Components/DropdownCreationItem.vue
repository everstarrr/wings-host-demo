<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import DropdownLink from "@/Components/DropdownLink.vue";
import { faCheck } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

defineProps({
    active: Boolean,
    img: Object,
});
</script>

<template>
    <div class="w-60">
        <DropdownLink as="button">
            <div class="flex items-center justify-between">
                <div class="flex space-x-2 items-center">
                    <v-lazy-image
                        v-if="img"
                        :src="img['src']"
                        :class="img['class']"
                        :alt="img['alt']" />
                    <p class="text-xl">
                        <slot />
                    </p>
                </div>
                <font-awesome-icon
                    v-if="active"
                    :icon="faCheck"
                    size="2xl"
                    style="color: #22c55e"
                    class="" />
            </div>
        </DropdownLink>
    </div>
</template>
