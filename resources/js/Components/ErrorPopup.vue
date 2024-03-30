<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    errors: Map,
});

const l_errors = ref(props.errors);

const error_timer = () => {
    setTimeout(function () {
        l_errors.value.clear();
    }, 5000);
};

watch(
    l_errors,
    async (old_value, new_value) => {
        error_timer();
    },
    {
        immediate: true,
    },
);
</script>

<template>
    <template
        v-show="l_errors.size > 0"
        v-for="error in l_errors">
        <div
            @click="l_errors.delete(error[0])"
            class="fixed top-28 right-6 bg-red-800/60 p-6 text-center rounded-md border-red-800 border-2 text-neutral-300 w-64">
            <p>{{ $t("errors." + error[0].toString().toLowerCase() + "." + error[1].toString().toLowerCase()) }}</p>
        </div>
    </template>
</template>

<style></style>
