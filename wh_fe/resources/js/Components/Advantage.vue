<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
    userIcon: Object,
});
</script>

<template>
    <div
        class="scale-100 p-5 bg-neutral-950 via-transparent ring-1 ring-inset ring-white/5 rounded-lg shadow-none motion-safe:hover:scale-[1.01] transition-all duration-250 block bg-opacity-50">
        <div class="border-neutral-300 border rounded-md inline-block">
            <p class="py-3 text-xl px-4 font-['OpenSans-SemiBold'] text-neutral-300">
                <slot name="title" />
            </p>
        </div>
        <font-awesome-icon
            :icon="userIcon"
            size="2xl"
            class="fai" />
        <p class="mt-4 text-neutral-300 text-lg leading-relaxed">
            <slot name="description" />
        </p>
    </div>
</template>

<style scoped>
.fai {
    color: #ffffff;
    float: right;
    margin-top: 12px;
    margin-right: 12px;
    @media (min-width: 1280px) {
        display: inline;
    }
    display: none;
}
</style>
