<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Présentation" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-purple">
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
            >
                Présentation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-transparent overflow-hidden sm:rounded-lg">
                    <div class="p-6 lg:p-8">
                        <h1 class="text-3xl font-medium text-gray-900 text-center mb-12 font-caveat">
                            Bienvenue dans mon univers
                        </h1>

                        <div class="mt-6 text-gray-500 leading-relaxed">
                            <!-- Contenu à personnaliser -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="flex flex-col justify-center">
                                    <h2
                                        class="text-xl font-semibold text-gray-700 mb-4"
                                    >
                                        À propos de moi
                                    </h2>
                                    <div class="space-y-4">
                                        <p>Votre texte de présentation ici...</p>
                                    </div>
                                </div>
                                <div
                                    class="flex items-start justify-center h-auto"
                                >
                                    <img
                                        src="/images/Fifi.jpg"
                                        alt="Photo de présentation"
                                        class="max-h-[400px] w-auto object-contain rounded-lg border-4 border-pastel-purple/30"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
