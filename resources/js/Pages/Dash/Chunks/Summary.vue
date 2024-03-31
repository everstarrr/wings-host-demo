<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faChevronRight, faFileLines } from "@fortawesome/free-solid-svg-icons";
import { Link } from "@inertiajs/vue3";
import moment from "moment";
import { ref } from "vue";
import CodeBlock from "@/Components/CodeBlock.vue";
import DialogModal from "@/Components/DialogModal.vue";
import { faSnowflake } from "@fortawesome/free-regular-svg-icons";
import DefaultButton from "@/Components/DefaultButton.vue";

const payments_stats = ref(null);
const balance = ref(null);
const get_payments_stats = async () => {
    await axios
        .get(route("api.v1.payments.stats"))
        .then(function (response) {
            payments_stats.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                payments_stats.value = err;
            }
        });

    await axios
        .get(route("api.v1.payments.balance"))
        .then(function (response) {
            balance.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                balance.value = err;
            }

            /*axios.defaults.withCredentials = false;

// TODO: make it global and use in every api calls
const api_v1 = axios.create({
    baseURL: "http://localhost:8000/api/v1",
    withCredentials: false,
    headers: {
        //"Accept": 'application/json',
        "Content-type": "application/json",
    },
});*/
        });
};

const servers = ref(null);
const wh_servers = ref(null);
const get_servers = async () => {
    await axios
        .get(route("api.v1.servers"))
        .then(function (response) {
            servers.value = response.data.servers;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                servers.value = err;
            }
        });

    await axios
        .get(route("api.v1.servers.wh"))
        .then(function (response) {
            wh_servers.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                wh_servers.value = err;
            }
        });
};

setTimeout(function () {
    get_payments_stats();
    get_servers();
}, 1000);

const showing_payments_details = ref(false);
</script>

