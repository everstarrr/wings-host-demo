<script setup>
import { ref } from "vue";
import { faCopy } from "@fortawesome/free-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const copied = ref(false);

const props = defineProps({
    allocations: Object,
    server: Object,
});
const copyIP = async (copy_value, allocation_id) => {
    await navigator.clipboard
        .writeText(copy_value)
        .then(() => {
            copied.value = allocation_id;
            setTimeout(function () {
                copied.value = false;
            }, 2000);
        })
        .catch((error) => {
            console.error("Error when performing a text copy operation: " + error);
        });
};

const set_result = ref(props.server.allocation_id);
const set_primary_allocation = async (server_id, allocation_id) => {
    await axios
        .post(route("api.v1.servers.network.primary"), {
            server_id: server_id,
            allocation_id: allocation_id,
        })
        .then(function (response) {
            if (response.data && !response.data.error) {
                set_result.value = response.data.allocation;
                props.server.allocation_id = response.data.allocation;
            } else {
                set_result.value = false;
            }
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                set_result.value = "error";
            }
        });
};
</script>

<template>
    <div class="block space-y-4">
        <div
            v-if="allocations"
            v-for="allocation in allocations[server.id]"
            class="bg-neutral-800 p-4 text-neutral-300 rounded-md justify-between min-[1460px]:flex min-[1460px]:space-y-0 space-y-1.5">
            <div class="min-[1460px]:flex-col flex min-[1460px]:w-max w-full justify-between">
                <span class="text-sm text-neutral-500">{{ $t("servers.allocations.address") }}</span>
                <span class="space-x-1.5">
                    <span>{{ allocation.ip_alias }}</span>
                    <button
                        class="text-neutral-500 transition-all ease-in-out duration-150 relative hover:text-neutral-400"
                        @click="copyIP(allocation.ip_alias + ':' + allocation.port, allocation.id)">
                        <code><font-awesome-icon :icon="faCopy" /></code>
                    </button>
                    <span
                        v-if="copied === allocation.id"
                        class="text-neutral-300 text-xs bg-neutral-900 p-2 ml-3 rounded-lg triangle1 absolute">
                        {{ $t("servers.ip.copied") }}
                    </span>
                </span>
            </div>
            <div class="min-[1460px]:flex-col flex min-[1460px]:w-max w-full justify-between min-[1460px]:px-5">
                <span class="text-sm text-neutral-500">{{ $t("servers.allocations.port") }}</span>
                <span>{{ allocation.port }}</span>
            </div>
            <button
                @click="
                    set_primary_allocation(server.id, allocation.id);
                    server.allocation_id = allocation.id;
                "
                type="button"
                :class="{ 'hover:border-white': allocation.id !== server.allocation_id }"
                :disabled="allocation.id === server.allocation_id"
                class="w-48 px-6 py-auto h-12 leading-4 rounded-lg text-neutral-300 bg-neutral-900 border-neutral-700 border transition-all duration-150 ease-in-out">
                <span
                    v-if="allocation.id === server.allocation_id"
                    class="text-neutral-500"
                    >{{ $t("servers.allocations.primary") }}</span
                >
                <span v-else>{{ $t("servers.allocations.primary.make") }}</span>
            </button>
        </div>
        <div
            v-else
            class="bg-neutral-800 p-4 text-neutral-300 rounded-md justify-between min-[1460px]:flex min-[1460px]:space-y-0 space-y-1.5 animate-pulse">
            Загрузка...
        </div>
    </div>
</template>

<style scoped>
.triangle1::after {
    content: "";
    position: absolute;
    top: 50%;
    right: 100%;
    margin-top: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: transparent #171717 transparent transparent;
}
</style>
