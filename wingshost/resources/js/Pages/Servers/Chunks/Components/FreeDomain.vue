<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DefaultButton from "@/Components/DefaultButton.vue";
import TextInput from "@/Components/TextInput.vue";
import DropdownCurrencyItem from "@/Components/DropdownCurrencyItem.vue";
import { ref } from "vue";

defineProps({
    selected_domain: Object,
    enabled: {
        type: Boolean,
        default: true,
    },
});

const use_tcpshield = ref(false);
</script>

<template>
    <div class="bg-neutral-800 border border-neutral-700 rounded-md p-6 flex flex-col space-y-4 w-full relative">
        <span class="text-neutral-300">{{ $t("servers.free_domain.notice") }}</span>
        <div class="xl:flex items-center xl:space-x-3 w-full xl:space-y-0 space-y-2 space-x-0">
            <div class="flex flex-col">
                <span class="text-neutral-400 ml-0.5 text-sm mb-0.5">{{ $t("servers.free_domain.sub") }}</span>
                <TextInput class="placeholder-neutral-500 h-12 w-56" />
            </div>
            <span class="text-neutral-300 text-xl pt-2.5 hidden xl:inline">.</span>
            <div class="flex flex-col">
                <span class="text-neutral-400 ml-0.5 text-sm mb-0.5">{{ $t("servers.free_domain.domain") }}</span>
                <Dropdown
                    width="full"
                    align="left">
                    <template #trigger="{ open }">
                        <button
                            type="button"
                            class="pl-3 text-neutral-300 inline-flex items-center w-56 pr-4 py-auto h-12 leading-4 font-['OpenSans-Medium'] rounded-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between">
                            <span v-if="selected_domain">{{ selected_domain }}</span>
                            <span v-else>{{ $t("servers.free_domain.choose") }}</span>

                            <svg
                                class="ml-2 -mr-0.5 h-4 w-4"
                                :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                color="#d4d4d4">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </template>

                    <template #content>
                        <DropdownCurrencyItem
                            @click="selected_domain = 'whsrv.ru'"
                            :active="selected_domain === 'whsrv.ru'">
                            whsrv.ru
                        </DropdownCurrencyItem>
                    </template>
                </Dropdown>
            </div>
        </div>
        <div class="flex items-center space-x-1.5 text-neutral-300">
            <Checkbox
                class="h-5 w-5"
                v-model:checked="use_tcpshield" />
            <span>{{ $t("servers.free_domain.use_tcpshield") }}</span>
        </div>
        <span
            class="text-neutral-300"
            v-if="use_tcpshield">
            {{ $t("servers.free_domain.use_tcpshield.notice") }}
        </span>
        <div>
            <TextInput
                v-if="use_tcpshield"
                placeholder="CNAME"
                class="placeholder-neutral-500 h-12 w-full mb-4" />
            <DefaultButton class="w-48">
                {{ $t("base.save") }}
            </DefaultButton>
        </div>
        <div class="overlay text-center text-3xl text-neutral-300">
            <p class="relative soon">Скоро...</p>
        </div>
    </div>
</template>

<style scoped>
.overlay {
    position: absolute; /* Позиционируем перекрывающий слой абсолютно */
    width: 100%; /* Ширина равна 100% размера родительского элемента */
    height: 100%; /* Высота равна 100% размера родительского элемента */
    background-color: rgba(20, 20, 20, 0.5);
    z-index: 999;
    display: block;
    top: -16px;
    left: 0;
    border-radius: 0.375rem;
    backdrop-filter: blur(6px);
}

.soon {
    top: calc(50% - 16px);
}
</style>
