<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { router, useForm } from "@inertiajs/vue3";
import moment from "moment";
import DialogModal from "@/Components/DialogModal.vue";
import { ref } from "vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faBan, faFileLines, faReceipt } from "@fortawesome/free-solid-svg-icons";
import CodeBlock from "@/Components/CodeBlock.vue";
import { faCopy } from "@fortawesome/free-regular-svg-icons";
import { TailwindPagination } from "laravel-vue-pagination";
import TeamTransaction from "@/Pages/Dash/Chunks/Payments/TeamTransaction.vue";
import Topup from "@/Pages/Dash/Chunks/Payments/Topup.vue";

const copied = ref("");
const copyToClipboard = async (copy_value) => {
    await navigator.clipboard
        .writeText(copy_value)
        .then(() => {
            copied.value = copy_value;
            setTimeout(function () {
                copied.value = "";
            }, 2000);
        })
        .catch((error) => {
            console.error("Error when performing a text copy operation: " + error);
        });
};

const showing_description = ref(null);

const form = useForm({
    amount: 100,
    currency: "rub",
});

const submit = () => {
    form.post(route("api.v1.payments.pay"));
};

const transactions = ref({});
const get_transactions = async (page = 1) => {
    await axios
        .get(route("api.v1.payments.transactions", { page: page }))
        .then(function (response) {
            transactions.value = response.data;
        })
        .catch((err) => {
            if (!axios.isCancel(err)) {
                transactions.value = "error";
            }
        });
};
get_transactions();

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

ym(96335758, "init", {
    clickmap: true,
    trackLinks: true,
    accurateTrackBounce: true,
    webvisor: true,
});
</script>

