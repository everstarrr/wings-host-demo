<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { router, usePage } from "@inertiajs/vue3";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
    faArrowDown,
    faArrowRight,
    faCity,
    faDollarSign,
    faGear,
    faHardDrive,
    faLocationDot,
    faMemory,
    faMicrochip,
    faServer,
    faTriangleExclamation,
} from "@fortawesome/free-solid-svg-icons";
import DangerButton from "@/Components/DangerButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownCurrencyItem from "@/Components/DropdownCurrencyItem.vue";
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import swe_png from "@pngs/countries/swe.png";
import SectionBorder from "@/Components/SectionBorder.vue";
import DialogModal from "@/Components/DialogModal.vue";
import deu_png from "@pngs/countries/deu.png";
import fin_png from "@pngs/countries/fin.png";
import rus_png from "@pngs/countries/rus.png";
import ErrorPopup from "@/Components/ErrorPopup.vue";
import SummaryItem from "@/Pages/Servers/Chunks/Components/SummaryItem.vue";
import DefaultButton from "@/Components/DefaultButton.vue";

const props = defineProps({
    server: Object,
    wh_server: Object,
});

const page = usePage();

const nodes = ref(null);
const get_nodes = async () => {
    await axios
        .get(route("api.v1.nodes"))
        .then(function (response) {
            nodes.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                errors.value.set("nodes", "retrieve");
            }
        });
};

setTimeout(function () {
    get_nodes();
}, 50);

const selectedGroupTo = ref();

const copied = ref(false);

const confirmingSuspend = ref(false);
const confirmingUnsuspend = ref(false);
const is_suspending = ref(false);
const confirmingDelete = ref(false);

const closeSuspend = () => {
    confirmingSuspend.value = false;
};
const closeUnsuspend = () => {
    confirmingUnsuspend.value = false;
};
const closeDelete = () => {
    confirmingDelete.value = false;
    delete_password.value = "";
};

const errors = ref(new Map());

const manual_suspend = async (server_id, suspend) => {
    confirmingSuspend.value = false;
    confirmingUnsuspend.value = false;
    is_suspending.value = true;
    await axios
        .post(route("api.v1.servers.suspend.manual"), {
            server_ext_id: server_id,
            suspend: suspend,
        })
        .then(function (response) {
            if (response.data) {
                if (response.data.status !== "failed") {
                    if (response.data.status === "success" && suspend === false) {
                        props.server.status = null;
                        router.get(route("dash.servers.server", props.server.uuidShort));
                    } else if (response.data.status === "success" && suspend === true) {
                        props.server.status = "suspended";
                        router.get(route("dash.servers.server", props.server.uuidShort));
                    } else {
                        errors.value.set("manual_suspend", "FRONTEND LOGIC FAILED > RELOAD PAGE");
                    }
                } else {
                    errors.value.set("manual_suspend", response.data.error);
                }
            } else {
                errors.value.set("manual_suspend", response.data);
            }
            is_suspending.value = false;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                errors.value.set("manual_suspend", err);
            }
            is_suspending.value = false;
        });
};

const switch_team = async (server_id, team_id) => {
    //confirmingSuspend.value = false;
    //confirmingUnsuspend.value = false;
    await axios
        .post(route("api.v1.servers.team.switch"), {
            server_ext_id: server_id,
            team_id: team_id,
        })
        .then(function (response) {
            if (response.data === "SUCCESS") {
                /*
                if (props.server.status === "suspended" && suspend === false) {
                    props.server.status = null;
                } else if (props.server.status === null && suspend === true) {
                    props.server.status = "suspended";
                } else {
                    props.server.status = "[E] manual_suspend_render_error: FRONTEND LOGIC FAILED > RELOAD PAGE";
                }
                 */
                router.put(
                    route("current-team-to-servers.update"),
                    {
                        team_id: team_id,
                    },
                    {
                        preserveState: false,
                    },
                );
            } else {
                errors.value.set("switch_team", response.data);
            }
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                errors.value.set("switch_team", err);
            }
        });
};

