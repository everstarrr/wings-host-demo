<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { usePage } from "@inertiajs/vue3";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faLock, faPen, faTrash, faXmark } from "@fortawesome/free-solid-svg-icons";
import { onMounted, onUnmounted, ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import DialogModal from "@/Components/DialogModal.vue";
import Checkbox from "@/Components/Checkbox.vue";

defineProps({
    server: Object,
});

const page = usePage();

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

const edit = ref(false);
const add = ref(false);

const controlCheckbox = ref(false);

const closeOnEscape = (e) => {
    if ((add.value || edit.value) && e.key === "Escape") {
        add.value = false;
        edit.value = false;
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));
onUnmounted(() => document.removeEventListener("keydown", closeOnEscape));
</script>

<template>
    <div class="w-full flex-col mt-10 space-y-4">
        <div
            class="bg-neutral-900 rounded-2xl border p-6 border-neutral-700 w-full flex justify-between text-neutral-300">
            <div class="flex md:items-center justify-between w-full md:flex-row flex-col md:space-y-0 space-y-4">
                <div class="flex items-center space-x-6">
                    <img
                        class="h-8 w-8 rounded-full object-cover"
                        :src="
                            'https://ui-avatars.com/api/?name=' +
                            $page.props.auth.user.u_name +
                            '&color=7F9CF5&background=EBF4FF'
                        "
                        :alt="$page.props.auth.user.f_name + ' | ' + $page.props.auth.user.u_name" />
                    <span>superhub@govno.xyz</span>
                </div>
                <div class="flex space-x-10 mr-4 items-center">
                    <div class="flex md:space-x-10 md:flex-row flex-col items-center md:space-y-0 space-y-0.5">
                        <div class="md:flex-col flex items-center md:mt-1.5 md:space-y-1.5 md:space-x-0 space-x-2">
                            <font-awesome-icon
                                :icon="faLock"
                                style="color: #22c55e" />
                            <span class="text-neutral-500">2FA ВКЛЮЧЕНА</span>
                        </div>
                        <div class="md:flex-col flex items-center md:space-x-0 space-x-2 md:-ml-0 -ml-2.5">
                            <span class="text-neutral-300 text-lg">0</span>
                            <span class="text-neutral-500">РАЗРЕШЕНИЯ</span>
                        </div>
                    </div>

                    <div class="space-x-10">
                        <button
                            class="text-neutral-500 hover:text-neutral-400 transition-all duration-150 ease-in-out"
                            type="button"
                            @click="edit = true">
                            <font-awesome-icon :icon="faPen" />
                            <DialogModal :show="edit || add">
                                <template #title>
                                    <div class="flex mt-2 justify-between">
                                        <span
                                            class="text-xl"
                                            v-if="edit === true"
                                            >Редактировать разрешения</span
                                        >
                                        <span
                                            class="text-xl"
                                            v-if="add === true"
                                            >Добавить пользователя</span
                                        >
                                        <button
                                            type="button"
                                            @click="
                                                edit = false;
                                                add = false;
                                            ">
                                            <font-awesome-icon
                                                :icon="faXmark"
                                                class="w-6 h-6" />
                                        </button>
                                    </div>
                                </template>
                                <template #content>
                                    <div
                                        class="mt-8"
                                        v-if="add === true">
                                        <span class="text-neutral-400 ml-0.5 text-sm mb-0.5">E-mail</span>
                                        <TextInput class="w-full h-12 mb-0.5" />
                                        <span
                                            >Введите e-mail пользователя, которому хотите предоставить права управления
                                            сервером.</span
                                        >
                                    </div>
                                    <section class="mt-8 overflow-y-scroll hide-scrollbar space-y-4">
                                        <!-- CONTROL -->
                                        <div class="bg-neutral-900 p-6 rounded-md">
                                            <div
                                                class="flex justify-between items-center border-b-2 border-neutral-700 pb-2 mb-3">
                                                <p class="text-xl text-neutral-300">Управление</p>
                                                <Checkbox
                                                    class="w-6 h-6 mb-1"
                                                    @click="controlCheckbox = !controlCheckbox" />
                                            </div>
                                            <h2 class="text-base">
                                                Разрешения, позволяющие управлять питанием сервера, а также отправлять
                                                команды.
                                            </h2>
                                            <div class="mt-4">
                                                <div class="flex-col space-y-4 ml-4">
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox
                                                            class="w-6 h-6"
                                                            :checked="controlCheckbox" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">КОНСОЛЬ</p>
                                                            <span
                                                                >Позволяет отправлять команды на сервер через
                                                                консоль.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox
                                                            class="w-6 h-6"
                                                            :checked="controlCheckbox" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">СТАРТ</p>
                                                            <span>Позволяет запускать сервер, если он выключен.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox
                                                            class="w-6 h-6"
                                                            :checked="controlCheckbox" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">СТОП</p>
                                                            <span>Позволяет выключать сервер, если он запущен.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox
                                                            class="w-6 h-6"
                                                            :checked="controlCheckbox" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">ПЕРЕЗАПУСК</p>
                                                            <span
                                                                >Позволяет перезапускать сервер. Это разрешение
                                                                позволяет запускать сервер, если он выключен, но не
                                                                позволяет перманентно останавливать его.</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- USER -->
                                        <div class="bg-neutral-900 p-6 rounded-md">
                                            <div
                                                class="flex justify-between items-center border-b-2 border-neutral-700 pb-2 mb-3">
                                                <p class="text-xl text-neutral-300">Пользователь</p>
                                                <Checkbox class="w-6 h-6 mb-1" />
                                            </div>
                                            <h2 class="text-base">
                                                Разрешения, позволяющие управлять другими пользователями этого сервера.
                                                Добавленные пользователи не могут управлять собственными разрешениями
                                                или выдавать другим пользователям разрешения, которых нет у них самих.
                                            </h2>
                                            <div class="mt-4">
                                                <div class="flex-col space-y-4 ml-4">
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">СОЗДАНИЕ</p>
                                                            <span
                                                                >Позволяет добавлять приглашённых пользователей на
                                                                сервер.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">ПРОСМОТР</p>
                                                            <span
                                                                >Позволяет просматривать приглашённых пользователей и их
                                                                разрешения.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">РЕДАКТИРОВАНИЕ</p>
                                                            <span
                                                                >Позволяет управлять разрешениями приглашённых
                                                                пользователей.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">УДАЛЕНИЕ</p>
                                                            <span
                                                                >Позволяет удалять приглашённых пользователей с
                                                                сервера.</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- FILE -->
                                        <div class="bg-neutral-900 p-6 rounded-md">
                                            <div
                                                class="flex justify-between items-center border-b-2 border-neutral-700 pb-2 mb-3">
                                                <p class="text-xl text-neutral-300">Файлы</p>
                                                <Checkbox class="w-6 h-6 mb-1" />
                                            </div>
                                            <h2 class="text-base">
                                                Разрешения, позволяющие вмешиваться в файловую систему сервера.
                                            </h2>
                                            <div class="mt-4">
                                                <div class="flex-col space-y-4 ml-4">
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">СОЗДАНИЕ</p>
                                                            <span
                                                                >Позволяет создавать новые файлы и папки через Панель
                                                                или загружать их напрямую.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">ЧТЕНИЕ</p>
                                                            <span
                                                                >Позволяет просматривать содержимое папок, но не
                                                                позволяет просматривать и скачивать файлы.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">
                                                                ПРОСМОТР СОДЕРЖИМОГО
                                                            </p>
                                                            <span>Позволяет просматривать и скачивать файлы.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">РЕДАКТИРОВАНИЕ</p>
                                                            <span>Позволяет редактировать файлы и папки.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">УДАЛЕНИЕ</p>
                                                            <span>Позволяет удалять файлы и папки.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">АРХИВИРОВАНИЕ</p>
                                                            <span>Позволяет архивировать и распаковывать файлы.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">SFTP</p>
                                                            <span
                                                                >Позволяет подключаться к SFTP и управлять файлами на
                                                                сервере, используя другие разрешения.</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- BACKUP -->
                                        <div class="bg-neutral-900 p-6 rounded-md">
                                            <div
                                                class="flex justify-between items-center border-b-2 border-neutral-700 pb-2 mb-3">
                                                <p class="text-xl text-neutral-300">Резервные копии</p>
                                                <Checkbox class="w-6 h-6 mb-1" />
                                            </div>
                                            <h2 class="text-base">
                                                Разрешения, позволяющие создавать и управлять резервными копиями.
                                            </h2>
                                            <div class="mt-4">
                                                <div class="flex-col space-y-4 ml-4">
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">СОЗДАНИЕ</p>
                                                            <span>Позволяет создавать новые резервные копии.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">ЧТЕНИЕ</p>
                                                            <span
                                                                >Позволяет просматривать все созданные резервные
                                                                копии.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">УДАЛЕНИЕ</p>
                                                            <span>Позволяет удалять резервные копии.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">ЗАГРУЗКА</p>
                                                            <span
                                                                >Позволяет загружать резервные копии с сервера.
                                                                Осторожно: это разрешение позволяет получить доступ ко
                                                                всем данным сервера через резервную копию.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">ВОССТАНОВЛЕНИЕ</p>
                                                            <span
                                                                >Позволяет востанавливать резервные копии на сервере.
                                                                Осторожно: это разрешение позволяет удалить все файлы на
                                                                сервере в процессе восстановления.</span
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- ALLOCATIONS -->
                                        <div class="bg-neutral-900 p-6 rounded-md">
                                            <div
                                                class="flex justify-between items-center border-b-2 border-neutral-700 pb-2 mb-3">
                                                <p class="text-xl text-neutral-300">Порты</p>
                                                <Checkbox class="w-6 h-6 mb-1" />
                                            </div>
                                            <h2 class="text-base">
                                                Разрешения, позволяющие управлять выделенными портами сервера.
                                            </h2>
                                            <div class="mt-4">
                                                <div class="flex-col space-y-4 ml-4">
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">ЧТЕНИЕ</p>
                                                            <span
                                                                >Позволяет просматривать все существующие порты сервера.
                                                                Основной порт могут просматривать пользователи с любым
                                                                уровнем доступа.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">СОЗДАНИЕ</p>
                                                            <span>Позволяет создавать дополнительные порты.</span>
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">РЕДАКТИРОВАНИЕ</p>
                                                            <span
                                                                >Позволяет изменять основной порт сервера и добавлять
                                                                описание к каждому порту.</span
                                                            >
                                                        </div>
                                                    </div>
                                                    <div class="flex space-x-6 items-center">
                                                        <Checkbox class="w-6 h-6" />
                                                        <div class="flex-col space-y-1">
                                                            <p class="text-neutral-300 text-base">УДАЛЕНИЕ</p>
                                                            <span>Позволяет удалять порты.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            class="w-48 px-6 py-auto h-12 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-900 bg-neutral-300 hover:bg-neutral-400 focus:bg-neutral-400 transition-all duration-150 ease-in-out mt-6 text-base">
                                            <span v-if="add === true">Добавить</span>
                                            <span v-if="edit === true">Сохранить</span>
                                        </button>
                                    </section>
                                </template>
                                <template #footer></template>
                            </DialogModal>
                        </button>
                        <button class="text-neutral-500 hover:text-neutral-400 transition-all duration-150 ease-in-out">
                            <font-awesome-icon :icon="faTrash" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button
        @click="add = !add"
        type="button"
        class="w-max px-16 py-auto h-12 leading-4 font-['OpenSans-Medium'] rounded-lg text-neutral-400 border-2 border-neutral-500 hover:border-neutral-300 transition-all focus:border-neutral-300 focus:text-neutral-300 duration-150 ease-in-out mt-6 hover:text-neutral-300">
        Добавить
    </button>
</template>
