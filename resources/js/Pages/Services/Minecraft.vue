<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import NavBar from "@/Components/NavBar.vue";
import purplelight_png from "@pngs/purplelight.png";
import purplelight_r_png from "@pngs/purplelight_right.png";
import { faClock, faGear, faSliders, faHeadset } from "@fortawesome/free-solid-svg-icons";
import Footer from "@/Components/Footer.vue";
import InputError from "@/Components/InputError.vue";
import { reactive, readonly, ref, watch } from "vue";
import Accordion from "@/Components/Accordion.vue";
import PresetButton from "@/Components/PresetButton.vue";
import BorderButton from "@/Components/BorderButton.vue";
import WhyWeItem from "@/Pages/Services/Components/WhyWeItem.vue";

const props = defineProps({
    plan: Object,
    type: String,
    version: String,
    subtype: String,
    ram: Number,
    cpu: Number,
    disk: Number,
    is_free: Boolean,
});

function scrollToCalculator() {
    const aimElement = document.getElementById("calculator");
    if (aimElement) {
        aimElement.scrollIntoView({ behavior: "smooth" });
    }
}

watch(
    () => [props.type, props.cpu, props.ram, props.disk, props.is_free],
    async () => {
        if (props.type || props.cpu || props.ram || props.disk || props.is_free) {
            setTimeout(function () {
                scrollToCalculator();
            }, 1000);
        }
    },
    {
        immediate: true,
    },
);

const page = usePage();

const ram_max = ref(16);
const cpu_max = ref(8);
const disk_max = ref(50);

const form = useForm(
    reactive({
        cpu: props.cpu ?? 0.5,
        ram: props.ram ?? 0.5,
        disk: props.disk ?? 0.5,
    }),
);

const free_test = ref(props.is_free ?? false);
const type = ref(props.type ?? "java");
const version = ref(free_test.value ? "1.13.2" : props.version ?? "latest");
const subtype = ref(props.subtype ?? "paper");

// PRICE
const price_day = ref(-1);
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
                node: null,
                cpu: form.cpu,
                ram: form.ram,
                disk: form.disk,
                backups: false,
                databases: false,
                promocode: "",
            },
            {},
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

watch(
    () => [form.cpu, form.ram, form.disk],
    async () => {
        price_day.value = form.ram * ram_price.value + form.cpu * cpu_price.value + form.disk * disk_price.value;
        price_month.value = price_day.value * 30;

        /*if (free_test.value && form.cpu > 1 && form.ram > 2 && form.disk > 1) {

            free_test.value = false;
        }*/
    },
    {
        immediate: true,
    },
);

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
    calc_price();
    get_balance();
}, 500);

const whywe = readonly([
    {
        icon: faGear,
        title: "Удобная панель управления",
        content:
            "Наша панель интуитивно понятна и проста в использовании, вы разберётесь в ней, даже если\n" +
            "никогда не занимались настройкой серверов. Кроме того, мы добавили косметические улучшения,\n" +
            "чтобы сделать её максимально приятной для вас.",
    },
    {
        icon: faHeadset,
        title: "Быстрая поддержка",
        content:
            "В нашей поддержке работают профессионалы своего дела, которые оперативно реагируют на\n" +
            "запросы, и всегда готовы помочь вам решить возникшие вопросы.",
    },
    {
        icon: faSliders,
        title: "Гибкая настройка",
        content:
            "Вы можете приобрести ровно столько мощностей, сколько нужно вам, ведь у нас нет фиксрованных\n" +
            "тарифов - лишь рекомендации на главной странице.",
    },
    {
        icon: faClock,
        title: "Оплата по дням",
        content:
            "Вы можете арендовывать мощности ровно на тот срок, который требуется, вместо того чтобы\n" +
            "платить за целый месяц.",
    },
]);

