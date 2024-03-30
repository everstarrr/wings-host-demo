<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const props = defineProps({
    userIcon: Object,
    isLoaded: Boolean,
});
</script>

<template>
    <div class="bg-neutral-900 rounded-2xl border p-4 px-6 border-neutral-700 w-full sm:w-max">
        <div class="flex justify-between">
            <div class="text-neutral-300">
                <div class="flex items-center space-x-8">
                    <font-awesome-icon
                        :icon="userIcon"
                        class="w-10 h-10" />
                    <div class="flex-col">
                        <span class="text-sm text-neutral-500">
                            <slot name="title" />
                        </span>
                        <p class="text-3xl">
                            <slot name="content" />
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
