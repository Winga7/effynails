<script setup>
import { computed, useSlots } from "vue";
import SectionTitle from "./SectionTitle.vue";

defineEmits(["submitted"]);

const hasActions = computed(() => !!useSlots().actions);
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
            <form @submit.prevent="$emit('submitted')">
                <div
                    class="mt-8 bg-white/95 backdrop-blur-sm border border-pink-100 shadow-xl rounded-2xl px-4 py-6 sm:p-8 flex flex-col justify-center items-center w-full h-full"
                >
                    <div class="grid grid-cols-6 gap-6">
                        <slot name="form" />
                    </div>
                </div>

                <div
                    v-if="hasActions"
                    class="flex items-center justify-end px-4 py-3 bg-white/70 backdrop-blur-sm border-t border-pink-50 text-end sm:px-8 shadow-none rounded-b-2xl"
                >
                    <slot name="actions" />
                </div>
            </form>
        </div>
    </div>
</template>
