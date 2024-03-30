<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import ArticleTemplate from "@/Components/ArticleTemplate.vue";
import VLazyImage from "v-lazy-image";
import step1 from "@pngs/articles/minecraft/java/mods/step1.png";
import step2_1 from "@pngs/articles/minecraft/java/mods/step2-1.png";
import step2_2 from "@pngs/articles/minecraft/java/mods/step2-2.png";
</script>

<template>
    <ArticleTemplate>
        <template #title> Как установить моды на сервер? </template>
        <template #content>
            <span
                >Моды - неотъемлемая фишка Майнкрафта, которая делает вашу игру разнообразнее и интереснее. В этой
                статье мы расскажем, как установить их на свой сервер и насладиться от игрой в преображённую игру.</span
            >
            <span class="border-2 p-4 border-wings-light"
                ><span class="font-['OpenSans-Bold']">Совет:</span> устанавливайте моды небольшими группами примерно по
                10 штук, так как если между модами произойдёт конфликт, вы сможете быстро обнаружить проблемный мод и
                удалить его.</span
            >
            <span
                ><span class="font-['OpenSans-Bold']">Шаг 1: </span>выберите <span class="techtext">Forge</span> или
                <span class="techtext">Fabric</span> на этапе создания сервера.</span
            >
            <v-lazy-image
                :src="step1"
                alt="step 1" />
            <span
                ><span class="font-['OpenSans-Bold']">Шаг 2: </span>после создания сервера зайдите в Панель и загрузите
                свои моды в папку <span class="bg-neutral-800 rounded-md p-1">mods</span>.</span
            >
            <v-lazy-image
                :src="step2_1"
                alt="step 2-1" />
            <v-lazy-image
                :src="step2_2"
                alt="step 2-2" />
            <span><span class="font-['OpenSans-Bold']">Шаг 3: </span>Запустите сервер и наслаждайтесь игрой!</span>
        </template>
    </ArticleTemplate>
</template>

<style scoped></style>
