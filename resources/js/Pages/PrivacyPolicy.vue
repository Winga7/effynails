<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    policy: String,
});
</script>

<template>
    <Head title="Politique de Confidentialité" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <template #header>
            <h2
                class="font-semibold text-3xl text-gray-800 font-caveat leading-tight"
            >
                Politique de Confidentialité
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-sm rounded-lg"
                >
                    <div
                        class="p-6 lg:p-8 prose max-w-none text-gray-700"
                        v-html="policy"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
