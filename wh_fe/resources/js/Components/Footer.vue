<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import AppLogoLeft from "@/Components/AppLogoLeft.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faDiscord, faTelegram, faVk } from "@fortawesome/free-brands-svg-icons";
import { faCodeBranch } from "@fortawesome/free-solid-svg-icons";
</script>

<template>
    <div class="w-full h-full lg:px-20 py-6 bg-neutral-950 text-neutral-300">
        <div
            class="flex lg:flex-row flex-col lg:items-stretch items-center justify-between border-b border-neutral-800 pb-6">
            <a :href="route('home')">
                <AppLogoLeft class="lg:inline-block w-auto lg:h-14 h-12" />
            </a>
            <a
                class="pt-4 font-['OpenSans-SemiBold'] text-lg lg:mr-32 text-neutral-300"
                href="mailto:support@xwings.host"
                >support@wingshost.ru</a
            >

            <div class="space-x-5 pt-4">
                <a
                    target="_blank"
                    :href="route('social.telegram')">
                    <font-awesome-icon
                        :icon="faTelegram"
                        size="2xl"
                        style="color: #ffffff" />
                </a>
                <a
                    target="_blank"
                    :href="route('social.vk')">
                    <font-awesome-icon
                        :icon="faVk"
                        size="2xl"
                        style="color: #ffffff" />
                </a>
                <a
                    target="_blank"
                    :href="route('social.discord')">
                    <font-awesome-icon
                        :icon="faDiscord"
                        size="2xl"
                        style="color: #ffffff" />
                </a>
            </div>
        </div>
        <div
            class="pt-6 flex lg:flex-row flex-col lg:items-stretch items-center justify-between text-neutral-400 font-['OpenSans-Medium']">
            <span class="text-sm">
                <span class="lg:text-left text-center block">Тихонов Иван Вячеславович</span>
                <span class="lg:text-left text-center block">ИНН 780264488084</span>
                <a
                    href="tel:+7 (931) 252-00-70"
                    class="lg:text-left text-center block"
                    >+7 (931) 252-00-70</a
                >
                <a
                    href="mailto:everstar@wingshost.ru"
                    class="lg:text-left text-center block"
                    >everstar@wingshost.ru</a
                >
                <span class="lg:text-left text-center block">
                    <font-awesome-icon :icon="faCodeBranch" />
                    {{ $page.props.ver }}
                </span>
            </span>
            <span class="text-sm lg:text-left text-center block"
                >&copy; 2020 - {{ new Date().getFullYear() }}
                <span class="font-['Benzin-Semibold'] text-wings-light">WINGS</span>
            </span>
            <div class="block space-y-3">
                <a
                    target="_blank"
                    :href="route('articles.terms-of-service')"
                    class="hover:text-wings-light transition-all ease-in-out duration-150">
                    <p class="lg:text-right text-center">Условия использования</p>
                </a>
                <a
                    target="_blank"
                    :href="route('articles.privacy-policy')"
                    class="hover:text-wings-light transition-all ease-in-out duration-150">
                    <p class="lg:text-left text-center">Политика конфиденциальности</p>
                </a>
            </div>
        </div>
    </div>
</template>
