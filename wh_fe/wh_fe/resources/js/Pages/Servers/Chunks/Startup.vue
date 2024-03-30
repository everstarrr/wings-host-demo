<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { useForm, usePage, useRemember } from "@inertiajs/vue3";

import Dropdown from "@/Components/Dropdown.vue";
import { reactive, ref } from "vue";
import craftingtable_png from "@pngs/services/minecraft/craftingtable.png";
import bedrock_png from "@pngs/services/minecraft/bedrock.png";
import command_block_png from "@pngs/services/minecraft/command_block.png";
import InputError from "@/Components/InputError.vue";
import DropdownCreationItem from "@/Components/DropdownCreationItem.vue";
import command_block_repeating_png from "@pngs/services/minecraft/command_block_repeating.png";
import azalea_png from "@pngs/services/minecraft/azalea.png";
import netherite_block_png from "@pngs/services/minecraft/netherite_block.png";
import bee_nest_png from "@pngs/services/minecraft/bee_nest.png";
import kelp_png from "@pngs/services/minecraft/kelp.png";
import purple_concrete_powder_png from "@pngs/services/minecraft/purple_concrete_powder.png";
import slime_block_png from "@pngs/services/minecraft/slime_block.png";
import red_sand_png from "@pngs/services/minecraft/red_sand.png";
import paper_png from "@pngs/services/minecraft/paper.png";
import purpur_png from "@pngs/services/minecraft/purpur.png";
import spigot_png from "@pngs/services/minecraft/spigot.png";
import forge_png from "@pngs/services/minecraft/forge.png";
import fabric_png from "@pngs/services/minecraft/fabric.png";
import vanilla_png from "@pngs/services/minecraft/vanilla.png";
import folia_png from "@pngs/services/minecraft/folia.png";
import nukkit_png from "@pngs/services/minecraft/nukkit.png";
import powernukkitx_png from "@pngs/services/minecraft/powernukkitx.png";
import pmmp_png from "@pngs/services/minecraft/pmmp.png";
import liteloader_png from "@pngs/services/minecraft/liteloader.png";
import grassblock_png from "@pngs/services/minecraft/grass_block.png";
import DefaultButton from "@/Components/DefaultButton.vue";

defineProps({
    server: Object,
    disabled: Object,
});

const page = usePage();

const form = useForm(
    reactive({
        service: "",
        type: "",
        version: "",
        core: "",
        node: 0,
        name: "",
        cpu: 0.5,
        ram: 0.5,
        disk: 0.5,
        databases: false,
        backups: false,
        promocode: "",
    }),
);
</script>