<template>
    <DialogModal
        :show="showing_description !== null"
        @close="showing_description = null"
        max-width="6xl">
        <template #title>Описание транзакции</template>
        <template #content>
            <div class="mt-4">
                <span class="sm:text-base text-xs">{{ showing_description.split(":")[0] }}:</span>
                <CodeBlock
                    class="sm:ml-10 sm:text-base text-xs"
                    :code="showing_description"
                    format="tag:json" />
            </div>
        </template>
        <template #footer>
            <button
                @click="showing_description = null"
                type="button"
                class="w-max px-6 py-auto h-10 leading-4 font-medium rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out block ml-3">
                {{ $t("base.close") }}
            </button>
        </template>
    </DialogModal>

    <h2 class="font-medium text-2xl text-white lg:text-left text-center mt-12 lg:mt-8 mx-8">
        {{ $t("payments.payments") }}
    </h2>

    <div class="flex-col justify-center overflow-x-hidden">
        <section class="section mb-8 border-b border-neutral-700 mx-8">
            <!-- Left Side -->
            <div class="my-8 xl:mr-2 section__item section__item--start">
                <Topup />
            </div>

            <!-- Right Side -->
            <div class="my-8 xl:ml-2 section__item section__item--end">
                <TeamTransaction :enabled="false" />
            </div>
        </section>

        <div class="mb-8 mx-8">
            <p class="text-neutral-300 text-2xl text-center">История транзакций</p>
            <p
                v-if="!transactions.data"
                class="text-neutral-500 text-center mt-6">
                У вас нет совершённых транзакций
            </p>
            <!-- Default Transactions History -->
            <div v-for="transaction in transactions.data">
                <div class="bg-neutral-800 p-4 text-neutral-300 mt-6 rounded-md flex-col space-y-2">
                    <div class="flex justify-between w-full items-center">
                        <span
                            v-if="transaction.is_confirming"
                            class="text-sm text-yellow-400 underline">
                            Подтверждение
                        </span>
                        <span
                            class="text-sm text-green-500 underline"
                            v-else-if="transaction.amount > 0"
                            >Пополнение</span
                        >
                        <span
                            class="text-sm text-purple-400 underline"
                            v-else-if="parseFloat(transaction.amount) === 0"
                            >Отклонена<font-awesome-icon
                                class="no-underline ml-2 text-red-600"
                                :icon="faBan"
                        /></span>
                        <span
                            class="text-sm text-red-500 underline"
                            v-else
                            >Списание</span
                        >

                        <span
                            v-if="transaction.is_confirming"
                            class="text-yellow-400 text-xs sm:text-sm">
                            +{{ parseFloat(transaction.amount) }}&#8381;
                        </span>
                        <span
                            class="text-green-500 text-xs sm:text-sm"
                            v-else-if="transaction.amount > 0"
                            >+{{ parseFloat(transaction.amount) }}&#8381;</span
                        >
                        <span
                            class="text-purple-400 text-xs sm:text-sm"
                            v-else-if="parseFloat(transaction.amount) === 0"
                            >+/-{{ parseFloat(transaction.amount) }}&#8381;</span
                        >
                        <span
                            class="text-red-500 text-xs sm:text-sm"
                            v-else
                            >{{ parseFloat(transaction.amount) }}&#8381;</span
                        >
                    </div>

                    <div class="flex justify-between w-full items-center">
                        <span class="text-sm text-neutral-500">Время</span>
                        <span class="text-xs sm:text-sm"
                            >{{ moment(transaction.created_at).format("DD.MM.YYYY / HH:mm:ss") }} UTC+3</span
                        >
                    </div>

                    <div class="flex justify-between w-full items-center">
                        <span class="text-sm text-neutral-500">UUID</span>
                        <span class="cursor-pointer text-xs sm:text-sm"
                            >{{ transaction.uuid }}
                            <span
                                v-if="copied === transaction.uuid"
                                class="text-neutral-300 text-sm bg-neutral-800/80 p-2 rounded-lg triangle2 absolute">
                                {{ $t("servers.id.copied") }}
                            </span>
                            <font-awesome-icon
                                @click="copyToClipboard(transaction.uuid)"
                                :icon="faCopy"
                                class="ml-2" />
                            <font-awesome-icon
                                v-if="
                                    transaction.description.substring(0, transaction.description.indexOf(':')) ===
                                        'payment' &&
                                    transaction.amount > 0 &&
                                    !transaction.is_confirming
                                "
                                @click="router.get(route('pay', transaction.uuid))"
                                :icon="faReceipt"
                                class="ml-2" />
                        </span>
                    </div>

                    <template v-if="transaction.server_id">
                        <div class="flex justify-between w-full items-center">
                            <span class="text-sm text-neutral-500">Сервер</span>
                            <span class="text-xs sm:text-sm">WH_INTERNAL_ID: {{ transaction.server_id }}</span>
                        </div>
                    </template>

                    <div
                        class="flex justify-between w-full items-center"
                        v-if="transaction.fee">
                        <span class="text-sm text-neutral-500">Комиссия</span>
                        <span>{{ transaction.fee }}</span>
                    </div>

                    <div
                        class="flex justify-between w-full items-center"
                        v-if="transaction.description">
                        <span class="text-sm text-neutral-500">Описание</span>
                        <span
                            class="cursor-pointer text-xs sm:text-sm"
                            v-if="transaction.description.length > 20"
                            @click="showing_description = transaction.description"
                            >{{ transaction.description.substring(0, transaction.description.indexOf(":")) }}
                            <font-awesome-icon
                                :icon="faFileLines"
                                class="ml-2"
                        /></span>
                        <span v-else>{{ transaction.description }}</span>
                    </div>

                    <div
                        class="flex justify-between w-full items-center cursor-progress"
                        v-if="transaction.is_confirming">
                        <span class="text-sm text-yellow-400">Статус подтверждения</span>
                        <span class="text-yellow-400 text-xs sm:text-sm text-left"
                            >Транзакция находится в процессе подтверждения</span
                        >
                    </div>
                </div>
            </div>
            <div
                v-if="transactions.data"
                class="w-full justify-center flex">
                <TailwindPagination
                    class="mt-8 scale-125"
                    :item-classes="[
                        'bg-transparent',
                        'text-neutral-300',
                        'text-lg',
                        'border-0',
                        'hover:text-wings-light',
                    ]"
                    :limit="5"
                    :active-classes="['border-0', 'text-wings-light']"
                    :data="transactions"
                    @pagination-change-page="get_transactions" />
            </div>
        </div>
    </div>
</template>

<style>
button:hover span span {
    color: #d4d4d4;
    transition: all 150ms ease-in-out;
}

button:focus span span {
    color: #d4d4d4;
    transition: all 150ms ease-in-out;
}

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
</style>
