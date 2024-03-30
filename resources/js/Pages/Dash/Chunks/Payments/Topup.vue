<script setup>
import DropdownCurrencyItem from "@/Components/DropdownCurrencyItem.vue";
import Dropdown from "@/Components/Dropdown.vue";
import { useForm } from "@inertiajs/vue3";

const submit = () => {
    form.post(route("api.v1.payments.pay"));
};

const form = useForm({
    amount: 100,
    currency: "rub",
});
</script>

<template>
    <div class="bg-neutral-800 border-2 border-neutral-300 rounded-md pt-6 px-6">
        <div class="border-b border-neutral-700 pb-1.5 xl:text-left text-center">
            <span class="text-neutral-300 text-xl">Пополнить баланс</span>
        </div>
        <div class="xl:flex mt-5 xl:space-x-14">
            <div class="pb-6">
                <div class="flex space-x-10 justify-center">
                    <div>
                        <form @submit.prevent="submit()">
                            <div class="text-neutral-400 ml-0.5 text-sm mb-1">Сумма пополнения</div>
                            <div class="inline-flex w-full">
                                <input
                                    v-model="form.amount"
                                    placeholder="100"
                                    type="number"
                                    step="0.01"
                                    class="w-[107px] bg-neutral-900 rounded-l-md border-neutral-700 text-neutral-300 focus:border-neutral-700 focus:ring-transparent border-r-neutral-900 focus:border-r-neutral-900 placeholder-neutral-500" />

                                <!-- Currencies -->
                                <Dropdown
                                    width="full"
                                    align="left">
                                    <template #trigger="{ open }">
                                        <button
                                            type="button"
                                            class="inline-flex items-center w-full pr-4 py-auto h-12 leading-4 font-['OpenSans-Medium'] rounded-r-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between border-l-neutral-900">
                                            <span class="border-l-2 border-neutral-400 pl-3">
                                                <span class="text-neutral-400">{{ form.currency.toUpperCase() }}</span>
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
                                            @click.prevent="form.currency = 'rub'"
                                            :active="form.currency === 'rub'">
                                            RUB
                                        </DropdownCurrencyItem>
                                    </template>
                                </Dropdown>
                            </div>
                            <div
                                v-if="form.errors.requests"
                                class="mt-1 text-red-500">
                                {{ form.errors.requests }}
                            </div>
                            <div>
                                <button
                                    type="submit"
                                    class="w-48 px-6 py-auto h-12 leading-4 font-medium rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out xl:mt-10 mt-4">
                                    Пополнить
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="inline-flex pb-6 space-x-14 w-full justify-center xl:justify-start">
                <div class="text-center space-y-2 text-neutral-400">
                    <p class="text-lg break-word text-neutral-300 mb-2.5">СУММА</p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': (form.amount >= 250) & (form.amount < 500) }">
                        от 250&#8381;
                    </p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': (form.amount >= 500) & (form.amount < 750) }">
                        от 500&#8381;
                    </p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': (form.amount >= 750) & (form.amount < 1000) }">
                        от 750&#8381;
                    </p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': form.amount >= 1000 }">
                        от 1000&#8381;
                    </p>
                </div>

                <div class="text-center space-y-2 text-neutral-400">
                    <p class="text-lg break-word text-neutral-300 mb-2.5">БОНУС</p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': (form.amount >= 250) & (form.amount < 500) }">
                        +2.0%
                    </p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': (form.amount >= 500) & (form.amount < 750) }">
                        +5.0%
                    </p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': (form.amount >= 750) & (form.amount < 1000) }">
                        +7.0%
                    </p>
                    <p
                        class="break-word"
                        :class="{ 'text-green-500': form.amount >= 1000 }">
                        +10.0%
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
