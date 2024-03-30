<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link
        :href="route('login')"
        class="font-[OpenSans-SemiBold] text-neutral-400 border-2 rounded-3xl p-2 px-5 hover:text-white hover:border-white transition-all ease-in-out duration-150 lg:inline hidden">
        {{ $t("auth.login") }}
    </Link>

    <Link
        :href="route('register')"
        class="ml-4 font-[OpenSans-SemiBold] text-neutral-950 bg-white border-2 border-transparent rounded-3xl p-2 px-5 hover:bg-wings-light transition-all ease-in-out duration-150 xl:inline hidden">
        {{ $t("auth.register") }}
    </Link>
</template>

<style scoped></style>
