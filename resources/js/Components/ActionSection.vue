<script setup>
import SectionTitle from "./SectionTitle.vue";
</script>

<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="p-6 h-full flex items-center">
            <SectionTitle>
                <template #title>
                    <slot name="title" />
                </template>
                <template #description>
                    <slot name="description" />
                </template>
            </SectionTitle>
        </div>

        <div
            class="md:mt-0 md:col-span-2 md:flex md:items-center md:justify-center md:min-h-[340px] md:h-full md:w-full"
        >
            <div
                class="mt-8 bg-white/95 backdrop-blur-sm border border-pink-100 shadow-xl rounded-2xl px-4 py-6 sm:p-8 flex flex-col justify-center items-center w-full h-full"
            >
                <slot name="content" />
            </div>
        </div>
    </div>
</template>
