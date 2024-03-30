<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { Link } from "@inertiajs/vue3";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { faChevronLeft } from "@fortawesome/free-solid-svg-icons";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <AppLayout
        :title="$t('nav.articles')"
        :show-angle="false">
        <section class="w-full bg-neutral-950 overflow-x-hidden p-6 lg:p-8 flex justify-center">
            <!-- Margins for all elements -->
            <Link
                :href="route('articles')"
                class="text-xl text-neutral-400 font-['OpenSans-SemiBold'] lg:mx-10 mx-4 top-32 right-[86%] lg:flex hidden items-center fixed hover:text-neutral-300 transition-all duration-150 bebrik">
                <font-awesome-icon
                    :icon="faChevronLeft"
                    class="w-6 h-6 podbebrik"
                    style="" />
                <span class="ml-2">Назад</span>
            </Link>
            <div
                class="xl:mt-24 w-full md:w-2/3 md:mt-20 mt-16 text-3xl text-neutral-300 font-['OpenSans-SemiBold'] lg:mx-10 mx-4">
                <p class="mb-8 text-center"><slot name="title" /></p>
                <div class="text-lg font-['OpenSans-SemiBold'] text-neutral-400 flex flex-col space-y-8">
                    <slot name="content" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
.bebrik:hover .podbebrik {
    color: #d4d4d4;
}
</style>
