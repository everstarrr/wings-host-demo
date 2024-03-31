<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import Modal from "./Modal.vue";

const emit = defineEmits(["close"]);

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
});

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-neutral-100">
                <slot name="title" />
            </div>

            <div class="mt-4 text-sm text-neutral-400">
                <slot name="content" />
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 bg-neutral-800 text-right">
            <slot name="footer" />
        </div>
    </Modal>
</template>