<template>
    <DialogModal
        :show="showing_payments_details && payments_stats"
        @close="showing_payments_details = false"
        max-width="6xl">
        <template #title>Описание статистики</template>
        <template #content>
            <div class="mt-4">
                <span class="sm:text-base text-xs">payment_stats:</span>
                <CodeBlock
                    class="sm:ml-10 sm:text-base text-xs"
                    :code="payments_stats.details"
                    format="json" />
            </div>
        </template>
        <template #footer>
            <button
                @click="showing_payments_details = false"
                type="button"
                class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block ml-3">
                {{ $t("base.close") }}
            </button>
        </template>
    </DialogModal>

    <h2 class="font-medium text-2xl text-white leading-tight lg:text-left text-center mt-12 lg:mt-8 mx-8">
        {{ $t("account.account") }}
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-3 m-8 gap-6">
        <div class="bg-neutral-800 border-neutral-700 border rounded-md p-4 sm:p-6">
            <p class="text-neutral-300 text-xl border-b border-neutral-700 pb-1.5">
                {{ $page.props.auth.user.u_name }}
            </p>

            <div class="flex flex-col h-full justify-between pb-10">
                <div class="mt-5 flex flex-col space-y-2">
                    <div class="space-x-6 flex w-full justify-between">
                        <span class="text-neutral-400">Регистрация:</span>
                        <span class="text-neutral-300">{{
                            moment($page.props.auth.user.created_at).format("DD.MM.YYYY HH:mm:ss")
                        }}</span>
                    </div>
                    <div class="space-x-6 flex w-full justify-between">
                        <span class="text-neutral-400">Интеграция VK:</span>
                        <span class="text-neutral-300">Нет</span>
                    </div>
                    <div class="space-x-6 flex w-full justify-between">
                        <span class="text-neutral-400">Интеграция Discord:</span>
                        <span class="text-neutral-300">Нет</span>
                    </div>
                    <div class="space-x-6 flex w-full justify-between">
                        <span class="text-neutral-400">Интеграция Telegram:</span>
                        <span class="text-neutral-300">Нет</span>
                    </div>
                </div>
                <DefaultButton
                    class="w-full focus:bg-neutral-500 hover:bg-neutral-500 text-neutral-900 bg-neutral-500 cursor-default xl:mt-10 mt-4">
                    Интеграции
                </DefaultButton>
            </div>
        </div>

        <div
            class="bg-neutral-800 border-neutral-700 border rounded-md p-4 sm:p-6"
            :class="{ 'animate-pulse': !payments_stats }">
            <p class="text-neutral-300 text-xl border-b border-neutral-700 pb-1.5">Финансы</p>
            <div class="flex flex-col h-full justify-between pb-10">
                <div class="mt-5 flex flex-col space-y-2">
                    <div class="space-x-6 flex w-full justify-between">
                        <span class="text-neutral-400">{{ $t("payments.balance") }}:</span>
                        <span
                            class="text-neutral-300"
                            v-if="balance">
                            {{ balance.personal.amount }} {{ balance.personal.currency }}</span
                        >
                        <span
                            class="text-neutral-300 animate-pulse"
                            v-else>
                            Загрузка...
                        </span>
                    </div>
                    <div
                        class="space-x-6 flex w-full justify-between"
                        v-if="payments_stats">
                        <span class="text-neutral-400">Расход в день:</span>
                        <span class="text-neutral-300"
                            >&asymp;{{ parseFloat(payments_stats.total_day).toFixed(2) }}&#x20bd;</span
                        >
                    </div>
                    <div
                        class="space-x-6 flex w-full justify-between"
                        v-if="payments_stats">
                        <span class="text-neutral-400">Остаток дней:</span>
                        <span class="text-neutral-300">&asymp;{{ payments_stats.days_remaining }} дней</span>
                    </div>
                    <div
                        class="space-x-6 flex w-full justify-between"
                        v-if="payments_stats">
                        <span class="text-neutral-400">Расход в месяц:</span>
                        <span class="text-neutral-300"
                            >&asymp;{{ parseFloat(payments_stats.total_month).toFixed(2) }}&#x20bd;</span
                        >
                    </div>
                    <div
                        class="space-x-6 flex w-full justify-between"
                        v-if="payments_stats">
                        <span class="text-neutral-400">Расход за всё время:</span>
                        <span class="text-neutral-300"
                            >{{ parseFloat(payments_stats.total_spent).toFixed(2) }}&#x20bd;</span
                        >
                    </div>
                    <div
                        class="space-x-6 flex w-full justify-between"
                        v-if="payments_stats">
                        <span class="text-neutral-400">Подробности:</span>
                        <span class="text-neutral-300 cursor-pointer"
                            ><font-awesome-icon
                                :icon="faFileLines"
                                @click.prevent="showing_payments_details = true && get_payments_stats()"
                        /></span>
                    </div>
                </div>
                <Link :href="route('dash.payments')">
                    <DefaultButton class="w-full xl:mt-10 mt-4">
                        {{ $t("payments.top_up") }}
                    </DefaultButton>
                </Link>
            </div>
        </div>

        <div
            class="bg-neutral-800 border-neutral-700 border rounded-md p-4 sm:p-6"
            :class="{ 'animate-pulse': !servers }">
            <p class="text-neutral-300 text-xl border-b border-neutral-700 pb-1.5">{{ $t("nav.servers") }}</p>
            <div class="flex flex-col h-full justify-between pb-10">
                <div
                    class="h-40 overflow-y-scroll mt-5"
                    :class="{ 'outer text-center': !servers || servers.length === 0 }">
                    <div
                        v-if="servers && servers.length !== 0"
                        class="flex flex-col space-y-2">
                        <Link
                            v-for="server in servers"
                            :href="route('dash.servers.server', server.uuidShort)"
                            class="h-10 bg-neutral-300 rounded-md hover:bg-neutral-400 transition-all duration-150 flex w-full items-center px-4 justify-between">
                            <span
                                ><font-awesome-icon
                                    v-if="server.status === 'suspended'"
                                    :icon="faSnowflake"
                                    class="h-4 w-4 pr-3" />{{ server.name }}</span
                            >
                            <font-awesome-icon
                                :icon="faChevronRight"
                                class="h-4 w-4" />
                        </Link>
                    </div>
                    <div
                        class="text-lg text-neutral-400 inner"
                        v-else>
                        <span v-if="servers && servers.length === 0">У вас пока нет серверов</span>
                        <span v-else>Загрузка...</span>
                    </div>
                </div>
                <Link :href="route('dash.servers.create')">
                    <DefaultButton class="w-full">
                        {{ $t("servers.create") }}
                    </DefaultButton>
                </Link>
            </div>
        </div>
    </div>
</template>

<style>
/* width */
::-webkit-scrollbar {
    width: 2px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #262626;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #d4d4d4;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.outer:before {
    display: inline-block;
    height: 100%;
    vertical-align: middle;
    content: "";
}

.inner {
    display: inline-block;
    vertical-align: middle;
}
</style>
