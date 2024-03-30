<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
    is_disabled: Boolean,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="border-neutral-700 bg-neutral-800 text-neutral-300 focus:border-white focus:ring-white rounded-md shadow-sm"
        :value="modelValue"
        :disabled="is_disabled"
        @input="$emit('update:modelValue', $event.target.value)" />
</template>
