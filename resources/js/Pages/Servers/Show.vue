<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { ref } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import GradientButton from "@/Components/GradientButton.vue";
import GrayButton from "@/Components/GrayButton.vue";
import Create from "@/Pages/Servers/Chunks/Create.vue";
import Minecraft from "@/Pages/Servers/Chunks/Services/Minecraft.vue";
import Summary from "@/Pages/Servers/Chunks/Summary.vue";
import { faCopy } from "@fortawesome/free-regular-svg-icons";
import { faArrowUpRightFromSquare } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import Network from "@/Pages/Servers/Chunks/Network.vue";
import Resources from "@/Pages/Servers/Chunks/Resources.vue";
import Startup from "@/Pages/Servers/Chunks/Startup.vue";
import Users from "@/Pages/Servers/Chunks/Users.vue";
import Payments from "@/Pages/Dash/Chunks/Payments.vue";

const props = defineProps({
    active_server_call: String,
    section: String,
});

const copied = ref(false);

const copyToClipboard = async (copy_value) => {
    await navigator.clipboard
        .writeText(copy_value)
        .then(() => {
            copied.value = true;
            setTimeout(function () {
                copied.value = false;
            }, 2000);
        })
        .catch((error) => {
            console.error("Error when performing a text copy operation: " + error);
        });
};

