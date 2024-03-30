<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import craftingtable_png from "@pngs/services/minecraft/craftingtable.png";
import bedrock_png from "@pngs/services/minecraft/bedrock.png";
import command_block_png from "@pngs/services/minecraft/command_block.png";
import command_block_repeating_png from "@pngs/services/minecraft/command_block_repeating.png";
import paper_png from "@pngs/services/minecraft/paper.png";
import purpur_png from "@pngs/services/minecraft/purpur.png";
import spigot_png from "@pngs/services/minecraft/spigot.png";
import forge_png from "@pngs/services/minecraft/forge.png";
import fabric_png from "@pngs/services/minecraft/fabric.png";
import vanilla_png from "@pngs/services/minecraft/vanilla.png";
import folia_png from "@pngs/services/minecraft/folia.png";
import deu_png from "@pngs/countries/deu.png";
import fin_png from "@pngs/countries/fin.png";
import rus_png from "@pngs/countries/rus.png";
import swe_png from "@pngs/countries/swe.png";
import liteloader_png from "@pngs/services/minecraft/liteloader.png";
import nukkit_png from "@pngs/services/minecraft/nukkit.png";
import powernukkitx_png from "@pngs/services/minecraft/powernukkitx.png";
import pmmp_png from "@pngs/services/minecraft/pmmp.png";
import DropdownCreationItem from "@/Components/DropdownCreationItem.vue";
import TextInput from "@/Components/TextInput.vue";
import LocationButton from "@/Components/LocationButton.vue";
import { reactive, ref, watch } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faAngleRight, faCircleInfo, faHardDrive, faMemory, faMicrochip } from "@fortawesome/free-solid-svg-icons";
import TextInputLight from "@/Components/TextInputLight.vue";
import Checkbox from "@/Components/Checkbox.vue";
import GradientButton from "@/Components/GradientButton.vue";
import azalea_png from "@pngs/services/minecraft/azalea.png";
import bee_nest_png from "@pngs/services/minecraft/bee_nest.png";
import slime_block_png from "@pngs/services/minecraft/slime_block.png";
import kelp_png from "@pngs/services/minecraft/kelp.png";
import netherite_block_png from "@pngs/services/minecraft/netherite_block.png";
import red_sand_png from "@pngs/services/minecraft/red_sand.png";
import purple_concrete_powder_png from "@pngs/services/minecraft/purple_concrete_powder.png";
import { useForm, usePage } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import bungee_cord_png from "@pngs/services/minecraft/bungeecord-shape.png";
import velocity_png from "@pngs/services/minecraft/velocity.png";
import sculk_webp from "@pngs/services/minecraft/sculk.webp";
import ErrorPopup from "@/Components/ErrorPopup.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    data: String,
});

const page = usePage();
const errors = ref(new Map());

// FORM
const form = useForm(
    reactive({
        service: "minecraft",
        type: null,
        version: null,
        core: null,
        node: 0,
        name: null,
        cpu: 0.5,
        ram: 0.5,
        disk: 0.5,
        databases: false,
        backups: false,
        promocode: "",
    }),
);

// MAX VALUES
const ram_max = ref(16);
const cpu_max = ref(8);
const disk_max = ref(50);

