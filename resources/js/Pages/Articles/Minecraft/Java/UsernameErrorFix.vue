<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import ArticleTemplate from "@/Components/ArticleTemplate.vue";
import step2_1 from "@pngs/articles/minecraft/java/mods/step2-1.png";
import step11 from "@pngs/articles/minecraft/java/username_error_fix/step11.png";
</script>

<template>
    <ArticleTemplate>
        <template #title> Как исправить "Не удалось проверить имя пользователя"? </template>
        <template #content>
            <span
                >При первом запуске сервера вы можете столкнуться с ошибкой "Не удалось проверить имя пользователя". Это
                случается, если попытаться зайти на сервер с нелицензионного аккаунта, вследствие чего сервер не может
                найти лицензионный аккаунт игрока и выдаёт ошибку</span
            >
            <span
                >По умолчанию все новые сервера Minecraft требуют от всех подключающихся к ним игроков иметь
                лицензионную версию игры. Это позволяет использовать официальную систему скинов и защитить игроков от
                взлома их аккаунтов. Однако не все администраторы хотят ограничивать доступ к своим серверам для игроков
                без лицензии майнкрафт. В таких случаях нужно предпринять ещё несколько шагов для настройки
                сервера.</span
            >

            <span class="border-2 p-4 border-wings-light"
                ><span class="font-['OpenSans-Bold']">Совет:</span> прежде чем отключать проверку лицензии, позаботьтесь
                об обеспечении безопасности сервера. Например, вы можете установить плагин AuthMe Reloaded для
                авторизации всех игроков.</span
            >
            <span class="border-2 p-4 border-red-500"
                ><span class="font-['OpenSans-Bold']">Обратите внимание:</span><br />
                &mdash; Если до отключения проверки на сервер заходил игрок с лицензией, то после отключения его данные
                будут утеряны.
                <br />
                &mdash; После отключения проверки лицензии перестанет работать стандартный whitelist и система банов,
                поэтому для ограничения доступа к серверу нужно будет использовать сторонние плагины.<br />
                &mdash; После отключения проверки лицензии перестанут отображаться скины у игроков, не использующих
                сторонние лаунчеры, добавляющие свои системы скинов в игру. Для отображения скинов используйте сторонние
                плагины.
            </span>
            <span
                ><span class="font-['OpenSans-Bold']">Шаг 1: </span>перейдите в Панель, выберите раздел "Файлы" и
                найдите файл <span class="techtext">server.properties</span>.</span
            >
            <v-lazy-image
                :src="step2_1"
                alt="step 1-1" />
            <v-lazy-image
                :src="step11"
                alt="step 1-2" />
            <span
                ><span class="font-['OpenSans-Bold']">Шаг 2: </span>откройте файл левой кнопкой мыши прямо в
                панели.</span
            >
            <span
                ><span class="font-['OpenSans-Bold']">Шаг 3: </span>найдите в нём строчку
                <span class="techtext">"online-mode=true"</span> и измените её на
                <span class="techtext">"online-mode=false"</span>.</span
            >
            <span><span class="font-['OpenSans-Bold']">Шаг 4: </span>сохраните файл и перезапустите сервер.</span>
        </template>
    </ArticleTemplate>
</template>

<style scoped></style>