const loading = ref(true);
const servers = ref(null);
const wh_servers = ref(null);
const active_server = ref(null);
const get_servers = async () => {
    await axios
        .get(route("api.v1.servers", { active: props.active_server_call }))
        .then(function (response) {
            servers.value = response.data.servers;
            active_server.value = response.data.active;
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

const allocations = ref(null);
const get_allocations = async () => {
    await axios
        .get(route("api.v1.servers.network.allocations"))
        .then(function (response) {
            allocations.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                allocations.value = err;
            }
        });
};

get_servers();

setTimeout(function () {
    get_allocations();
}, 30);
</script>

<template>
    <AppLayout
        :title="$t('nav.servers')"
        :show-angle="true">
        <template #aside="{ showingAsideNav }">
            <g class="lg:block">
                <Link :href="route('dash.servers.create')">
                    <GradientButton class="px-10 w-full">
                        {{ $t("servers.create") }}
                    </GradientButton>
                </Link>
                <template
                    v-if="servers"
                    v-for="server in servers"
                    :key="server.id">
                    <Link :href="route('dash.servers.server', server.uuidShort)">
                        <GrayButton
                            class="mt-6 float-left w-full h-22 text-start"
                            :class="{
                                active: server.uuidShort === $page.props.active_server_call,
                                'border-2 border-yellow-500': server.status === 'suspended',
                            }">
                            {{ server.name }}<br />
                            IP/ID:
                            <template v-if="allocations"
                                >{{ allocations[server.id][server.allocation_id].ip_alias }}:{{
                                    allocations[server.id][server.allocation_id].port
                                }}
                            </template>
                            <template
                                v-else
                                class="animate-pulse">
                                Загрузка...
                            </template>
                            /
                            {{ server.uuidShort }}
                        </GrayButton>
                    </Link>
                </template>
                <template v-else>
                    <!-- TODO: подумать над отступом сверху -->
                    <p
                        v-if="$page.props.auth.user"
                        class="text-neutral-300 text-center font-medium animate-pulse mt-40">
                        Загрузка серверов...
                    </p>
                    <p
                        v-else
                        class="text-neutral-400 text-center font-medium mt-40">
                        Сервера доступны только зарегистрированным пользователям
                    </p>
                </template>
                <p class="mb-4 invisible text-xs">
                    Здравствуй, дорогой друг. Если ты читаешь это, вероятно, ты весьма смышлён, раз решил поискать
                    что-то в элементах. Так вот, Суперхаб говно.
                </p>
            </g>

            <div
                :class="{ block: showingAsideNav, hidden: !showingAsideNav }"
                v-if="allocations && allocations.length === 0"
                class="px-10 mt-40 text-center text-white font-['OpenSans-Medium']">
                У вас нет серверов
            </div>
            <div
                v-else-if="servers && servers.length === 0"
                class="px-10 mt-40 text-center text-white font-['OpenSans-Medium']">
                В этой группе нет серверов
            </div>

            <template v-else />
        </template>

        <div class="py-12">
            <template v-if="active_server && active_server.includes('create')">
                <Create />
            </template>
            <template v-else-if="active_server && active_server.includes('minecraft')">
                <Minecraft :data="active_server" />
            </template>
        </div>
        <template
            v-if="servers"
            v-for="server in servers">
            <div
                v-if="server.uuidShort === active_server"
                class="lg:w-4/5 mx-auto sm:px-6 lg:px-9 float-right mt-6">
                <!-- Page Navigation -->
                <div
                    class="bg-neutral-900 border border-neutral-700 rounded-2xl xl:w-max w-full p-2 flex items-center flex-wrap lg:gap-x-4 gap-x-2 gap-y-4 min-[900px]:pt-2 pt-4">
                    <Link :href="route('dash.servers.server', server.uuidShort)">
                        <span
                            class="h-10 w-max p-2 px-4 rounded-lg text-neutral-300"
                            :class="{
                                'border-wings-light border bg-wings/20 text-wings-light':
                                    route().current('dash.servers.server') || route().current('dash.servers'),
                            }">
                            {{ $t("servers.main") }}
                        </span>
                    </Link>
                    <Link :href="route('dash.servers.server.network', server.uuidShort)">
                        <span
                            class="h-10 w-max p-2 px-4 rounded-lg text-neutral-300"
                            :class="{
                                'border-wings-light border bg-wings/20 text-wings-light':
                                    route().current('dash.servers.server.network'),
                            }">
                            {{ $t("servers.network") }}
                        </span>
                    </Link>
                    <Link
                        :href="route('dash.servers.server.resources', server.uuidShort)"
                        :class="{ 'pointer-events-none': server.status === 'suspended' }">
                        <span
                            class="h-10 w-max p-2 px-4 rounded-lg text-neutral-300"
                            :class="{
                                'border-wings-light border bg-wings/20 text-wings-light': route().current(
                                    'dash.servers.server.resources',
                                ),
                                'text-neutral-500': server.status === 'suspended',
                            }">
                            {{ $t("servers.resources") }}
                        </span>
                    </Link>
                    <Link
                        class="pointer-events-none"
                        :href="route('dash.servers.server.users', server.uuidShort)">
                        <span
                            class="h-10 w-max p-2 px-4 rounded-lg text-neutral-500"
                            :class="{
                                'border-wings-light border bg-wings/20 text-wings-light':
                                    route().current('dash.servers.server.users'),
                            }">
                            {{ $t("servers.users") }}
                        </span>
                    </Link>
                    <Link
                        class="pointer-events-none"
                        :href="route('dash.servers.server.startup', server.uuidShort)">
                        <span
                            class="h-10 w-max p-2 px-4 rounded-lg text-neutral-500"
                            :class="{
                                'border-wings-light border bg-wings/20 text-wings-light':
                                    route().current('dash.servers.server.startup'),
                            }">
                            {{ $t("servers.startup") }}
                        </span>
                    </Link>
                    <Link
                        class="h-10 w-max p-2 px-4 rounded-lg space-x-1.5"
                        :href="route('panel', server.uuidShort)"
                        target="_blank">
                        <button>
                            <span class="text-neutral-300">{{ $t("servers.panel") }}</span>
                        </button>
                        <font-awesome-icon
                            :icon="faArrowUpRightFromSquare"
                            style="color: #d4d4d4"
                            class="h-4 w-4" />
                    </Link>
                    <div class="px-4 text-neutral-300 text-lg p-2 space-x-2">
                        <span
                            >{{ $t("servers.ip") }}:
                            <template v-if="allocations">
                                {{ allocations[server.id][server.allocation_id].ip_alias }}:{{
                                    allocations[server.id][server.allocation_id].port
                                }}</template
                            ><template
                                v-else
                                class="animate-pulse"
                                >Загрузка...</template
                            ></span
                        >
                        <button
                            class="text-neutral-500 underline transition-all ease-in-out duration-150 hover:text-neutral-400 relative"
                            v-if="allocations"
                            @click="
                                copyToClipboard(
                                    allocations[server.id][server.allocation_id].ip_alias +
                                        ':' +
                                        allocations[server.id][server.allocation_id].port,
                                )
                            ">
                            <code>
                                <font-awesome-icon :icon="faCopy" />
                            </code>
                        </button>
                        <span
                            v-if="copied"
                            class="text-neutral-300 text-xs bg-neutral-800/80 p-2 ml-3 rounded-lg triangle2 absolute">
                            {{ $t("servers.ip.copied") }}
                        </span>
                    </div>
                </div>
                <template v-if="wh_servers && server">
                    <Summary
                        v-if="!section && server && wh_servers"
                        :server="server"
                        :wh_server="wh_servers[server.id]" />
                    <Network
                        v-else-if="section === 'network' && server"
                        :server="server" />
                    <Resources
                        v-else-if="section === 'resources' && server && wh_servers"
                        :server="server"
                        :wh_server="wh_servers[server.id]" />
                    <Users
                        v-else-if="section === 'users' && server"
                        :server="server" />
                    <Startup
                        v-else-if="section === 'startup' && server"
                        :server="server" />
                    <Summary
                        v-else-if="server"
                        :server="server" />
                </template>

                <!-- Spinner -->
                <template v-else>
                    <div
                        class="loadingio-spinner-rolling-bzo8ytft79 absolute top-1/3 left-[25%] sm:left-[40%] lg:left-1/2 scale-50 lg:scale-75">
                        <div class="ldio-1kfs0eaw0lw">
                            <div></div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </AppLayout>
</template>

<style>
.triangle2::after {
    content: "";
    position: absolute;
    top: 50%;
    right: 100%;
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent #262626 transparent transparent;
}

@keyframes ldio-1kfs0eaw0lw {
    0% {
        transform: translate(-50%, -50%) rotate(0deg);
    }
    100% {
        transform: translate(-50%, -50%) rotate(360deg);
    }
}

.ldio-1kfs0eaw0lw div {
    position: absolute;
    width: 120px;
    height: 120px;
    border: 20px solid #7c00db;
    border-top-color: transparent;
    border-radius: 50%;
}

.ldio-1kfs0eaw0lw div {
    animation: ldio-1kfs0eaw0lw 1s linear infinite;
    top: 100px;
    left: 100px;
}

.loadingio-spinner-rolling-bzo8ytft79 {
    width: 200px;
    height: 200px;
    display: inline-block;
    overflow: hidden;
    background: none;
}

.ldio-1kfs0eaw0lw {
    width: 100%;
    height: 100%;
    position: relative;
    transform: translateZ(0) scale(1);
    backface-visibility: hidden;
    transform-origin: 0 0; /* see note above */
}

.ldio-1kfs0eaw0lw div {
    box-sizing: content-box;
}
</style>
