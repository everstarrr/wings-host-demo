<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faCircleInfo,
    faHardDrive,
    faMemory,
    faMicrochip,
    faTriangleExclamation,
} from "@fortawesome/free-solid-svg-icons";
import { reactive, ref, watch } from "vue";
import InputError from "@/Components/InputError.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DefaultButton from "@/Components/DefaultButton.vue";
import ErrorPopup from "@/Components/ErrorPopup.vue";

const props = defineProps({
    server: Object,
    wh_server: Object,
});

const page = usePage();
const errors = ref(new Map());

const form = useForm(
    reactive({
        wh_id: props.wh_server.id,
        ext_id: props.server.id,
        uuid_s: props.server.uuidShort,
        cpu: props.server.cpu / 100,
        ram: props.server.memory / 1024,
        disk: props.server.disk / 1024,
        backups: props.server.backup_limit > 3,
        databases: props.server.database_limit > 0,
    }),
);

const processing = ref(false);
const put_form = async () => {
    processing.value = true;
    await axios
        .put(route("api.v1.servers.resources.edit"), form)
        .then(function (response) {
            if (response.data) {
                if (response.data.status === "success") {
                    // TODO: сброс вот этой херни которая плюс минус
                    current_price_day.value = price_day;
                    current_price_month.value = price_month;
                } else if (response.data.status === "error") {
                    errors.value.set("resources", response.data.error);
                }
            }
            processing.value = false;
        })
        .catch((err) => {
            if (err.response.data.error) {
                errors.value.set("resources", err.response.data.error);
            } else if (!axios.isCancel(err)) {
                errors.value.set("resources", err);
            }
            processing.value = false;
        });
};

function submit() {
    put_form();
}

// MAX VALUES
const ram_max = ref(16);
const cpu_max = ref(8);
const disk_max = ref(50);

// NODE
const nodes = ref(null);
const server_node = ref(null);

const get_nodes = async () => {
    await axios
        .get(route("api.v1.nodes"))
        .then(function (response) {
            nodes.value = response.data;

            for (const node of Object.keys(nodes.value)) {
                if (nodes.value[node].ext_id === props.server.node_id) {
                    server_node.value = nodes.value[node];
                    break;
                }
            }

            const ram_free = server_node.value.resources.ram.free.gb;
            const cpu_threads = server_node.value.resources.cpu.free.threads;
            const disk_free = server_node.value.resources.disk.free.gb;

            if (form.ram > ram_free) {
                ram_max.value = form.ram;
            } else {
                if (ram_free >= 16) {
                    ram_max.value = 16;
                } else {
                    ram_max.value = ram_free.toFixed(0) - 1;
                }
            }

            if (form.disk > disk_free) {
                disk_max.value = form.disk;
            } else {
                if (disk_free >= 50) {
                    disk_max.value = 50;
                } else {
                    disk_max.value = disk_free.toFixed(0) - 1;
                }
            }

            if (form.cpu > cpu_threads) {
                cpu_max.value = form.cpu;
            } else {
                if (cpu_threads.value >= 8) {
                    cpu_max.value = 8;
                } else {
                    cpu_max.value = cpu_threads;
                }
            }
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                errors.value.set("nodes", "retrieve");
            }
        });
};

setTimeout(function () {
    get_nodes();
}, 50);

// PRICE
const current_price_day = ref(props.wh_server.price.day);
const price_day = ref(-1);
const current_price_month = ref(props.wh_server.price.month);
const price_month = ref(-1);
const cpu_price = ref(-1);
const ram_price = ref(-1);
const disk_price = ref(-1);
const database_price = ref(-1);
const backup_price = ref(-1);

const calc_price = async () => {
    await axios
        .post(
            route("api.v1.price.calc"),
            {
                server_type: "service",
                node: props.server.node_id,
                cpu: form.cpu,
                ram: form.ram,
                disk: form.disk,
                backups: form.backups,
                databases: form.databases,
            },
            { signal: price_abort_controller.signal },
        )
        .then(function (response) {
            if (response.data) {
                price_day.value = response.data.day;
                price_month.value = response.data.month;
                cpu_price.value = response.data.details.cpu.plan;
                ram_price.value = response.data.details.plan.ram;
                disk_price.value = response.data.details.plan.disk;
                backup_price.value = response.data.details.plan.backup;
                database_price.value = response.data.details.plan.database;
            } else {
                setTimeout(function () {
                    price_day.value = -1;
                    price_month.value = -1;
                }, 5);
            }
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                price_day.value = err;
            }
        });
};

