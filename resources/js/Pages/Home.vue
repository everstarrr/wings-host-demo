<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavBar from "@/Components/NavBar.vue";
import explosion_png from "@pngs/explosion.png";
import {
    faClock,
    faClockRotateLeft,
    faGear,
    faSliders,
    faQuestion,
    faHeadset,
} from "@fortawesome/free-solid-svg-icons";
import Footer from "@/Components/Footer.vue";
import PresetButton from "@/Components/PresetButton.vue";
import Accordion from "@/Components/Accordion.vue";
import VLazyImage from "v-lazy-image";
import Advantage from "@/Components/Advantage.vue";
import { ref } from "vue";
import BorderButton from "@/Components/BorderButton.vue";
import GradientButton from "@/Components/GradientButton.vue";
import ArticleItem from "@/Components/ArticleItem.vue";

function scrollToFAQ() {
    const aimElement = document.getElementById("faq");
    if (aimElement) {
        aimElement.scrollIntoView({ behavior: "smooth" });
    }
}

const balance = ref(null);
const get_balance = async () => {
    await axios
        .get(route("api.v1.payments.balance"))
        .then(function (response) {
            balance.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                balance.value = err;
            }
        });
};

setTimeout(function () {
    get_balance();
}, 1000);
</script>

<template>
    <NavBar class="border-transparent" />
    <Head :title="$t('nav.main')">
        <title>{{ $t("nav.main") }}</title>
    </Head>

    <!-- All Page -->
    <main>
        <section class="w-full bg-neutral-950 overflow-x-hidden p-6 lg:p-8">
            <!-- Margins for all elements -->
            <div class="2xl:mt-12 md:mt-20 mt-16 text-white lg:mx-10 mx-4">
                <!-- Text and two buttons -->
                <div class="lg:flex block items-center justify-between">
                    <div class="text-center lg:text-left lg:text-5xl text-3xl wqhd:text-7xl">
                        <h1 class="font-['Benzin-Medium']">Пожалуй, самый стабильный хостинг.</h1>
                        <p class="mt-5 lg:mt-10 font-['Benzin-Medium']">Выбирайте надёжность.</p>
                        <p class="mt-5 lg:mt-10 font-['Benzin-Medium']">
                            Выбирайте <span class="text-wings-light">WINGS</span>.
                        </p>
                        <Link :href="route('dash.servers.create')">
                            <GradientButton class="h-14 w-full sm:w-72 mx-auto mt-16 pt-3">
                                Создать сервер
                            </GradientButton>
                        </Link>
                        <BorderButton
                            @click="scrollToFAQ"
                            class="block md:inline-flex w-full md:mt-16 mt-5 md:ml-10 pt-3 bg-transparent h-14 sm:w-72">
                            О хостинге
                        </BorderButton>
                    </div>

                    <v-lazy-image
                        :src="explosion_png"
                        alt="Explosion"
                        class="w-2/5 h-2/5 wqhd:h-1/3 wqhd:w-1/3 xl:block hidden" />
                </div>

                <!-- Trending -->
                <h3 class="block 2xl:mt-4 mt-12 font-['Benzin-Medium'] text-4xl">Популярное</h3>
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 font-['OpenSans-Medium'] text-2xl mt-12 mb-10">
                    <PresetButton
                        class="bg-bedrock"
                        :link="route('services.minecraft.builder', ['java', 2, 1, 1, true])"
                        :free="!$page.props.auth.user || (balance && balance.current.total_spent === 0)">
                        <template #type>MINECRAFT</template>
                        <template
                            #title
                            v-if="!$page.props.auth.user || (balance && balance.current.total_spent === 0)"
                            >Бесплатный тестовый сервер на 2 дня
                        </template>
                        <template
                            #title
                            v-else
                            >Небольшой сервер для теста
                        </template>
                        <template #rate>2 GB RAM | 1 CPU | 1 GB HDD</template>
                    </PresetButton>

                    <PresetButton
                        class="bg-vanilla"
                        :link="route('services.minecraft.builder', ['java', 8, 3, 6])">
                        <template #type>MINECRAFT</template>
                        <template #title> Классический Vanilla-сервер на ~15 человек</template>
                        <template #rate>8 GB RAM | 3 CPU | 6 GB HDD</template>
                    </PresetButton>

                    <PresetButton
                        class="bg-minecraft"
                        :link="route('services.minecraft.builder', ['java', 6, 2, 8])">
                        <template #type>MINECRAFT</template>
                        <template #title> Коммерческий Java Edition 1.12.2 сервер для начинающих</template>
                        <template #rate>6 GB RAM | 2 CPU | 8 GB HDD</template>
                    </PresetButton>
                </div>

                <!-- Articles -->
                <div class="mt-24">
                    <div class="flex w-full justify-between">
                        <h3 class="font-['Benzin-Medium'] text-4xl">Статьи</h3>
                        <!-- View more for desktops -->
                        <Link
                            :href="route('articles')"
                            class="float-right rounded-lg hidden md:inline-block border-neutral-700 pt-2.5 hover:border-neutral-300 hover:border-3 border-2 h-12 w-max px-3 font-['OpenSans-Medium']">
                            Смотреть больше
                        </Link>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 font-['OpenSans-Medium'] text-2xl mt-12 mb-10">
                        <ArticleItem
                            :bg="'bg-mods_article'"
                            :href="route('articles.minecraft.java.install.mods')">
                            <template #category>MINECRAFT</template>
                            <template #title>Как установить моды на сервер?</template>
                            <template #description>
                                Подробный гайд по установке модов и ресурспаков на ваш сервер
                            </template>
                        </ArticleItem>

                        <ArticleItem
                            :bg="'bg-username_error_article'"
                            :href="route('articles.minecraft.java.username-error-fix')">
                            <template #category>MINECRAFT</template>
                            <template #title> Как исправить "Не удалось проверить имя пользователя"? </template>
                            <template #description> Подробное руководство по решению проблемы </template>
                        </ArticleItem>

                        <ArticleItem
                            :bg="'bg-proxy_article'"
                            :href="route('articles.minecraft.java.setup-proxy')">
                            <template #category>MINECRAFT</template>
                            <template #title>Как настроить Minecraft Proxy?</template>
                            <template #description>
                                В этом гайде мы расскажем, как правильно настроить прокси и создать хаб на сервере.
                            </template>
                        </ArticleItem>
                    </div>

                    <!-- View more for phones -->
                    <Link
                        :href="route('articles')"
                        class="rounded-lg md:hidden inline-block border-neutral-700 hover:border-neutral-300 hover:border-3 border-2 h-12 w-max px-3 font-['OpenSans-Medium'] mb-3 transition-all pt-2.5 ease-in-out duration-150">
                        Смотреть больше
                    </Link>
                </div>
            </div>
        </section>

        <!-- Why we? -->
        <section class="relative flex flex-col bg-wingsFade w-full">
            <h2 class="font-['Benzin-Medium'] text-center lg:text-5xl text-3xl text-white mt-10">Наши преимущества</h2>
            <div class="text-white lg:mx-10 mx-4 p-6 lg:p-8">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 font-['OpenSans-Medium'] lg:mt-16 mt-12 mb-10">
                    <Advantage :user-icon="faGear">
                        <template #title> Удобная панель управления</template>
                        <template #description>
                            Наша панель интуитивно понятна и проста в использовании, даже если вы не разбираетесь в
                            настройке серверов.
                        </template>
                    </Advantage>

                    <Advantage :user-icon="faHeadset">
                        <template #title> Быстрая поддержка</template>
                        <template #description>
                            Мы в кратчайшие сроки решим Ваши проблемы и проконсультируем по возникшим вопросам.
                        </template>
                    </Advantage>

                    <Advantage :user-icon="faSliders">
                        <template #title> Гибкая настройка</template>
                        <template #description>
                            Выделяйте для Вашего сервера столько мощностей, сколько вам требуется, без фиксированных
                            тарифов.
                        </template>
                    </Advantage>

                    <Advantage :user-icon="faClock">
                        <template #title> Оплата по дням</template>
                        <template #description>
                            Вместо помесячной оплаты Вы можете арендовать сервер на промежуток времени от 1 дня.
                        </template>
                    </Advantage>

                    <Advantage :user-icon="faClockRotateLeft">
                        <template #title> Резервные копии</template>
                        <template #description>
                            Предоставляем 3 бесплатные резервные копии для обеспечения сохранности ваших файлов.
                        </template>
                    </Advantage>

                    <Advantage :user-icon="faQuestion">
                        <template #title>
                            {{ Math.floor(Math.random() * (30 - 1 + 1) + 1) }}.0{{ new Date().getMonth() + 3 }}.{{
                                new Date().getFullYear()
                            }}
                        </template>
                        <template #description>
                            Мы постоянно развиваемся и улучшаем наш проект, поэтому нововведение не заставит себя долго
                            ждать.
                        </template>
                    </Advantage>
                </div>
            </div>
        </section>

        <section class="flex flex-col bg-neutral-950 w-full pb-20">
            <h2
                class="font-['Benzin-Medium'] text-center lg:text-5xl text-3xl text-white mt-20"
                id="faq">
                FAQ
            </h2>

            <p class="font-medium text-neutral-400 lg:text-xl text-lg my-12 text-center px-4">
                В этом разделе вы можете найти ответы на самые популярные вопросы.
            </p>

            <!-- Hardware -->
            <Accordion class="px-4">
                <template #title> Какое оборудование вы используете?</template>
                <template #content>
                    Мы используем только самые качественные сервера в странах восточной Европы с современным
                    оборудованием, позволяющим обеспечить купленный сервер выделенными ему ресурсами и оптимальным
                    пингом.
                </template>
            </Accordion>

            <!-- Hardware -->
            <Accordion class="px-4">
                <template #title> За что отвечает поддержка?</template>
                <template #content>
                    Поддержка создаёт заявки на исправление технических неполадок, а так же помогает с решением
                    вопросов, требующих её вмешательства. Иногда, прежде чем писать в поддержку, уместнее будет задать
                    интересующий Вас вопрос другим пользователям в чатах в Telegram, VK или Discord. Кроме того, ответы
                    на некоторые вопросы Вы можете найти в разделе "Статьи".
                </template>
            </Accordion>

            <!-- Hardware -->
            <Accordion class="px-4">
                <template #title>Можно ли приобрести выделенный сервер?</template>
                <template #content>
                    Да, можно, но пока что только через поддержку. В будущем мы планируем сделать этот процесс более
                    автоматизированным.
                </template>
            </Accordion>
        </section>
    </main>
    <Footer />
</template>
