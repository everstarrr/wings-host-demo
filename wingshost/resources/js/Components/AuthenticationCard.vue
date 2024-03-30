<!--
  - Copyright (c) WINGS, 2023.
  - Property of WINGS. The distribution of proprietary software created by WINGS is strictly prohibited.
  - There Will Be Consequences. Think about it.
  -->

<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-3 sm:pt-0 bg-neutral-950">
        <div>
            <Transition>
                <slot name="logo" />
            </Transition>
        </div>

        <div class="w-full sm:max-w-md mt-3 px-6 py-4 bg-neutral-900 shadow-md overflow-hidden sm:rounded-lg">
            <slot />
        </div>
    </div>
</template>