let price_abort_controller = new AbortController();
watch(
    () => [form.cpu, form.ram, form.disk, form.backups, form.databases],
    async () => {
        if (price_abort_controller) {
            price_abort_controller.abort();
            price_abort_controller = new AbortController();
        }

        await calc_price();
    },
    {
        immediate: true,
    },
);

calc_price();
</script>

<template>
    <ErrorPopup :errors="errors" />
    <form @submit.prevent="submit()">
        <div
            class="bg-neutral-900 rounded-2xl border border-neutral-700 overflow-hidden mt-10 mb-10 lg:w-2/3 xl:w-1/2 max-w-xl w-full">
            <div class="p-8 bg-neutral-900">
                <div class="flex-col space-y-8 border-b border-neutral-800 pb-4">
                    <!-- RAM -->
                    <div class="flex lg:justify-between lg:space-x-0 sm:space-x-20 space-x-0 w-full">
                        <div class="flex-col">
                            <div class="inline-flex sm:items-center items-end w-full mb-3 justify-between">
                                <div class="flex-col">
                                    <div class="inline-flex space-x-2.5 items-center">
                                        <p class="text-2xl text-neutral-300">RAM</p>
                                        <p class="text-neutral-500 text-lg w-36">{{ ram_price }}&#x20bd; за 1 GB</p>
                                    </div>
                                    <p class="text-neutral-300 text-sm">Оперативная память</p>
                                </div>
                                <div
                                    class="inline-flex sm:flex-row flex-col-reverse items-end sm:items-center space-x-4">
                                    <input
                                        type="number"
                                        v-model="form.ram"
                                        min="0.5"
                                        :max="ram_max"
                                        step="0.5"
                                        class="w-20 bg-neutral-800 rounded-md border-neutral-700 text-neutral-300 focus:border-white focus:ring-white" />
                                    <span
                                        class="text-green-500"
                                        v-if="$props.server.memory / 1024 < form.ram"
                                        >+{{ form.ram - $props.server.memory / 1024 }}</span
                                    >
                                    <span
                                        class="text-red-500"
                                        v-else-if="$props.server.memory / 1024 > form.ram"
                                        >{{ form.ram - $props.server.memory / 1024 }}</span
                                    >
                                </div>
                            </div>
                            <div class="flex-col">
                                <input
                                    type="range"
                                    v-model="form.ram"
                                    min="0.5"
                                    :max="ram_max"
                                    step="0.5"
                                    class="w-[284px] cursor-pointer slider" />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.ram" />
                                <div class="text-neutral-400 space-x-[248px]">
                                    <span>0.5</span>
                                    <span>{{ Number.isInteger(ram_max) ? ram_max : ram_max.toFixed(1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-4 items-center">
                            <font-awesome-icon
                                :icon="faMemory"
                                style="color: #ffffff"
                                class="w-20 h-20 sm:inline hidden" />
                            <div class="tooltip">
                                <font-awesome-icon
                                    :icon="faCircleInfo"
                                    style="color: #737373"
                                    class="w-4 h-4 pt-1.5 sm:inline hidden" />
                                <span class="tooltiptext block">
                                    Чем больше выделено оперативной памяти - тем больше игроков, плагинов и модов сможет
                                    выдержать сервер без перезапуска.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- CPU -->
                    <div class="flex lg:justify-between lg:space-x-0 sm:space-x-20 space-x-0 w-full">
                        <div class="flex-col">
                            <div class="inline-flex sm:items-center items-end w-full mb-3 justify-between">
                                <div class="flex-col">
                                    <div class="inline-flex space-x-2.5 items-center">
                                        <p class="text-2xl text-neutral-300">CPU</p>
                                        <p class="text-neutral-500 text-lg w-[148px]">
                                            {{ cpu_price }}&#x20bd; за 1 CPU
                                        </p>
                                    </div>
                                    <p class="text-neutral-300 text-sm">Ядра процессора</p>
                                </div>
                                <div
                                    class="inline-flex sm:flex-row flex-col-reverse items-end sm:items-center space-x-4">
                                    <input
                                        type="number"
                                        v-model="form.cpu"
                                        min="0.5"
                                        :max="cpu_max"
                                        step="0.5"
                                        class="w-20 bg-neutral-800 rounded-md border-neutral-700 text-neutral-300 focus:border-white focus:ring-white" />
                                    <span
                                        class="text-green-500"
                                        v-if="props.server.cpu / 100 < form.cpu"
                                        >+{{ form.cpu - $props.server.cpu / 100 }}</span
                                    >
                                    <span
                                        class="text-red-500"
                                        v-else-if="props.server.cpu / 100 > form.cpu"
                                        >{{ form.cpu - props.server.cpu / 100 }}</span
                                    >
                                </div>
                            </div>
                            <div class="flex-col">
                                <input
                                    type="range"
                                    v-model="form.cpu"
                                    min="0.5"
                                    :max="cpu_max"
                                    step="0.5"
                                    class="w-[284px] cursor-pointer slider" />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.cpu" />
                                <div class="text-neutral-400 space-x-[248px]">
                                    <span>0.5</span>
                                    <span>{{ Number.isInteger(cpu_max) ? cpu_max : cpu_max.toFixed(1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-4 items-center">
                            <font-awesome-icon
                                :icon="faMicrochip"
                                style="color: #ffffff"
                                class="w-20 h-20 sm:inline hidden" />
                            <div class="tooltip">
                                <font-awesome-icon
                                    :icon="faCircleInfo"
                                    style="color: #737373"
                                    class="w-4 h-4 pt-1.5 sm:inline hidden" />
                                <span class="tooltiptext block">
                                    От кол-ва выделенных ядер зависит скорость запуска сервера, а также
                                    производительность. Мы рекомендуем выделять на сервер не менее двух ядер.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- DISK -->
                    <div class="flex lg:justify-between lg:space-x-0 sm:space-x-20 space-x-0 w-full">
                        <div class="flex-col">
                            <div class="inline-flex sm:items-center items-end w-full mb-3 justify-between">
                                <div class="flex-col">
                                    <div class="inline-flex space-x-2.5 items-center">
                                        <p class="text-2xl text-neutral-300">DISK</p>
                                        <p class="text-neutral-500 text-lg w-36">{{ disk_price }}&#x20bd; за 1 GB</p>
                                    </div>
                                    <p class="text-neutral-300 text-sm">Дисковое пространство</p>
                                </div>
                                <div
                                    class="inline-flex sm:flex-row flex-col-reverse items-end sm:items-center space-x-4">
                                    <input
                                        type="number"
                                        v-model="form.disk"
                                        min="0.5"
                                        :max="disk_max"
                                        step="0.5"
                                        class="w-20 bg-neutral-800 rounded-md border-neutral-700 text-neutral-300 focus:border-white focus:ring-white" />
                                    <span
                                        class="text-green-500"
                                        v-if="props.server.disk / 1024 < form.disk"
                                        >+{{ form.disk - props.server.disk / 1024 }}</span
                                    >
                                    <span
                                        class="text-red-500"
                                        v-else-if="props.server.disk / 1024 > form.disk"
                                        >{{ form.disk - props.server.disk / 1024 }}</span
                                    >
                                </div>
                            </div>
                            <div class="flex-col">
                                <input
                                    type="range"
                                    v-model="form.disk"
                                    min="0.5"
                                    :max="disk_max"
                                    step="0.5"
                                    class="w-[284px] cursor-pointer slider" />
                                <InputError
                                    class="mt-2"
                                    :message="form.errors.disk" />
                                <div class="text-neutral-400 space-x-[248px]">
                                    <span>0.5</span>
                                    <span>{{ Number.isInteger(disk_max) ? disk_max : disk_max.toFixed(1) }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-4 items-center">
                            <font-awesome-icon
                                :icon="faHardDrive"
                                style="color: #ffffff"
                                class="w-20 h-20 sm:inline hidden" />
                            <div class="tooltip">
                                <font-awesome-icon
                                    :icon="faCircleInfo"
                                    style="color: #737373"
                                    class="w-4 h-4 pt-1.5 sm:inline hidden" />
                                <span class="tooltiptext block">
                                    На дисковом пространстве хранятся данные сервера. Чем больше места выделено - тем
                                    больше данных он сможет сохранить.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <section class="section mt-4 text-neutral-300 max-w-3xl">
                    <div class="section__item section__item--start">
                        <p class="text-5xl">{{ price_day }}&#x20bd;</p>
                        <span
                            class="text-3xl text-green-500"
                            v-if="current_price_day < price_day"
                            >+{{ (price_day - current_price_day).toFixed(2) }}&#x20bd;</span
                        >
                        <span
                            class="text-3xl text-red-500"
                            v-else-if="current_price_day > price_day"
                            >{{ (price_day - current_price_day).toFixed(2) }}&#x20bd;</span
                        >
                        <p class="text-xl text-neutral-500">в день</p>
                    </div>

                    <div class="section__item section__item--end">
                        <p class="text-5xl">{{ price_month }}&#x20bd;</p>
                        <span
                            class="text-3xl text-green-500"
                            v-if="current_price_month < price_month"
                            >+{{ (price_month - current_price_month).toFixed(2) }}&#x20bd;</span
                        >
                        <span
                            class="text-3xl text-red-500"
                            v-else-if="current_price_month > price_month"
                            >{{ (price_month - current_price_month).toFixed(2) }}&#x20bd;</span
                        >
                        <p class="text-xl text-neutral-500">в месяц</p>
                    </div>
                </section>
                <!-- TODO: сделать значки -->
                <div class="flex text-neutral-300 space-x-2.5 mt-6 items-center">
                    <Checkbox
                        class="h-6 w-6 cursor-pointer"
                        v-model:checked="form.databases" />
                    <p class="text-lg">Базы данных</p>
                    <p class="text-neutral-500 pt-0.5">&#183; &nbsp;бесплатно&#xFE61;</p>
                    <div class="tooltip">
                        <font-awesome-icon
                            :icon="faCircleInfo"
                            style="color: #737373"
                            class="w-4 h-4 pt-1.5" />
                        <span class="tooltiptext block">
                            Бесплатная до 5 GB база данных MySQL для ваших плагинов, далее &ndash;
                            {{ database_price }}&#x20bd;/день за GB.
                        </span>
                    </div>

                    <div
                        class="tooltip"
                        v-if="props.server.database_limit > 0 && !form.databases">
                        <font-awesome-icon
                            :icon="faTriangleExclamation"
                            style="color: #eab308"
                            class="animate-pulse w-6 h-6 pt-1.5 lg:inline hidden" />
                        <span class="tooltiptext block"
                            >Вы отказываетесь от дополнительной опции, которая ранее была включена. Все базы данных
                            этого сервера будут удалены.</span
                        >
                    </div>
                </div>
                <div class="flex text-neutral-300 space-x-2.5 mt-2 items-center">
                    <Checkbox
                        class="h-6 w-6 cursor-pointer"
                        v-model:checked="form.backups" />
                    <p class="text-lg">Доп. резервные копии</p>
                    <p class="text-neutral-500 pt-0.5">&#183; &nbsp;{{ backup_price }}&#x20bd;/день за GB</p>
                    <div class="tooltip">
                        <font-awesome-icon
                            :icon="faCircleInfo"
                            style="color: #737373"
                            class="w-4 h-4 pt-1.5 inline" />
                        <span class="tooltiptext block">
                            Обеспечьте сохранность ваших данных с помощью дополнительных резервных копий. Плата
                            взимается только за предыдущие 3-м последним резевные копии, с учётом размера каждой из них.
                        </span>
                    </div>

                    <div
                        class="tooltip"
                        v-if="props.server.backup_limit > 3 && !form.backups">
                        <font-awesome-icon
                            :icon="faTriangleExclamation"
                            style="color: #eab308"
                            class="animate-pulse w-6 h-6 pt-1.5 lg:inline hidden" />
                        <span class="tooltiptext block"
                            >Вы отказываетесь от дополнительной опции, которая ранее была включена. Все, кроме 3-х
                            последних, резервные копии этого сервера будут удалены.</span
                        >
                    </div>
                </div>
                <DefaultButton
                    type="submit"
                    class="w-48 mt-6"
                    :disabled="processing">
                    Сохранить
                </DefaultButton>
            </div>
        </div>
    </form>
</template>