// YANDEX METRICS
(function (m, e, t, r, i, k, a) {
    m[i] =
        m[i] ||
        function () {
            (m[i].a = m[i].a || []).push(arguments);
        };
    m[i].l = 1 * new Date();
    for (var j = 0; j < document.scripts.length; j++) {
        if (document.scripts[j].src === r) {
            return;
        }
    }
    (k = e.createElement(t)),
        (a = e.getElementsByTagName(t)[0]),
        (k.async = 1),
        (k.src = r),
        a.parentNode.insertBefore(k, a);
})(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

ym(96332558, "init", {
    clickmap: true,
    trackLinks: true,
    accurateTrackBounce: true,
    webvisor: true,
});
</script>

<template>
    <NavBar class="border-transparent" />
    <Head :title="$t('nav.main')"
        ><title>{{ $t("nav.main") }}</title></Head
    >

    <main>
        <!-- Header element -->
        <section class="w-full bg-minecraftbg_dark bg-cover h-screen overflow-hidden">
            <!-- Margins for all elements -->
            <div class="xl:mt-12 text-white flex items-center">
                <!-- Elements -->
                <div class="text-center my-44 sm:my-44 mx-auto">
                    <h1 class="font-['Benzin-Medium'] text-white lg:text-5xl text-3xl">
                        ПОЖАЛУЙ, САМЫЙ СТАБИЛЬНЫЙ<br />
                        МАЙНКРАФТ-ХОСТИНГ
                    </h1>
                    <p class="font-medium text-neutral-400 lg:text-2xl text-lg mt-12">
                        Компетентная поддержка, низкие цены, гибкая настройка.<br />
                        Доверь свой сервер профессионалам.
                    </p>
                    <Link :href="route('dash.servers.create.service', 'minecraft')">
                        <BorderButton class="mt-16 w-72 h-14 mx-auto"> Создать сервер </BorderButton>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Why we? -->
        <section class="flex flex-col bg-neutral-950 w-full">
            <v-lazy-image
                :src="purplelight_png"
                alt="purplelight"
                class="absolute z-10" />
            <h2 class="font-['Benzin-Medium'] text-center lg:text-5xl text-3xl text-white mt-20">Почему мы?</h2>
            <div class="text-white lg:mx-28 mx-4 p-6 lg:p-8">
                <!-- Grid with cells -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-10 font-['OpenSans-Medium'] lg:mt-16 mt-12 mb-10">
                    <WhyWeItem
                        v-for="item in whywe"
                        :icon="item.icon">
                        <template #title>
                            {{ item.title }}
                        </template>

                        <template #content>
                            {{ item.content }}
                        </template>
                    </WhyWeItem>
                </div>
            </div>
        </section>

        <!-- Rates -->
        <section class="flex flex-col bg-neutral-950 w-full">
            <h2 class="font-['Benzin-Medium'] text-center lg:text-5xl text-3xl text-white mt-20">Тарифы</h2>
            <v-lazy-image
                :src="purplelight_r_png"
                alt="purplelight"
                class="absolute" />

            <!-- Description -->
            <p class="font-medium text-neutral-400 lg:text-xl text-lg mt-12 text-center lg:mx-64 mx-10 z-10">
                Вы всегда можете настроить свой тариф самостоятельно
                <button
                    @click="scrollToCalculator"
                    class="text-wings hover:text-wings-light duration-150">
                    ниже</button
                >, но для удобства мы составили оптимальные пресеты для разных категорий серверов.
            </p>

            <!-- Grid with presets -->
            <div class="text-white lg:mx-28 mx-4 p-6 lg:p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-10 font-medium text-2xl mt-12 mb-10">
                    <PresetButton
                        class="bg-bedrock"
                        @click="
                            form.ram = 2;
                            form.cpu = 1;
                            form.disk = 1;
                            type = 'java';
                            version = '1.13.2';
                            !$page.props.auth.user ||
                            ($page.props.auth.user && balance && balance.personal.total_spent === 0)
                                ? (free_test = true)
                                : (free_test = false);
                            scrollToCalculator();
                        "
                        :free="
                            !$page.props.auth.user ||
                            ($page.props.auth.use && balance && balance.current.total_spent === 0)
                        ">
                        <template #type>MINECRAFT</template>
                        <template
                            #title
                            v-if="
                                !$page.props.auth.user ||
                                ($page.props.auth.user && balance && balance.current.total_spent === 0)
                            "
                            >Бесплатный тестовый сервер на 2 дня</template
                        >
                        <template
                            #title
                            v-else
                            >Небольшой сервер для теста</template
                        >
                        <template #rate>2 GB RAM | 1 CPU | 1 GB HDD</template>
                    </PresetButton>

                    <PresetButton
                        @click="
                            form.ram = 6;
                            form.cpu = 4;
                            form.disk = 10;
                            scrollToCalculator();
                        "
                        class="bg-minecraft">
                        <template #type>MINECRAFT</template>
                        <template #title> Коммерческий Java Edition 1.12.2 сервер для начинающих </template>

                        <template #rate>10 GB RAM | 3 CPU | 16 GB HDD</template>
                    </PresetButton>

                    <PresetButton
                        @click="
                            form.ram = 2;
                            form.cpu = 2;
                            form.disk = 1;
                            type = 'proxy';
                            scrollToCalculator();
                        "
                        class="bg-hub">
                        <template #type>MINECRAFT</template>
                        <template #title> Proxy для лобби/хаба на твоём сервере </template>

                        <template #rate>2 GB RAM | 2 CPU | 1 GB HDD</template>
                    </PresetButton>

                    <PresetButton
                        @click="
                            form.ram = 8;
                            form.cpu = 3;
                            form.disk = 16;
                            scrollToCalculator();
                        "
                        class="bg-vanilla">
                        <template #type>MINECRAFT</template>
                        <template #title> Классический Vanilla-сервер на ~15 человек </template>

                        <template #rate>6 GB RAM | 1 CPU | 6 GB HDD</template>
                    </PresetButton>

                    <PresetButton
                        @click="
                            form.ram = 16;
                            form.cpu = 8;
                            form.disk = 50;
                            type = 'java';
                            scrollToCalculator();
                        "
                        class="bg-roleplay">
                        <template #type>MINECRAFT</template>
                        <template #title> Максимальный сервер для любых задач </template>
                        <template #rate>16 GB RAM | 8 CPU | 50 GB HDD</template>
                    </PresetButton>

                    <!--                    <PresetButton-->
                    <!--                        @click="-->
                    <!--                            form.ram = 6;-->
                    <!--                            form.cpu = 2;-->
                    <!--                            form.disk = 5;-->
                    <!--                            type = 'java';-->
                    <!--                            scrollToCalculator();-->
                    <!--                        "-->
                    <!--                        class="bg-friends">-->
                    <!--                        <template #type>MINECRAFT</template>-->
                    <!--                        <template #title> Небольшой Java Edition сервер для игры с друзьями </template>-->
                    <!--                        <template #rate>6 GB RAM | 1 CPU | 6 GB HDD</template>-->
                    <!--                    </PresetButton>-->

                    <PresetButton
                        class="bg-mods_article"
                        @click="
                            form.ram = 12;
                            form.cpu = 4;
                            form.disk = 10;
                            type = 'java';
                            !$page.props.auth.user ||
                            ($page.props.auth.user && balance && balance.personal.total_spent === 0)
                                ? (free_test = true)
                                : (free_test = false);
                            scrollToCalculator();
                        ">
                        <template #type>MINECRAFT</template>
                        <template #title>Сервер для игры с модами</template>

                        <template #rate>12 GB RAM | 4 CPU | 10 GB HDD</template>
                    </PresetButton>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section class="flex flex-col bg-neutral-950 w-full py-20">
            <v-lazy-image
                :src="purplelight_r_png"
                alt="purplelight"
                class="absolute z-10 lg:left-1/4" />
            <h2 class="font-['Benzin-Medium'] text-center lg:text-5xl text-3xl text-white">FAQ</h2>

            <p class="font-medium text-neutral-400 lg:text-xl text-lg my-12 text-center px-4">
                В этом разделе вы можете найти ответы на самые популярные вопросы.
            </p>

            <!-- Mods -->
            <Accordion class="border-t">
                <template #title> Можно ли установить моды на сервер? </template>
                <template #content>
                    Да, вы можете запустить сервер с модами, выбрав ядро Forge или Fabric при создании сервера.
                </template>
            </Accordion>

            <!-- BungeeCord and Velocity -->
            <Accordion>
                <template #title> Можно ли установить BungeeCord и Velocity, а также их форки? </template>
                <template #content> Да, разумеется. </template>
            </Accordion>

            <!-- Help -->
            <Accordion>
                <template #title> Помогут ли мне с установкой сервера? </template>
                <template #content>
                    Да, но только предоставлением хостинга. Настраивать плагины, устанавливать моды и т.д. Вы должны
                    сами. Кроме того, вам могут помочь другие клиенты в Discord-сервере или VK-чате. Просим вас писать в
                    поддержку только по действительно важным вопросам и техническим проблемам.
                </template>
            </Accordion>

            <!-- Ports -->
            <Accordion>
                <template #title> Доступны ли порты 19132 и 25565? </template>
                <template #content>
                    Нет, за редким исключением. Для Java-серверов можно установить SRV DNS запись.
                </template>
            </Accordion>
        </section>

        <!-- Calculator -->

        <section class="flex flex-col bg-neutral-950 w-full sm:py-20 pt-20 pb-8">
            <h2
                class="font-['Benzin-Medium'] text-center lg:text-5xl text-3xl text-white"
                id="calculator">
                Калькулятор цены
            </h2>

            <!-- Description -->
            <p class="font-medium text-neutral-400 lg:text-xl text-lg mt-12 text-center px-4">
                Рассчитайте среднюю стоимость стоимость сервера при помощи этого калькулятора.
            </p>

            <div class="text-white mx-auto lg:block md:flex justify-center md:space-x-12 lg:space-x-0 z-20">
                <!-- Flexbox with sliders -->
                <div class="w-full flex space-y-12 flex-col mt-12 mb-8">
                    <div class="flex lg:flex-row flex-col lg:space-x-16">
                        <!-- RAM -->
                        <div class="flex lg:flex-col flex-row space-y-2 space-x-8 lg:space-x-0">
                            <div class="space-y-4 md:w-72 w-screen90">
                                <div class="flex items-center w-full justify-between">
                                    <div>
                                        <div class="flex space-x-2.5 items-center">
                                            <span class="text-2xl text-neutral-300">{{ $t("servers.ram") }}</span>
                                            <span class="text-neutral-500 text-lg">
                                                {{ ram_price }}&#x20bd; за 1 GB
                                            </span>
                                        </div>
                                        <span class="text-neutral-300 mb-3 text-sm">{{ $t("servers.ram.full") }}</span>
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
                                        <span>{{ ram_max }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="w-72 text-neutral-400 text-sm md:block hidden"
                                >Чем больше выделено оперативной памяти, тем больше игроков, плагинов и модов сможет
                                выдержать сервер без перезапуска.</span
                            >
                        </div>

                        <!-- CPU -->
                        <div class="flex lg:flex-col flex-row space-y-2 space-x-8 lg:space-x-0">
                            <div class="space-y-4 md:w-72 w-screen90">
                                <div class="flex items-center w-full justify-between">
                                    <div>
                                        <div class="flex space-x-2.5 items-center">
                                            <span class="text-2xl text-neutral-300">{{ $t("servers.cpu") }}</span>
                                            <span class="text-neutral-500 text-lg"> {{ cpu_price }}&#x20bd; за 1 </span>
                                        </div>
                                        <span class="text-neutral-300 mb-3 text-sm">{{ $t("servers.cpu.full") }}</span>
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
                                        <span>{{ cpu_max }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="w-72 text-neutral-400 text-sm md:block hidden"
                                >От кол-ва выделенных ядер зависит скорость запуска сервера, а так же
                                производительность. Мы рекомендуем выделять на сервер не менее двух ядер.</span
                            >
                        </div>

                        <!-- DISK -->
                        <div class="flex lg:flex-col flex-row space-y-2 space-x-8 lg:space-x-0">
                            <div class="space-y-4 md:w-72 w-screen90">
                                <div class="flex items-center w-full justify-between">
                                    <div>
                                        <div class="flex space-x-2.5 items-center">
                                            <span class="text-2xl text-neutral-300">{{ $t("servers.disk") }}</span>
                                            <span class="text-neutral-500 text-lg">
                                                {{ disk_price }}&#x20bd; за 1 GB
                                            </span>
                                        </div>
                                        <span class="text-neutral-300 mb-3 text-sm">{{ $t("servers.disk.full") }}</span>
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
                                        <span>{{ disk_max }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="w-72 text-neutral-400 text-sm md:block hidden"
                                >На дисковом пространстве хранятся данные сервера. Чем больше места выделено, тем больше
                                данных он сможет сохранить.</span
                            >
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="flex flex-col max-w-xs space-y-8">
                        <div class="section text-neutral-300">
                            <div class="section__item section__item--start">
                                <p class="text-5xl">{{ price_day.toFixed(2) }}&#x20bd;</p>
                                <p class="text-xl text-neutral-500">{{ $t("servers.price.day") }}</p>
                            </div>

                            <div class="section__item section__item--end">
                                <p class="text-5xl">{{ price_month.toFixed(2) }}&#x20bd;</p>
                                <p class="text-xl text-neutral-500">{{ $t("servers.price.month") }}</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-col max-w-xs space-y-8 border-2 p-4 border-wings-light rounded-md"
                            v-if="
                                free_test &&
                                (!$page.props.auth.user ||
                                    ($page.props.auth.user &&
                                        balance &&
                                        balance.personal.amount >= price_day * 2 &&
                                        balance.personal.total_spent === 0))
                            ">
                            <p
                                v-if="!$page.props.auth.user"
                                class="text-neutral-300 text-center">
                                Бонус для оплаты тестового сервера будет зачислен на ваш баланс после регистрации!
                            </p>
                            <p
                                v-else-if="$page.props.auth.user && balance && balance.personal.amount >= price_day * 2"
                                class="text-neutral-300 text-center">
                                Бонус для оплаты тестового сервера уже на вашем балансе
                            </p>
                            <!--<p v-else class="text-neutral-300 text-center">Этот сервер будет платным для вас</p>-->
                        </div>
                        <Link
                            :href="
                                route('dash.servers.create.pre-builder', {
                                    service: 'minecraft',
                                    type: type,
                                    version: version,
                                    subtype: subtype,
                                    ram: form.ram,
                                    cpu: form.cpu,
                                    disk: form.disk,
                                    name: 'Сервер Minecraft',
                                })
                            "
                            class="inline-flex px-4 py-2 pt-3 bg-neutral-300 h-14 justify-center align-middle border-2 rounded-3xl font-['OpenSans-Medium'] text-neutral-950 tracking-wide hover:bg-white hover:text-neutral-950 focus:outline-none focus:opacity-80 transition-all ease-in-out duration-150 text-lg">
                            {{ $t("servers.create") }}
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <Footer />
</template>
