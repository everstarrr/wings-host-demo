<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import minecraft_png from "@pngs/services/create/minecraft.png";
import rust_png from "@pngs/services/create/rust.png";
import csgo_png from "@pngs/services/create/csgo.png";
import samp_png from "@pngs/services/create/samp.png";
import gtav_png from "@pngs/services/create/gtav.png";
import gmod_png from "@pngs/services/create/gmod.png";
import tf2_png from "@pngs/services/create/tf.png";
import unturned_png from "@pngs/services/create/unturned.png";
import arma3_png from "@pngs/services/create/arma3.png";
import database_png from "@pngs/services/create/database.png";
import python_png from "@pngs/services/create/python.png";
import nodejs_png from "@pngs/services/create/nodejs.png";

import VLazyImage from "v-lazy-image";
import ServiceLink from "@/Pages/Servers/Chunks/Components/ServiceLink.vue";
import ServiceDisabledButton from "@/Pages/Servers/Chunks/Components/ServiceDisabledButton.vue";
</script>
<template>
    <div class="lg:w-4/5 sm:px-6 lg:px-9 float-right w-full mt-10 lg:mt-16">
        <!-- Games Grid -->
        <h2 class="font-[Benzin-Medium] text-center text-white text-4xl mb-8 bslines">&nbsp&nbspИГРЫ&nbsp&nbsp</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 font-[Benzin-Semibold] text-2xl mb-12 lg:mx-0 mx-7">
            <ServiceLink :href="route('dash.servers.create.service', 'minecraft')">
                <template #image>
                    <v-lazy-image
                        :src="minecraft_png"
                        class="vli" />
                </template>
                <template #title> MINECRAFT </template>
            </ServiceLink>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="rust_png"
                        class="vli" />
                </template>
                <template #title> RUST </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="csgo_png"
                        class="vli" />
                </template>
                <template #title> CS:GO </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="samp_png"
                        class="vli" />
                </template>
                <template #title> GTA SA:MP </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="gmod_png"
                        class="vli" />
                </template>
                <template #title> GARRY'S MOD </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="gtav_png"
                        class="vli" />
                </template>
                <template #title> GTA V </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="tf2_png"
                        class="vli" />
                </template>
                <template #title> TF 2 </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="unturned_png"
                        class="vli" />
                </template>
                <template #title> UNTURNED </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="arma3_png"
                        class="vli" />
                </template>
                <template #title> ARMA 3 </template>
            </ServiceDisabledButton>
        </div>

        <!-- Services Grid -->
        <h2 class="font-[Benzin-Medium] text-center text-white text-4xl mb-8 bslines">&nbsp&nbspСЕРВИСЫ&nbsp&nbsp</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 font-[Benzin-Semibold] text-2xl mb-10 lg:mx-0 mx-7">
            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="database_png"
                        class="vli" />
                </template>
                <template #title> DATABASE </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="python_png"
                        class="vli" />
                </template>
                <template #title> PYTHON </template>
            </ServiceDisabledButton>

            <ServiceDisabledButton>
                <template #image>
                    <v-lazy-image
                        :src="nodejs_png"
                        class="vli" />
                </template>
                <template #title> NODE.JS </template>
            </ServiceDisabledButton>
        </div>
    </div>
</template>

<style>
.bslines {
    position: relative;
    font-size: 30px;
    z-index: 1;
    overflow: hidden;
    text-align: center;
}

.bslines:before,
.bslines:after {
    position: absolute;
    top: 51%;
    overflow: hidden;
    width: 50%;
    height: 3px;
    content: "\a0";
    background-color: #d4d4d4;
}

.bslines:before {
    margin-left: -50%;
    text-align: right;
}

.vli {
    height: 100%;
    width: 100%;
    object-fit: cover;
    border-radius: 0.5rem;
}
</style>