<template>
    <div
        class="bg-neutral-900 rounded-2xl border border-neutral-700 overflow-hidden mt-10 mb-10 w-full lg:w-2/3 xl:w-1/2">
        <div class="p-8 bg-neutral-900 overflow-y-scroll hide-scrollbar">
            <!-- Service -->
            <Dropdown
                v-model="form.service"
                width="full"
                align="left">
                <template #trigger="{ open }">
                    <button
                        type="button"
                        class="inline-flex items-center w-full px-6 py-auto h-16 text-md leading-4 font-medium rounded-2xl border-neutral-700 border text-neutral-400 bg-neutral-800 hover:text-white focus:outline-none focus:border-white focus:text-white focus:border-2 transition ease-in-out duration-150 hover:border-white justify-between">
                        <span
                            v-if="form.service === 'minecraft'"
                            class="inline-flex items-center space-x-2 -ml-2">
                            <v-lazy-image
                                :src="grassblock_png"
                                class="w-10 h-10"
                                alt="Minecraft" />
                            <span class="text-xl text-neutral-300">Minecraft</span>
                        </span>
                        <span v-else>Выберите сервис</span>
                        <svg
                            class="ml-2 -mr-0.5 h-5 w-5"
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
                            src: grassblock_png,
                            class: 'w-12 h-12',
                        }"
                        @click.prevent="
                            form.service = 'minecraft';
                            form.type = null;
                            form.version = null;
                            form.core = null;
                        "
                        :active="form.service === 'minecraft'">
                        Minecraft
                    </DropdownCreationItem>
                </template>
            </Dropdown>
            <InputError
                class="mt-2"
                :message="form.errors.type" />

            <!-- Client -->
            <Dropdown
                v-model="form.type"
                width="full"
                align="left">
                <template #trigger="{ open }">
                    <button
                        type="button"
                        class="mt-8 inline-flex items-center w-full px-6 py-auto h-16 text-md leading-4 font-['OpenSans-Medium'] rounded-2xl border-neutral-700 border text-neutral-400 bg-neutral-800 hover:text-white focus:outline-none focus:border-white focus:text-white focus:border-2 transition ease-in-out duration-150 hover:border-white justify-between">
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
                            class="ml-2 -mr-0.5 h-5 w-5"
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
                    <template v-if="form.service">
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
                    <template v-else>
                        <DropdownCreationItem class="w-90">Выберите тип сервера</DropdownCreationItem>
                    </template>
                </template>
            </Dropdown>
            <InputError
                class="mt-2"
                :message="form.errors.type" />

            <!-- Verisons -->
            <Dropdown
                width="full"
                align="left">
                <!-- Versions -->
                <template #trigger="{ open }">
                    <button
                        type="button"
                        class="mt-8 inline-flex items-center w-full px-6 py-auto h-16 text-md leading-4 font-['OpenSans-Medium'] rounded-2xl border-neutral-700 border text-neutral-400 bg-neutral-800 hover:text-white focus:outline-none focus:border-white focus:text-white focus:border-2 transition ease-in-out duration-150 hover:border-white justify-between">
                        <span
                            v-if="form.version === 'latest'"
                            class="inline-flex items-center space-x-2 -ml-2">
                            <v-lazy-image
                                :src="command_block_repeating_png"
                                class="w-10 h-10"
                                alt="1.18.2" />
                            <span class="text-xl text-neutral-300">Последняя версия</span>
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
                            class="ml-2 -mr-0.5 h-5 w-5"
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

                <!-- Versions dropdown content -->
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
                            Последняя версия
                        </DropdownCreationItem>

                        <DropdownCreationItem
                            class="w-90"
                            @click.prevent="form.version = '1.19.80.23'"
                            :active="form.version === '1.19.80.23'">
                            1.19.80.23
                        </DropdownCreationItem>

                        <DropdownCreationItem
                            class="w-90"
                            @click.prevent="form.version = '1.18.33'"
                            :active="form.version === '1.18.33'">
                            1.18.33
                        </DropdownCreationItem>

                        <DropdownCreationItem
                            class="w-90"
                            @click.prevent="form.version = '1.17.41'"
                            :active="form.version === '1.17.41'">
                            1.17.41
                        </DropdownCreationItem>

                        <DropdownCreationItem
                            class="w-90"
                            @click.prevent="form.version = '1.16.221'"
                            :active="form.version === '1.16.221'">
                            1.16.221
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
                <!-- cores -->
                <template #trigger="{ open }">
                    <button
                        type="button"
                        class="mt-8 inline-flex items-center w-full px-6 py-auto h-16 text-md leading-4 font-['OpenSans-Medium'] rounded-2xl border-neutral-700 border text-neutral-400 bg-neutral-800 hover:text-white focus:outline-none focus:border-white focus:text-white focus:border-2 transition ease-in-out duration-150 hover:border-white justify-between">
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

                        <span v-else>Выберите ядро</span>
                        <svg
                            class="ml-2 -mr-0.5 h-5 w-5"
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
                    v-if="form.type && form.type !== 'bedrock'">
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
                        v-if="form.version === 'latest' || form.version === '1.18.2' || form.version === '1.16.5'"
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

                <template
                    #content
                    v-else-if="form.type">
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

                    <DropdownCreationItem
                        class="w-90"
                        :img="{
                            src: nukkit_png,
                            class: 'w-12 h-12',
                        }"
                        @click.prevent="form.core = 'nukkit'"
                        :active="form.core === 'nukkit'">
                        Nukkit
                    </DropdownCreationItem>

                    <DropdownCreationItem
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
                <template
                    #content
                    v-else>
                    <DropdownCreationItem class="w-90">Выберите тип сервера</DropdownCreationItem>
                </template>
            </Dropdown>
            <InputError
                class="mt-2"
                :message="form.errors.core" />
            <p class="text-neutral-400 ml-0.5 text-base mb-0.5 mt-8">Команда запуска</p>
            <textarea
                class="textarea rounded-md bg-neutral-800 border-neutral-700 border min-w-full max-w-lg focus:ring-transparent focus:border-white text-neutral-300" />
            <DefaultButton class="w-48 mt-6"> Сохранить </DefaultButton>
        </div>
    </div>
</template>

<style>
.textarea {
    font-family: inherit;
    font-size: inherit;
    padding: 8px;
    display: block;
    width: 100%;
    overflow: hidden;
    resize: both;
    min-height: 40px;
    line-height: 20px;
}

.textarea[contenteditable]:empty::before {
    content: "Placeholder still possible";
    color: gray;
}
</style>