const delete_password = ref("");
const delete_server = async (password) => {
    confirmingDelete.value = false;
    await axios
        .delete(route("api.v1.servers.delete"), {
            data: {
                ext_id: props.wh_server.ext_id,
                password: password,
            },
        })
        .then(function (response) {
            if (response.data) {
                if (!response.data.auth.pass) {
                    errors.value.set("servers.delete", response.data.auth);
                } else if (!response.data.result) {
                    errors.value.set("server.delete", "failed");
                } else if (response.data.result) {
                    router.get(route("dash.servers"));
                }
            }
        })
        .catch((err) => {
            if (err.response.data.errors.password) {
                errors.value.set("server.delete", err.response.data.message);
            } else if (!axios.isCancel(err)) {
                errors.value.set("server.delete", err);
            }
        });
    delete_password.value = "";
};
</script>
<template>
    <ErrorPopup :errors="errors" />
    <div class="float-right w-full mb-16">
        <!-- Suspended -->
        <div
            v-if="server.status === 'suspended'"
            class="bg-neutral-900 rounded-2xl border-2 border-yellow-500 overflow-hidden mt-8 px-8 py-4 flex items-center justify-between"
            :class="{ 'animate-pulse': is_suspending }">
            <div class="space-x-4 items-center flex">
                <font-awesome-icon
                    :icon="faTriangleExclamation"
                    style="color: #eab308"
                    class="h-8 w-8" />
                <span class="text-neutral-300">{{
                    $t("servers.suspended.full", { days_delete: wh_server.untransacted.until_delete.toString() })
                }}</span>
            </div>
            <div class="sm:space-x-4 flex sm:flex-row flex-col sm:space-y-0 space-y-3">
                <button
                    @click="confirmingDelete = true"
                    class="border-red-500 border bg-red-500/20 h-10 w-max p-2 px-4 rounded-md text-neutral-300 hover:bg-red-500/30 transition-all ease-in-out duration-150">
                    <span>{{ $t("base.delete") }}</span>
                    <DialogModal
                        :show="confirmingDelete"
                        @close="closeDelete">
                        <template #title> Удаление сервера</template>
                        <template #content>
                            Введите пароль, чтобы подтвердить удаление сервера.
                            <div class="mt-4">
                                <TextInput
                                    ref="delete_password"
                                    v-model="delete_password.value"
                                    type="password"
                                    class="mt-1 block w-3/4 placeholder-neutral-500"
                                    placeholder="Пароль"
                                    autocomplete="current-password" />
                            </div>
                        </template>
                        <template #footer>
                            <button
                                @click="closeDelete"
                                type="button"
                                class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-300 border border-neutral-500 hover:border-neutral-300 transition-all duration-150 ease-in-out block focus:border-neutral-300">
                                Отмена
                            </button>
                            <button
                                @click="delete_server(delete_password.value)"
                                type="button"
                                class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block ml-3">
                                Удалить сервер
                            </button>
                        </template>
                    </DialogModal>
                </button>

                <button
                    @click="confirmingUnsuspend = true"
                    class="border-green-500 border bg-green-500/20 h-10 w-max p-2 px-4 rounded-md text-neutral-300 hover:bg-green-500/30 transition-all ease-in-out duration-150">
                    <span>{{ $t("servers.unsuspend") }}</span>
                    <DialogModal
                        :show="confirmingUnsuspend"
                        @close="closeUnsuspend">
                        <template #title> Разморозить сервер</template>
                        <template #content>
                            При разморозке с вашего баланса моментально будет списана плата за день, а сервер возобновит
                            работу.
                        </template>
                        <template #footer>
                            <button
                                @click="closeUnsuspend"
                                type="button"
                                class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-300 border border-neutral-500 hover:border-neutral-300 transition-all duration-150 ease-in-out block focus:border-neutral-300">
                                Отмена
                            </button>
                            <button
                                type="button"
                                class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block ml-3"
                                @click="manual_suspend(server.id, false)">
                                Разморозить сервер
                            </button>
                        </template>
                    </DialogModal>
                </button>
            </div>
        </div>
        <div class="flex flex-wrap mt-10 gap-y-4 gap-x-4">
            <SummaryItem :user-icon="faMemory">
                <template #title>
                    {{ $t("servers.ram.full") }}
                </template>
                <template #content>
                    <span v-if="server.memory">{{ server.memory / 1024 }} GB </span>
                    <span v-else>Загрузка...</span>
                </template>
            </SummaryItem>
            <SummaryItem :user-icon="faMicrochip">
                <template #title>
                    {{ $t("servers.cpu.full") }}
                </template>
                <template #content> {{ server.cpu / 100 }} {{ $t("servers.cpu") }} {{ server.status }} </template>
            </SummaryItem>
            <SummaryItem :user-icon="faHardDrive">
                <template #title>
                    {{ $t("servers.disk.full") }}
                </template>
                <template #content> {{ server.disk / 1024 }} GB </template>
            </SummaryItem>
            <SummaryItem :user-icon="faDollarSign">
                <template #title>
                    {{ $t("servers.price.day.full") }}
                </template>
                <template #content> {{ wh_server.price.day }}&#x20bd; </template>
            </SummaryItem>
            <SummaryItem :user-icon="faDollarSign">
                <template #title>
                    {{ $t("servers.price.month.full") }}
                </template>
                <template #content> {{ wh_server.price.month }}&#x20bd; </template>
            </SummaryItem>

            <div
                class="bg-neutral-900 rounded-2xl border p-4 px-6 border-neutral-700 w-full sm:w-max"
                :class="{ 'animate-pulse': !nodes }">
                <div class="flex justify-between">
                    <div class="text-neutral-300">
                        <div class="flex items-center space-x-8">
                            <font-awesome-icon
                                :icon="faLocationDot"
                                class="w-10 h-10" />
                            <div class="flex-col">
                                <p class="text-sm text-neutral-500">{{ $t("locations.country") }}</p>
                                <span
                                    class="inline-flex items-center space-x-2 mt-1"
                                    v-if="nodes">
                                    <v-lazy-image
                                        v-if="nodes[server.node_id].location.split('.')[0].toLowerCase() === 'deu'"
                                        :src="deu_png"
                                        class="w-9 h-6 rounded-sm"
                                        alt="Germany" />

                                    <v-lazy-image
                                        v-if="nodes[server.node_id].location.split('.')[0].toLowerCase() === 'fin'"
                                        :src="fin_png"
                                        class="w-9 h-6 rounded-sm"
                                        alt="Finland" />

                                    <v-lazy-image
                                        v-if="nodes[server.node_id].location.split('.')[0].toLowerCase() === 'rus'"
                                        :src="rus_png"
                                        class="w-9 h-6 rounded-sm"
                                        alt="Russia" />

                                    <v-lazy-image
                                        v-if="nodes[server.node_id].location.split('.')[0].toLowerCase() === 'swe'"
                                        :src="swe_png"
                                        class="w-9 h-6 rounded-sm"
                                        alt="Sweden" />

                                    <span class="text-3xl">{{
                                        $t("locations." + nodes[server.node_id].location.split(".")[0])
                                    }}</span>
                                </span>
                                <span
                                    class="text-3xl"
                                    v-else
                                    >Загрузка...</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <SummaryItem
                :user-icon="faCity"
                :class="{ 'animate-pulse': !nodes }">
                <template #title>
                    {{ $t("locations.city") }}
                </template>
                <template #content>
                    <span v-if="nodes">
                        {{
                            $t(
                                "locations." +
                                    nodes[server.node_id].location.split(".")[0] +
                                    "." +
                                    nodes[server.node_id].location.split(".")[1],
                            )
                        }}
                    </span>
                    <span v-else>Загрузка...</span>
                </template>
            </SummaryItem>
            <SummaryItem
                :user-icon="faServer"
                :class="{ 'animate-pulse': !nodes }">
                <template #title>
                    {{ $t("servers.node") }}
                </template>
                <template #content>
                    <span v-if="nodes">
                        {{ nodes[server.node_id].name }}
                    </span>
                    <span v-else>Загрузка...</span>
                </template>
            </SummaryItem>
            <SummaryItem :user-icon="faGear">
                <template #title>
                    {{ $t("servers.service") }}
                </template>
                <template #content> Minecraft </template>
            </SummaryItem>
        </div>
        <div class="bg-neutral-900 rounded-2xl border border-neutral-700 overflow-hidden mt-10">
            <div class="p-8 bg-neutral-900">
                <!-- Suspend -->
                <div
                    class="sm:flex justify-between"
                    v-if="server.status !== 'suspended'">
                    <div class="space-y-1.5 sm:w-1/3">
                        <p class="text-xl text-white">{{ $t("servers.suspend") }}</p>
                        <p class="text-neutral-400 text-sm">{{ $t("servers.suspend.notice") }}</p>
                    </div>
                    <div
                        class="bg-neutral-800 p-6 rounded-lg border-neutral-700 border space-y-6 sm:w-2/3 sm:mt-0 mt-6">
                        <p class="text-neutral-400 text-sm">
                            {{ $t("servers.suspend.notice.full") }}
                        </p>
                        <div>
                            <button
                                @click="confirmingSuspend = true"
                                class="border-yellow-500 border bg-yellow-500/20 h-12 w-56 p-2 px-4 rounded-md hover:bg-yellow-500/30 duration-150 transition-all ease-in-out text-neutral-300">
                                <span>{{ $t("servers.suspend") }}</span>
                            </button>
                            <DialogModal
                                :show="confirmingSuspend"
                                @close="closeSuspend">
                                <template #title> Заморозить сервер</template>
                                <template #content>
                                    После заморозки сервера вы можете возобновить его работу в течение 60 дней. По
                                    истечении этого срока сервер будет безвозвратно удалён.
                                </template>
                                <template #footer>
                                    <button
                                        @click="closeSuspend"
                                        type="button"
                                        class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-300 border border-neutral-500 hover:border-neutral-300 transition-all duration-150 ease-in-out block focus:border-neutral-300">
                                        Отмена
                                    </button>
                                    <button
                                        type="button"
                                        class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block ml-3"
                                        @click="manual_suspend(server.id, true)">
                                        Заморозить сервер
                                    </button>
                                </template>
                            </DialogModal>
                        </div>
                    </div>
                </div>
                <SectionBorder v-if="server.status !== 'suspended'" />
                <!-- Group Settings -->
                <div class="sm:flex justify-between">
                    <div class="space-y-1.5 sm:w-1/3">
                        <p class="text-xl text-white">Настройки группы</p>
                        <p class="text-neutral-400 text-sm">Здесь вы можете настроить группу сервера.</p>
                    </div>
                    <div
                        class="bg-neutral-800 p-6 rounded-lg border-neutral-700 border space-y-6 sm:w-2/3 sm:mt-0 mt-6">
                        <p class="text-neutral-400 text-sm">
                            Удалить все данные сервера с хостинга. Вам будет отправлено письмо с ссылкой для
                            подтверждения удаления сервера. После подтверждения все данные сервера будут удалены без
                            возможности восстановления.
                        </p>
                        <div class="block xl:flex space-x-0 xl:space-x-8">
                            <div class="xl:block">
                                <div>
                                    <InputLabel class="text-neutral-300 ml-0.5 text-sm mb-1"
                                        >Текущая группа
                                    </InputLabel>
                                    <TextInput
                                        :is_disabled="true"
                                        :placeholder="$page.props.auth.user.current_team.name"
                                        class="h-12 w-56 placeholder-neutral-500" />
                                </div>
                            </div>
                            <font-awesome-icon
                                :icon="faArrowRight"
                                style="color: #a3a3a3"
                                class="mt-10 hidden xl:block" />
                            <div class="flex w-56 mt-6 xl:hidden justify-center sm:justify-start">
                                <font-awesome-icon
                                    :icon="faArrowDown"
                                    style="color: #a3a3a3"
                                    class="h-5 w-5 sm:ml-24" />
                            </div>
                            <div class="xl:block">
                                <div>
                                    <InputLabel class="text-neutral-300 ml-0.5 text-sm mb-1">Куда</InputLabel>
                                    <Dropdown
                                        width="full"
                                        align="left">
                                        <template #trigger="{ open }">
                                            <button
                                                type="button"
                                                class="inline-flex items-center pr-4 w-56 py-auto h-12 leading-4 font-['OpenSans-Medium'] rounded-md border-neutral-700 border bg-neutral-900 transition ease-in-out duration-150 justify-between">
                                                <span class="pl-4">
                                                    <template v-for="team in $page.props.auth.user.all_teams">
                                                        <span
                                                            class="text-neutral-300 -ml-1"
                                                            v-if="team.id === selectedGroupTo"
                                                            >{{ team.name }}</span
                                                        >
                                                    </template>
                                                </span>
                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    :class="{ 'rotate-180': open }"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    color="#d4d4d4">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </template>
                                        <template #content>
                                            <template v-for="team in $page.props.auth.user.all_teams">
                                                <DropdownCurrencyItem
                                                    v-if="team.id !== $page.props.auth.user.current_team.id"
                                                    class=""
                                                    @click="selectedGroupTo = team.id"
                                                    :active="selectedGroupTo === team.id">
                                                    {{ team.name }}
                                                </DropdownCurrencyItem>
                                            </template>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                        </div>
                        <div>
                            <DefaultButton
                                @click="switch_team(server.id, selectedGroupTo)"
                                class="w-56">
                                Переместить
                            </DefaultButton>
                        </div>
                    </div>
                </div>
                <SectionBorder />
                <!-- Delete -->
                <div class="sm:flex justify-between">
                    <div class="space-y-1.5 sm:w-1/3">
                        <p class="text-xl text-white">Удаление сервера</p>
                        <p class="text-neutral-400 text-sm">Здесь вы можете удалить сервер.</p>
                    </div>
                    <div
                        class="bg-neutral-800 p-6 rounded-lg border-neutral-700 border space-y-6 sm:w-2/3 sm:mt-0 mt-6">
                        <p class="text-neutral-400 text-sm">
                            Удалить все данные сервера с хостинга. Вы должны будете ввести пароль для подтверждения
                            удаления сервера. После подтверждения, все данные сервера будут удалены без возможности
                            восстановления.
                        </p>
                        <div>
                            <DangerButton
                                class="h-12 w-56"
                                @click="confirmingDelete = true"
                                >Удалить сервер
                            </DangerButton>
                            <DialogModal
                                :show="confirmingDelete"
                                @close="closeDelete">
                                <template #title> Удаление сервера</template>
                                <template #content>
                                    Нужен Ваш пароль, чтобы подтвердить необратимое удаление сервера. Вы удаляете сервер
                                    {{ server.uuidShort }} ({{ server.name }}).
                                    <div class="mt-4">
                                        <TextInput
                                            ref="delete_password"
                                            v-model="delete_password.value"
                                            type="password"
                                            class="mt-1 block w-3/4 placeholder-neutral-500"
                                            placeholder="Пароль"
                                            autocomplete="current-password" />
                                    </div>
                                </template>
                                <template #footer>
                                    <button
                                        @click="closeDelete"
                                        type="button"
                                        class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-300 border border-neutral-500 hover:border-neutral-300 transition-all duration-150 ease-in-out block focus:border-neutral-300">
                                        Отмена
                                    </button>
                                    <button
                                        @click="delete_server(delete_password.value)"
                                        type="button"
                                        class="w-max px-6 py-auto h-10 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block ml-3">
                                        Удалить сервер
                                    </button>
                                </template>
                            </DialogModal>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