// NODES REQUEST
const node_load = ref(96);
const nodes = ref(null);
const get_nodes = async () => {
    await axios
        .get(route("api.v1.nodes"))
        .then(function (response) {
            nodes.value = response.data;
            for (const node of Object.keys(nodes.value)) {
                if (nodes.value[node].load < node_load.value) {
                    node_load.value = nodes.value[node].load;
                    form.node = nodes.value[node].ext_id;
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

// MAX VALUES WATCHER
watch(
    () => [form.node],
    async () => {
        if (nodes.value) {
            const node = nodes.value[form.node];
            const ram_free = node.resources.ram.free.gb;
            const cpu_threads = node.resources.cpu.free.threads;
            const disk_free = node.resources.disk.free.gb;

            if (ram_free >= 16) {
                ram_max.value = 16;
            } else {
                ram_max.value = ram_free.toFixed(0) - 1;
            }

            if (disk_free >= 50) {
                disk_max.value = 50;
            } else {
                disk_max.value = disk_free.toFixed(0) - 1;
            }

            if (cpu_threads.value >= 8) {
                cpu_max.value = 8;
            } else {
                cpu_max.value = cpu_threads;
            }
        }
    },
    {
        immediate: true,
    },
);

// PROMOCODE REQUEST
const promocode_data = ref();
const promocode_not_found_timeout = ref();
const check_promocode = async () => {
    promocode_data.value = "loading"; // set loading state
    await axios
        .post(route("api.v1.promocode.check"), { promocode: form.promocode })
        .then(function (response) {
            if (response.data) {
                promocode_data.value = response.data;
            } else {
                promocode_data.value = "not found";
                promocode_not_found_timeout.value = setTimeout(function () {
                    promocode_data.value = null;
                }, 10000);
            }
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                promocode_data.value = err;
            }
        })
        .finally(() => {
            if (promocode_data.value && promocode_data.value !== "not found" && promocode_not_found_timeout.value) {
                clearTimeout(promocode_not_found_timeout.value);
            }
        });
};

// PRICE
const price_day = ref(-1);
const price_month = ref(-1);
const cpu_price = ref(-1);
const ram_price = ref(-1);
const disk_price = ref(-1);
const database_price = ref(-1);
const backup_price = ref(-1);

// PRICES REQUEST FUNCTION
const calc_price = async () => {
    await axios
        .post(
            route("api.v1.price.calc"),
            {
                server_type: "service",
                node: form.node,
                cpu: form.cpu,
                ram: form.ram,
                disk: form.disk,
                backups: form.backups,
                // databases: form.databases,
                promocode: form.promocode,
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

// PRICE CHANGE WATCHER
let price_abort_controller = new AbortController();
watch(
    () => [form.node],
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

// FORM VALIDATION
if (props.data) {
    const data = props.data.split(";");
    if (data.length >= 8) {
        form.service = data[0];

        if (/'java' || 'bedrock' || 'proxy'/.test(data[1])) {
            form.type = data[1];
        }

        form.version = data[2];
        form.core = data[3];

        if (parseInt(data[4]) <= ram_max.value) {
            form.ram = parseFloat(data[4]);
        }

        if (parseInt(data[5]) <= cpu_max.value) {
            form.cpu = parseFloat(data[5]);
        }

        if (parseInt(data[6]) <= disk_max.value) {
            form.disk = parseFloat(data[6]);
        }

        form.name = data[7];
    }
}

// CALCULATOR WATCHER
watch(
    () => [form.cpu, form.ram, form.disk],
    async () => {
        price_day.value =
            form.ram * ram_price.value + form.cpu * cpu_price.value + form.disk * disk_price.value + form.backups;
        price_month.value = price_day.value * 30;
    },
    {
        immediate: true,
    },
);
</script>

<template>
    <ErrorPopup :errors="errors" />
    <form @submit.prevent="form.post(route('dash.servers.create.post'))">
        <!-- Whole Right Section -->
        <div class="lg:w-4/5 lg:px-9 float-right w-full mt-10 lg:mt-16 mb-6 lg:mb-10">
            <!-- Whole Server Creation Window -->
            <div class="bg-neutral-900 rounded-2xl border border-neutral-700">
                <!-- Window Title -->
                <p class="font-medium text-2xl text-white leading-tight lg:text-left text-center mt-8 mx-8">
                    {{ $t("servers.create") }}
                </p>

                <!-- Section -->
                <section class="section mb-8">
                    <!-- Left Side -->
                    <div class="p-8 section__item section__item--start">
                        <!-- Client Dropdown -->
                        <Dropdown
                            v-model="form.type"
                            width="full"
                            align="left">
                            <template #trigger="{ open }">
                                <button
                                    type="button"
                                    class="inline-flex items-center w-full px-6 py-auto h-16 text-md leading-4 font-medium rounded-2xl border-neutral-700 border text-neutral-400 bg-neutral-800 hover:text-white focus:outline-none focus:border-white focus:text-white focus:border-2 transition ease-in-out duration-150 hover:border-white justify-between">
                                    <span
                                        v-if="form.type === 'java'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="craftingtable_png"
                                            class="w-10 h-10"
                                            alt="Java Edition" />
                                        <span class="text-xl text-neutral-300">Java Edition</span>
                                    </span>

                                    <span
                                        v-else-if="form.type === 'bedrock'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="bedrock_png"
                                            class="w-10 h-10"
                                            alt="Bedrock Edition" />
                                        <span class="text-xl text-neutral-300">Bedrock Edition</span>
                                    </span>

                                    <span
                                        v-else-if="form.type === 'proxy'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="command_block_png"
                                            class="w-10 h-10"
                                            alt="Proxy" />
                                        <span class="text-xl text-neutral-300">Proxy</span>
                                    </span>

                                    <span v-else>Выберите тип сервера</span>
                                    <svg
                                        class="ml-2 -mr-0.5 h-5 w-5 transition-transform duration-150"
                                        :class="{ 'rotate-180': open }"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: craftingtable_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="
                                        form.type = 'java';
                                        form.version = null;
                                        form.core = null;
                                    "
                                    :active="form.type === 'java'">
                                    Java Edition
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: bedrock_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="
                                        form.type = 'bedrock';
                                        form.version = null;
                                        form.core = null;
                                    "
                                    :active="form.type === 'bedrock'">
                                    Bedrock Edition
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: command_block_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="
                                        form.type = 'proxy';
                                        form.version = null;
                                        form.core = null;
                                    "
                                    :active="form.type === 'proxy'">
                                    Proxy
                                </DropdownCreationItem>
                            </template>
                        </Dropdown>
                        <InputError
                            class="mt-2"
                            :message="form.errors.type" />

                        <!-- Version Dropdown -->
                        <Dropdown
                            width="full"
                            align="left">
                            <template #trigger="{ open }">
                                <button
                                    :disabled="!form.type"
                                    type="button"
                                    :class="
                                        form.type
                                            ? 'hover:border-white hover:text-white text-neutral-400 bg-opacity-100'
                                            : 'bg-opacity-50 text-neutral-600'
                                    "
                                    class="mt-8 inline-flex items-center w-full px-6 py-auto h-16 text-md leading-4 font-medium rounded-2xl border-neutral-700 border bg-neutral-800 focus:outline-none focus:border-white focus:text-white focus:border-2 transition ease-in-out duration-150 justify-between">
                                    <span
                                        v-if="form.version === 'latest'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="command_block_repeating_png"
                                            class="w-10 h-10"
                                            alt="Последняя версия" />
                                        <span class="text-xl text-neutral-300">Последняя версия</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.19.4'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="sculk_webp"
                                            class="w-10 h-10"
                                            alt="1.19.4" />
                                        <span class="text-xl text-neutral-300">1.19.4</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.18.2'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="azalea_png"
                                            class="w-10 h-10"
                                            alt="1.18.2" />
                                        <span class="text-xl text-neutral-300">1.18.2</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.16.5'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="netherite_block_png"
                                            class="w-10 h-10"
                                            alt="1.16.5" />
                                        <span class="text-xl text-neutral-300">1.16.5</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.15.2'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="bee_nest_png"
                                            class="w-10 h-10"
                                            alt="1.15.2" />
                                        <span class="text-xl text-neutral-300">1.15.2</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.13.2'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="kelp_png"
                                            class="w-10 h-10"
                                            alt="1.13.2" />
                                        <span class="text-xl text-neutral-300">1.13.2</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.12.2'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="purple_concrete_powder_png"
                                            class="w-10 h-10"
                                            alt="1.12.2" />
                                        <span class="text-xl text-neutral-300">1.12.2</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.8.8'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="slime_block_png"
                                            class="w-10 h-10"
                                            alt="1.8.8" />
                                        <span class="text-xl text-neutral-300">1.8.8</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.7.10'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="red_sand_png"
                                            class="w-10 h-10"
                                            alt="1.7.10" />
                                        <span class="text-xl text-neutral-300">1.7.10</span>
                                    </span>

                                    <!-- Bedrock Edition versions -->
                                    <span
                                        v-else-if="form.version === '1.19.80.23'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <span class="text-xl text-neutral-300">1.19.80.23</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.18.33'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <span class="text-xl text-neutral-300">1.18.33</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.17.41'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <span class="text-xl text-neutral-300">1.17.41</span>
                                    </span>

                                    <span
                                        v-else-if="form.version === '1.16.221'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <span class="text-xl text-neutral-300">1.16.221</span>
                                    </span>

                                    <span v-else>Выберите версию</span>
                                    <svg
                                        class="ml-2 -mr-0.5 h-5 w-5 transition-transform duration-150"
                                        :class="{ 'rotate-180': open }"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </template>

                            <!-- Java Edition versions -->
                            <template
                                #content
                                v-if="form.type !== 'bedrock'">
                                <template v-if="form.type">
                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: command_block_repeating_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="form.version = 'latest'"
                                        :active="form.version === 'latest'">
                                        Последняя версия
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: sculk_webp,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.19.4';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.19.4'">
                                        1.19.4
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: azalea_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.18.2';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.18.2'">
                                        1.18.2
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: netherite_block_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.16.5';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.16.5'">
                                        1.16.5
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: bee_nest_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.15.2';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.15.2'">
                                        1.15.2
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: kelp_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.13.2';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.13.2'">
                                        1.13.2
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: purple_concrete_powder_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.12.2';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.12.2'">
                                        1.12.2
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: slime_block_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.8.8';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.8.8'">
                                        1.8.8
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        :img="{
                                            src: red_sand_png,
                                            class: 'w-12 h-12',
                                        }"
                                        @click.prevent="
                                            form.version = '1.7.10';
                                            form.core = null;
                                        "
                                        :active="form.version === '1.7.10'">
                                        1.7.10
                                    </DropdownCreationItem>
                                </template>
                                <template v-else>
                                    <DropdownCreationItem class="w-90">Выберите тип сервера</DropdownCreationItem>
                                </template>
                            </template>

                            <!-- Bedrock Edition versions -->
                            <template
                                #content
                                v-else>
                                <template v-if="form.type">
                                    <DropdownCreationItem
                                        class="w-90"
                                        @click.prevent="form.version = 'latest'"
                                        :active="form.version === 'latest'">
                                        Последняя версия (Все ядра)
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        @click.prevent="form.version = '1.19.80.23'"
                                        :active="form.version === '1.19.80.23'">
                                        1.19.80.23 (Vanilla)
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        @click.prevent="form.version = '1.18.33'"
                                        :active="form.version === '1.18.33'">
                                        1.18.33 (Vanilla)
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        @click.prevent="form.version = '1.17.41'"
                                        :active="form.version === '1.17.41'">
                                        1.17.41 (Vanilla)
                                    </DropdownCreationItem>

                                    <DropdownCreationItem
                                        class="w-90"
                                        @click.prevent="form.version = '1.16.221'"
                                        :active="form.version === '1.16.221'">
                                        1.16.221 (Vanilla)
                                    </DropdownCreationItem>
                                </template>
                                <template v-else>
                                    <DropdownCreationItem class="w-90">Выберите тип сервера</DropdownCreationItem>
                                </template>
                            </template>
                        </Dropdown>
                        <InputError
                            class="mt-2"
                            :message="form.errors.version" />

                        <!-- Core Dropdown -->
                        <Dropdown
                            v-model="form.core"
                            width="full"
                            align="left">
                            <template #trigger="{ open }">
                                <button
                                    type="button"
                                    :disabled="!form.type"
                                    :class="
                                        form.type
                                            ? 'hover:border-white hover:text-white text-neutral-400 bg-opacity-100'
                                            : 'bg-opacity-50 text-neutral-600'
                                    "
                                    class="mt-8 inline-flex items-center w-full px-6 py-auto h-16 text-md leading-4 font-medium rounded-2xl border-neutral-700 border bg-neutral-800 focus:outline-none focus:border-white focus:text-white focus:border-2 transition ease-in-out duration-150 justify-between">
                                    <!-- Java Edition cores -->
                                    <span
                                        v-if="form.core === 'paper'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="paper_png"
                                            class="w-10 h-10"
                                            alt="Paper" />
                                        <span class="text-xl text-neutral-300">Paper</span>
                                    </span>
                                    <span
                                        v-else-if="form.core === 'purpur'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="purpur_png"
                                            class="w-10 h-10"
                                            alt="Purpur" />
                                        <span class="text-xl text-neutral-300">Purpur</span>
                                    </span>
                                    <span
                                        v-else-if="form.core === 'spigot'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="spigot_png"
                                            class="w-10 h-10"
                                            alt="Spigot" />
                                        <span class="text-xl text-neutral-300">Spigot</span>
                                    </span>
                                    <span
                                        v-else-if="form.core === 'forge'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="forge_png"
                                            class="w-10 h-10"
                                            alt="Forge" />
                                        <span class="text-xl text-neutral-300">Forge</span>
                                    </span>
                                    <span
                                        v-else-if="form.core === 'fabric'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="fabric_png"
                                            class="w-10 h-10"
                                            alt="Fabric" />
                                        <span class="text-xl text-neutral-300">Fabric</span>
                                    </span>
                                    <span
                                        v-else-if="form.core === 'vanilla'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="vanilla_png"
                                            class="w-10 h-10"
                                            alt="Vanilla" />
                                        <span class="text-xl text-neutral-300">Vanilla</span>
                                    </span>
                                    <span
                                        v-else-if="form.core === 'folia'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="folia_png"
                                            class="w-10 h-10"
                                            alt="Folia" />
                                        <span class="text-xl text-neutral-300">Folia</span>
                                    </span>

                                    <!-- Bedrock Edition cores -->
                                    <span
                                        v-else-if="form.core === 'nukkit'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="nukkit_png"
                                            class="w-10 h-10"
                                            alt="Nukkit" />
                                        <span class="text-xl text-neutral-300">Nukkit</span>
                                    </span>

                                    <span
                                        v-else-if="form.core === 'powernukkitx'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="powernukkitx_png"
                                            class="w-10 h-10"
                                            alt="PowerNukkitX" />
                                        <span class="text-xl text-neutral-300">PowerNukkitX</span>
                                    </span>

                                    <span
                                        v-else-if="form.core === 'pmmp'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="pmmp_png"
                                            class="w-10 h-10"
                                            alt="Pocket-Mine MP" />
                                        <span class="text-xl text-neutral-300">Pocket-Mine MP</span>
                                    </span>

                                    <span
                                        v-else-if="form.core === 'liteloader'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <v-lazy-image
                                            :src="liteloader_png"
                                            class="w-10 h-10"
                                            alt="LiteLoader" />
                                        <span class="text-xl text-neutral-300">LiteLoader</span>
                                    </span>

                                    <!-- Proxy cores -->
                                    <span
                                        v-else-if="form.core === 'bungeecord'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <img
                                            :src="bungee_cord_png"
                                            class="w-10 h-10"
                                            alt="BungeeCord" />
                                        <span class="text-xl text-neutral-300">BungeeCord</span>
                                    </span>

                                    <span
                                        v-else-if="form.core === 'velocity'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <img
                                            :src="velocity_png"
                                            class="w-10 h-10"
                                            alt="Velocity" />
                                        <span class="text-xl text-neutral-300">Velocity</span>
                                    </span>

                                    <span
                                        v-else-if="form.core === 'waterfall'"
                                        class="inline-flex items-center space-x-2 -ml-2">
                                        <img
                                            :src="paper_png"
                                            class="w-10 h-10"
                                            alt="Waterfall" />
                                        <span class="text-xl text-neutral-300">Waterfall</span>
                                    </span>

                                    <span v-else>Выберите ядро</span>
                                    <svg
                                        class="ml-2 -mr-0.5 h-5 w-5 transition-transform duration-150"
                                        :class="{ 'rotate-180': open }"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                                </button>
                            </template>

                            <!-- Java Edition cores -->
                            <template
                                #content
                                v-if="form.type && form.type === 'java'">
                                <DropdownCreationItem
                                    v-if="form.version !== '1.7.10'"
                                    class="w-90"
                                    :img="{
                                        src: paper_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'paper'"
                                    :active="form.core === 'paper'">
                                    Paper
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    v-if="
                                        form.version === 'latest' ||
                                        form.version === '1.18.2' ||
                                        form.version === '1.16.5'
                                    "
                                    class="w-90"
                                    :img="{
                                        src: purpur_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'purpur'"
                                    :active="form.core === 'purpur'">
                                    Purpur
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: spigot_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'spigot'"
                                    :active="form.core === 'spigot'">
                                    Spigot
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    v-if="form.version === 'latest'"
                                    class="w-90"
                                    :img="{
                                        src: folia_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'folia'"
                                    :active="form.core === 'folia'">
                                    Folia
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: forge_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'forge'"
                                    :active="form.core === 'forge'">
                                    Forge
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    v-if="
                                        form.version === 'latest' ||
                                        form.version === '1.18.2' ||
                                        form.version === '1.16.5' ||
                                        form.version === '1.15.2'
                                    "
                                    class="w-90"
                                    :img="{
                                        src: fabric_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'fabric'"
                                    :active="form.core === 'fabric'">
                                    Fabric
                                </DropdownCreationItem>
                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: vanilla_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'vanilla'"
                                    :active="form.core === 'vanilla'">
                                    Vanilla
                                </DropdownCreationItem>
                            </template>

                            <!-- Bedrock Edition cores -->
                            <template
                                #content
                                v-else-if="form.type && form.type === 'bedrock'">
                                <DropdownCreationItem
                                    v-if="form.version !== 'PM4' && form.version !== 'PM5'"
                                    class="w-90"
                                    :img="{
                                        src: vanilla_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'vanilla'"
                                    :active="form.core === 'vanilla'">
                                    Vanilla
                                </DropdownCreationItem>

                                <DropdownCreationItem
                                    v-if="form.version === 'latest'"
                                    class="w-90"
                                    :img="{
                                        src: nukkit_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'nukkit'"
                                    :active="form.core === 'nukkit'">
                                    NukkitX
                                </DropdownCreationItem>

                                <DropdownCreationItem
                                    v-if="form.version === 'latest'"
                                    class="w-90"
                                    :img="{
                                        src: powernukkitx_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'powernukkitx'"
                                    :active="form.core === 'powernukkitx'">
                                    PowerNukkitX
                                </DropdownCreationItem>

                                <DropdownCreationItem
                                    v-if="form.version === 'latest'"
                                    class="w-90"
                                    :img="{
                                        src: pmmp_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'pmmp'"
                                    :active="form.core === 'pmmp'">
                                    PocketMine-MP
                                </DropdownCreationItem>

                                <DropdownCreationItem
                                    v-if="form.version === 'latest'"
                                    class="w-90"
                                    :img="{
                                        src: liteloader_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="form.core = 'liteloader'"
                                    :active="form.core === 'liteloader'">
                                    LiteLoader
                                </DropdownCreationItem>
                            </template>

                            <!-- Proxy cores -->
                            <template
                                #content
                                v-else-if="form.type && form.type === 'proxy' && form.version !== '1.7.10'">
                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: bungee_cord_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="
                                        form.core = 'bungeecord';
                                        form.version === '1.7.10' ? (form.version = 'latest') : true;
                                    "
                                    :active="form.core === 'bungeecord'">
                                    BungeeCord
                                </DropdownCreationItem>

                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: velocity_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="
                                        form.core = 'velocity';
                                        form.version = 'latest';
                                    "
                                    :active="form.core === 'velocity'">
                                    Velocity
                                </DropdownCreationItem>

                                <DropdownCreationItem
                                    class="w-90"
                                    :img="{
                                        src: paper_png,
                                        class: 'w-12 h-12',
                                    }"
                                    @click.prevent="
                                        form.core = 'waterfall';
                                        form.version = 'latest';
                                    "
                                    :active="form.core === 'waterfall'">
                                    Waterfall
                                </DropdownCreationItem>
                            </template>

                            <template
                                #content
                                v-else>
                                <DropdownCreationItem class="w-90">Выберите тип сервера</DropdownCreationItem>
                            </template>
                        </Dropdown>
                        <InputError
                            class="mt-2"
                            :message="form.errors.core" />

                        <!-- Location Buttons -->
                        <p class="font-medium text-lg text-neutral-400 leading-tight mt-8">Выберите расположение</p>
                        <div class="flex flex-wrap gap-y-6 gap-x-4 mt-8">
                            <template
                                v-if="nodes"
                                v-for="node in nodes"
                                :key="node.id">
                                <!-- make node load 95 for production -->
                                <LocationButton
                                    v-if="node.load < 95"
                                    class="h-28 w-max min-w-60"
                                    @click.prevent="form.node = node.ext_id"
                                    :class="{ active: form.node === node.ext_id }">
                                    <div class="flex flex-col text-left">
                                        <div class="flex justify-between mb-2">
                                            <span class="text-lg">{{ node.name.toUpperCase() }}</span>
                                            <span
                                                :class="{
                                                    'bg-green-500/30 border-green-500': node.load <= 50,
                                                    'bg-yellow-500/30 border-yellow-500':
                                                        node.load >= 50 && node.load <= 70,
                                                    'bg-red-500/30 border-red-500': node.load >= 71,
                                                }"
                                                class="bg-green-500/30 px-2 rounded-md border-2 border-green-500 text-neutral-400">
                                                {{ node.load }}%
                                            </span>
                                        </div>
                                        <span class="text-neutral-400 border-b border-neutral-700 pb-1">{{
                                            node.cpu
                                        }}</span>
                                        <div class="inline-flex w-52 space-x-2 items-center pt-1">
                                            <v-lazy-image
                                                v-if="node.location.split('.')[0].toLowerCase() === 'deu'"
                                                :src="deu_png"
                                                class="w-6 h-4 rounded-sm"
                                                alt="Germany" />

                                            <v-lazy-image
                                                v-if="node.location.split('.')[0].toLowerCase() === 'fin'"
                                                :src="fin_png"
                                                class="w-6 h-4 rounded-sm"
                                                alt="Finland" />

                                            <v-lazy-image
                                                v-if="node.location.split('.')[0].toLowerCase() === 'rus'"
                                                :src="rus_png"
                                                class="w-6 h-4 rounded-sm"
                                                alt="Russia" />

                                            <v-lazy-image
                                                v-if="node.location.split('.')[0].toLowerCase() === 'swe'"
                                                :src="swe_png"
                                                class="w-6 h-4 rounded-sm"
                                                alt="Sweden" />

                                            <span class="text-neutral-400">
                                                {{
                                                    $t(
                                                        "locations." +
                                                            node.location.split(".")[0] +
                                                            "." +
                                                            node.location.split(".")[1],
                                                    )
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </LocationButton>
                            </template>
                            <template v-else>
                                <LocationButton
                                    class="animate-pulse h-28 w-60"
                                    :enabled="false">
                                    <div class="mx-auto my-auto">Загрузка...</div>
                                </LocationButton>
                            </template>
                            <InputError
                                class="mt-2"
                                :message="form.errors.node" />
                        </div>
                    </div>

                    <!-- Right Section -->
                    <div class="px-8 pb-8 -mt-7 section__item section__item--end">
                        <div class="border-b border-neutral-800 pb-4 max-w-3xl">
                            <TextInputLight
                                @keydown.enter.prevent
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Введите название сервера"
                                class="w-full mt-[60px] h-16 lg:text-2xl text-lg placeholder-neutral-400 px-4 bg-[#262626] max-w-3xl" />
                            <InputError
                                class="mt-2"
                                :message="form.errors.name" />

                            <!-- Resources -->
                            <div class="flex-col space-y-8 mt-12 max-w-3xl">
                                <!-- RAM -->
                                <div class="flex justify-between w-full max-w-3xl">
                                    <div class="space-y-4 md:w-72 w-screen90">
                                        <div class="flex items-center w-full justify-between">
                                            <div>
                                                <div class="flex space-x-2.5 items-center">
                                                    <span class="text-2xl text-neutral-300">RAM</span>
                                                    <span class="text-neutral-500 text-lg">
                                                        {{ ram_price }}&#x20bd; за 1 GB
                                                    </span>
                                                </div>
                                                <span class="text-neutral-300 mb-3 text-sm">Оперативная память</span>
                                            </div>
                                            <div class="inline-flex items-center space-x-4">
                                                <input
                                                    type="number"
                                                    v-model="form.ram"
                                                    min="0.5"
                                                    :max="ram_max"
                                                    step="0.5"
                                                    class="w-20 bg-neutral-800 rounded-md border-neutral-700 text-neutral-300 focus:border-white focus:ring-white" />
                                            </div>
                                        </div>

                                        <div>
                                            <input
                                                type="range"
                                                v-model="form.ram"
                                                min="0.5"
                                                :max="ram_max"
                                                step="0.5"
                                                class="w-full cursor-pointer slider" />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.ram" />
                                            <div class="text-neutral-400 text-base w-full flex justify-between">
                                                <span>0.5</span>
                                                <span>{{
                                                    Number.isInteger(ram_max) ? ram_max : ram_max.toFixed(1)
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="items-center lg:flex hidden space-x-4 ml-8">
                                        <font-awesome-icon
                                            :icon="faMemory"
                                            style="color: #ffffff"
                                            class="w-20 h-20" />
                                        <div class="">
                                            <div class="tooltip">
                                                <font-awesome-icon
                                                    :icon="faCircleInfo"
                                                    style="color: #737373"
                                                    class="w-4 h-4 pt-1.5 lg:inline hidden" />
                                                <span class="tooltiptext block">
                                                    Чем больше выделено оперативной памяти - тем больше игроков,
                                                    плагинов и модов сможет выдержать сервер без перезапуска.
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- CPU -->
                                <div class="flex justify-between w-full max-w-3xl">
                                    <div class="space-y-4 md:w-72 w-screen90">
                                        <div class="flex items-center w-full justify-between">
                                            <div>
                                                <div class="flex space-x-2.5 items-center">
                                                    <span class="text-2xl text-neutral-300">CPU</span>
                                                    <span class="text-neutral-500 text-lg">
                                                        {{ cpu_price }}&#x20bd; за 1
                                                    </span>
                                                </div>
                                                <span class="text-neutral-300 mb-3 text-sm">Ядра процессора</span>
                                            </div>
                                            <div class="inline-flex items-center space-x-4">
                                                <input
                                                    type="number"
                                                    v-model="form.cpu"
                                                    min="0.5"
                                                    :max="cpu_max"
                                                    step="0.5"
                                                    class="w-20 bg-neutral-800 rounded-md border-neutral-700 text-neutral-300 focus:border-white focus:ring-white" />
                                            </div>
                                        </div>

                                        <div>
                                            <input
                                                type="range"
                                                v-model="form.cpu"
                                                min="0.5"
                                                :max="cpu_max"
                                                step="0.5"
                                                class="w-full cursor-pointer slider" />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.cpu" />
                                            <div class="text-neutral-400 text-base w-full flex justify-between">
                                                <span>0.5</span>
                                                <span>{{
                                                    Number.isInteger(cpu_max) ? cpu_max : cpu_max.toFixed(1)
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="items-center lg:flex hidden space-x-4 ml-8">
                                        <font-awesome-icon
                                            :icon="faMicrochip"
                                            style="color: #ffffff"
                                            class="w-20 h-20" />
                                        <div class="">
                                            <div class="tooltip">
                                                <font-awesome-icon
                                                    :icon="faCircleInfo"
                                                    style="color: #737373"
                                                    class="w-4 h-4 pt-1.5 lg:inline hidden" />
                                                <span class="tooltiptext block">
                                                    От кол-ва выделенных ядер зависит скорость запуска сервера, а также
                                                    производительность. Мы рекомендуем выделять на сервер не менее двух
                                                    ядер.
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- DISK -->
                                <div class="flex justify-between w-full max-w-3xl">
                                    <div class="space-y-4 md:w-72 w-screen90">
                                        <div class="flex items-center w-full justify-between">
                                            <div>
                                                <div class="flex space-x-2.5 items-center">
                                                    <span class="text-2xl text-neutral-300">DISK</span>
                                                    <span class="text-neutral-500 text-lg">
                                                        {{ disk_price }}&#x20bd; за 1 GB
                                                    </span>
                                                </div>
                                                <span class="text-neutral-300 mb-3 text-sm">Дисковое пространство</span>
                                            </div>
                                            <div class="inline-flex items-center space-x-4">
                                                <input
                                                    type="number"
                                                    v-model="form.disk"
                                                    min="0.5"
                                                    :max="disk_max"
                                                    step="0.5"
                                                    class="w-20 bg-neutral-800 rounded-md border-neutral-700 text-neutral-300 focus:border-white focus:ring-white" />
                                            </div>
                                        </div>

                                        <div>
                                            <input
                                                type="range"
                                                v-model="form.disk"
                                                min="0.5"
                                                :max="disk_max"
                                                step="0.5"
                                                class="w-full cursor-pointer slider" />
                                            <InputError
                                                class="mt-2"
                                                :message="form.errors.disk" />
                                            <div class="text-neutral-400 text-base w-full flex justify-between">
                                                <span>0.5</span>
                                                <span>{{
                                                    Number.isInteger(disk_max) ? disk_max : disk_max.toFixed(1)
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="items-center lg:flex hidden space-x-4 ml-8">
                                        <font-awesome-icon
                                            :icon="faHardDrive"
                                            style="color: #ffffff"
                                            class="w-20 h-20" />
                                        <div class="">
                                            <div class="tooltip">
                                                <font-awesome-icon
                                                    :icon="faCircleInfo"
                                                    style="color: #737373"
                                                    class="w-4 h-4 pt-1.5 lg:inline hidden" />
                                                <span class="tooltiptext block">
                                                    На дисковом пространстве хранятся данные сервера. Чем больше места
                                                    выделено - тем больше данных он сможет сохранить.
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section mt-4 text-neutral-300 max-w-3xl">
                            <div class="section__item section__item--start">
                                <p
                                    v-if="nodes"
                                    class="text-5xl">
                                    {{ price_day.toFixed(2) }}&#x20bd;
                                </p>
                                <p
                                    v-else
                                    class="text-5xl">
                                    &#8211;
                                </p>
                                <p class="text-xl text-neutral-500">в день</p>
                            </div>

                            <div class="section__item section__item--end">
                                <p
                                    v-if="nodes"
                                    class="text-5xl">
                                    {{ price_month.toFixed(2) }}&#x20bd;
                                </p>
                                <p
                                    v-else
                                    class="text-5xl">
                                    &#8211;
                                </p>
                                <p class="text-xl text-neutral-500">в месяц</p>
                            </div>
                        </div>

                        <div class="flex text-neutral-300 space-x-2.5 mt-6 items-center">
                            <Checkbox
                                class="h-6 w-6 cursor-pointer"
                                v-model:checked="form.databases" />
                            <span class="text-lg">Базы данных</span>
                            <span class="text-neutral-500 pt-0.5">&#183; &nbsp;бесплатно</span>
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
                        </div>
                        <div class="flex text-neutral-300 space-x-2.5 mt-2 items-center">
                            <Checkbox
                                class="h-6 w-6 cursor-pointer"
                                v-model:checked="form.backups" />
                            <span class="text-lg">Доп. резервные копии</span>
                            <span class="text-neutral-500 pt-0.5">
                                &#183; &nbsp;{{ backup_price }}&#x20bd;/день за GB
                            </span>
                            <div class="tooltip">
                                <font-awesome-icon
                                    :icon="faCircleInfo"
                                    style="color: #737373"
                                    class="w-4 h-4 pt-1.5 lg:inline hidden" />
                                <span class="tooltiptext block">
                                    Обеспечьте сохранность ваших данных с помощью 10 дополнительных резервных копий
                                </span>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center">
                            <TextInput
                                @keypress.enter.prevent="check_promocode()"
                                id="promocode"
                                v-model="form.promocode"
                                autocomplete="promocode"
                                class="rounded-r-none w-44 focus:border-neutral-500 transition-all duration-150 focus:ring-0"
                                placeholder="Промокод" />
                            <button
                                @click.prevent="
                                    check_promocode();
                                    calc_price();
                                "
                                type="button"
                                class="inline-flex items-center px-4 pt-[11px] pb-[13px] border-l-transparent font-['OpenSans-Medium'] rounded-r-md border-neutral-700 border text-neutral-400 bg-neutral-800 hover:text-white focus:outline-none focus:border-white focus:text-white transition ease-in-out duration-150 hover:border-white justify-between">
                                <font-awesome-icon
                                    :icon="faAngleRight"
                                    class="" />
                            </button>
                            <span
                                v-if="promocode_data === 'loading'"
                                class="ml-4 text-yellow-600">
                                Загрузка...
                            </span>
                            <span
                                v-else-if="
                                    promocode_data && form.promocode === promocode_data.code && promocode_data.usable
                                "
                                class="ml-4 text-green-600">
                                Промокод применён, скидка {{ promocode_data.discount }}%
                            </span>
                            <span
                                v-else-if="promocode_data === 'not found' && form.promocode"
                                class="ml-4 text-red-600">
                                Промокод не найден
                            </span>
                            <span
                                v-else-if="
                                    promocode_data && form.promocode === promocode_data.code && !promocode_data.usable
                                "
                                class="ml-4 text-red-600">
                                Этот промокод нельзя использовать
                            </span>
                        </div>
                        <GradientButton
                            v-if="$page.props.auth.user"
                            type="submit"
                            class="w-full mt-6 py-3 text-lg max-w-3xl"
                            >Продолжить
                        </GradientButton>
                        <Link
                            v-else
                            :href="
                                route('dash.servers.create.builder', {
                                    service: form.service,
                                    type: form.type ?? '-',
                                    version: form.version ?? '-',
                                    subtype: form.core ?? '-',
                                    ram: form.ram ?? 4,
                                    cpu: form.cpu ?? 2,
                                    disk: form.disk ?? 2,
                                    name: form.name ?? '',
                                })
                            ">
                            <GradientButton
                                type="button"
                                class="w-full mt-6 py-3 text-lg max-w-3xl"
                                >Продолжить
                            </GradientButton>
                        </Link>
                        <span
                            class="mt-5 text-red-600"
                            v-if="$page.props.errors.server && $page.props.errors.server.includes('LOW_BALANCE')">
                            Недостаточно средств
                        </span>
                    </div>
                </section>
            </div>
        </div>
    </form>
</template>

<style>
/* Tooltip container */
.tooltip {
    position: relative;
    display: inline-block;
}

/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 220px;
    background-color: #0a0a0a;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;

    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;
    top: -5px;
    right: 105%;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>
