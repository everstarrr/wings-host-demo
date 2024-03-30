<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import vanilla_c from "@pngs/vanilla_c.jpg";
import { Link } from "@inertiajs/vue3";
import mods from "@pngs/articles/minecraft/java/mods/mods.png";
import Welcome from "@/Components/Welcome.vue";
import username_error_fix from "@pngs/articles/minecraft/java/username_error_fix/username_error.png";
import proxy from "@pngs/articles/minecraft/java/proxy/proxy.jpg";
//import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { ref } from "vue";
</script>

<template>
    <AppLayout
        :title="$t('nav.articles')"
        :show-angle="false">
        <template #header>
            <h2 class="font-semibold text-xl text-neutral-950 leading-tight">
                {{ $t("nav.articles") }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    <Link
                        :href="route('articles.minecraft.java.install.mods')"
                        class="motion-safe:hover:scale-[1.01] transition-all duration-250 scale-100 space-y-2 flex flex-col">
                        <v-lazy-image
                            :src="mods"
                            alt="minecraft"
                            class="rounded-lg ease-in-out duration-150 md:h-48" />
                        <div class="flex flex-col space-y-1">
                            <p class="text-neutral-300 text-lg sm:text-xl font-['OpenSans-SemiBold']">
                                Как установить моды на сервер?
                            </p>
                            <span class="text-neutral-500 text-sm font-['OpenSans-Medium']"
                                >Подробный гайд по установке модов и ресурспаков на ваш сервер</span
                            >
                        </div>
                        <span class="text-neutral-500 text-sm font-['OpenSans-Medium']">04.12.2023 · Minecraft</span>
                    </Link>

                    <Link
                        :href="route('articles.minecraft.java.username-error-fix')"
                        class="motion-safe:hover:scale-[1.01] transition-all duration-250 scale-100 space-y-2 flex flex-col">
                        <v-lazy-image
                            :src="username_error_fix"
                            alt="username error fix"
                            class="rounded-lg ease-in-out duration-150 md:h-48" />
                        <div class="flex flex-col space-y-1">
                            <p class="text-neutral-300 text-lg sm:text-xl font-['OpenSans-SemiBold']">
                                Как исправить "Не удалось проверить имя пользователя"?
                            </p>
                            <span class="text-neutral-500 text-sm font-['OpenSans-Medium']"
                                >В этом гайде мы расскажем, как решить эту часто встречающуюся у новичков ошибку</span
                            >
                        </div>
                        <span class="text-neutral-500 text-sm font-['OpenSans-Medium']">04.12.2023 · Minecraft</span>
                    </Link>

                    <Link
                        :href="route('articles.minecraft.java.setup-proxy')"
                        class="motion-safe:hover:scale-[1.01] transition-all duration-250 scale-100 space-y-2 flex flex-col">
                        <img
                            :src="proxy"
                            alt="proxy"
                            class="rounded-lg ease-in-out duration-150 md:h-48" />
                        <div class="flex flex-col space-y-1">
                            <p class="text-neutral-300 text-lg sm:text-xl font-['OpenSans-SemiBold']">
                                Как настроить Minecraft Proxy?
                            </p>
                            <span class="text-neutral-500 text-sm font-['OpenSans-Medium']"
                                >В этом гайде мы расскажем, как правильно настроить прокси и создать хаб на
                                сервере.</span
                            >
                        </div>
                        <span class="text-neutral-500 text-sm font-['OpenSans-Medium']">04.01.2024 · Minecraft</span>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
a:hover p {
    text-decoration: underline;
}
</style>
