<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<script setup>
import { usePage } from "@inertiajs/vue3";

defineProps({});

const page = usePage();
</script>

<template>
    <div class="lg:w-4/5 mx-auto sm:px-6 lg:px-9 float-right w-full mt-16">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">Referrals</h2>
        <div class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mt-5">
            <div class="p-6 lg:p-8 bg-gradient-to-bl from-gray-700/50 via-transparent border-b border-gray-700">
                <p class="text-gray-400 leading-relaxed">Referrals</p>
            </div>
        </div>
    </div>
</template>
