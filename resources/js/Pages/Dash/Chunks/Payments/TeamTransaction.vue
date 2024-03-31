<script setup>
import DropdownCurrencyItem from "@/Components/DropdownCurrencyItem.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    amount: 100,
    currency: "rub",
});

const props = defineProps({
    enabled: Boolean,
});
</script>

<template>
    <div class="bg-neutral-800 border border-neutral-700 rounded-md py-6 px-6">
        <div class="border-b border-neutral-700 pb-1.5 xl:text-left text-center">
            <span class="text-neutral-300 text-xl">Перевод</span>
        </div>

        <div class="flex space-x-10 justify-center relative">
            <div class="flex-col mt-5 xl:space-y-10">
                <div>
                    <div class="text-neutral-400 ml-0.5 text-sm mb-1">Сумма перевода</div>
                    <div class="inline-flex">
                        <input
                            disabled
                            placeholder="100"
                            type="number"
                            class="w-32 bg-neutral-900 rounded-l-md border-neutral-700 text-neutral-300 border-r-transparent focus:ring-transparent focus:border-neutral-700 focus:border-r-transparent placeholder-neutral-500" />
                        <button
                            type="button"
                            class="inline-flex items-center w-full pr-4 py-auto h-12 leading-4 font-['OpenSans-Medium'] rounded-r-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between border-l-transparent cursor-default">
                            <span class="border-l-2 border-neutral-500 pl-3 text-neutral-500">RUB</span>
                        </button>
                    </div>
                </div>

                <div class="xl:hidden block space-y-2 mt-2">
                    <div>
                        <div class="text-neutral-400 ml-0.5 text-sm mb-1">Откуда</div>
                        <Dropdown
                            width="full"
                            align="left">
                            <template #trigger="{ open }">
                                <button
                                    type="button"
                                    class="inline-flex items-center w-full pr-4 py-auto h-12 leading-4 font-medium rounded-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between">
                                    <span
                                        v-if="form.currency === 'rub'"
                                        class="pl-3">
                                        <span class="text-neutral-400">Группа 1</span>
                                    </span>
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
                                    class=""
                                    @click="form.currency = 'rub'"
                                    :active="form.currency === 'rub'">
                                    Группа 1
                                </DropdownCurrencyItem>
                            </template>
                        </Dropdown>
                    </div>
                    <div>
                        <div class="text-neutral-400 ml-0.5 text-sm mb-1">Куда</div>
                        <Dropdown
                            width="full"
                            align="left">
                            <template #trigger="{ open }">
                                <button
                                    type="button"
                                    class="inline-flex items-center w-full pr-4 py-auto h-12 leading-4 font-medium rounded-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between">
                                    <span class="pl-3">
                                        <span class="text-neutral-400">Группа 2</span>
                                    </span>
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
                                    class=""
                                    @click="form.currency = 'rub'"
                                    :active="form.currency === 'rub'">
                                    Группа 2
                                </DropdownCurrencyItem>
                            </template>
                        </Dropdown>
                    </div>
                </div>
                <button
                    type="button"
                    class="w-48 px-6 py-auto h-12 leading-4 font-medium rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block mt-10"
                    :class="{ 'bg-neutral-500 hover:bg-neutral-500 cursor-default focus:bg-neutral-500': !enabled }">
                    Перевести
                </button>
            </div>

            <div class="flex-col mt-5 space-y-4 w-full hidden xl:block">
                <div>
                    <div class="text-neutral-400 ml-0.5 text-sm mb-1">Откуда</div>
                    <Dropdown
                        width="full"
                        align="left">
                        <template #trigger="{ open }">
                            <button
                                type="button"
                                class="inline-flex items-center w-full pr-4 py-auto h-12 leading-4 font-medium rounded-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between">
                                <span
                                    v-if="form.currency === 'rub'"
                                    class="pl-3">
                                    <span class="text-neutral-400">Группа 1</span>
                                </span>
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
                                class=""
                                @click="form.currency = 'rub'"
                                :active="form.currency === 'rub'">
                                Группа 1
                            </DropdownCurrencyItem>
                        </template>
                    </Dropdown>
                </div>

                <div>
                    <div class="text-neutral-400 ml-0.5 text-sm mb-1">Куда</div>
                    <Dropdown
                        width="full"
                        align="left">
                        <template #trigger="{ open }">
                            <button
                                type="button"
                                class="inline-flex items-center w-full pr-4 py-auto h-12 leading-4 font-medium rounded-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between">
                                <span class="pl-3">
                                    <span class="text-neutral-400">Группа 2</span>
                                </span>
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
                                class=""
                                @click="form.currency = 'rub'"
                                :active="form.currency === 'rub'">
                                Группа 2
                            </DropdownCurrencyItem>
                        </template>
                    </Dropdown>
                </div>
            </div>
            <div class="overlay text-center text-3xl text-neutral-300">
                <p class="relative soon">Скоро...</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.overlay {
    position: absolute; /* Позиционируем перекрывающий слой абсолютно */
    width: calc(100% + 48px); /* Ширина равна 100% размера родительского элемента */
    height: calc(100% + 83px); /* Высота равна 100% размера родительского элемента */
    background-color: rgba(20, 20, 20, 0.5);
    z-index: 999;
    display: block;
    top: -59px;
    left: -64px;
    border-radius: 0.375rem;
    backdrop-filter: blur(6px);
}

.soon {
    top: calc(50% - 20px);
}
</style>
