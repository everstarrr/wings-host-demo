<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

defineProps({
    icon: Object,
});
</script>

<template>
    <div class="p-8 bg-neutral-900 ring-1 ring-inset ring-white/5 rounded-lg bg-opacity-50">
        <font-awesome-icon
            :icon="icon"
            size="2xl"
            style="color: #ffffff"
            class="my-4 mx-2" />
        <h3 class="py-4 text-2xl font-['OpenSans-SemiBold'] text-neutral-300">
            <slot name="title" />
        </h3>

        <p class="mt-1 text-neutral-400 text-md leading-relaxed">
            <slot name="content" />
        </p>
    </div>
</template>
