<script setup>
import craftingtable_png from "@pngs/services/minecraft/craftingtable.png";
import bedrock_png from "@pngs/services/minecraft/bedrock.png";
import command_block_png from "@pngs/services/minecraft/command_block.png";
import Dropdown from "@/Components/Dropdown.vue";

const emit = defineEmits(["sendClient"]);
</script>

<template>
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
</template>

<style scoped></style>
