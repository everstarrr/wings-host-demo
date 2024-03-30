<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import FreeDomain from "@/Pages/Servers/Chunks/Components/FreeDomain.vue";
import Ports from "@/Pages/Servers/Chunks/Components/Ports.vue";

const props = defineProps({
    server: Object,
});

const page = usePage();

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

setTimeout(function () {
    get_allocations();
}, 500);
</script>

<template>
    <div class="bg-neutral-900 rounded-2xl border border-neutral-700 overflow-hidden mt-10 mb-10">
        <section class="p-8 bg-neutral-900 section">
            <div class="section__item section__item--start pr-2 min-[700px]:pb-0 pb-4">
                <p class="text-neutral-300 text-2xl mb-4">{{ $t("servers.free_domain") }}</p>
                <FreeDomain :enabled="true" />
            </div>

            <div class="section__item section__item--end pl-2 min-[700px]:pt-0 pt-4">
                <p class="text-neutral-300 text-2xl mb-4">{{ $t("servers.allocations") }}</p>
                <Ports
                    :allocations="allocations"
                    :server="server" />
            </div>
        </section>
    </div>
</template>
