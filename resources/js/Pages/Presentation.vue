<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Présentation" />

    <AppLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Présentation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 lg:p-8">
                        <h1
                            class="text-2xl font-medium text-gray-900 dark:text-gray-100"
                        >
                            Bienvenue dans mon univers
                        </h1>

                        <div
                            class="mt-6 text-gray-500 dark:text-gray-400 leading-relaxed"
                        >
                            <!-- Contenu à personnaliser -->
                            <div class="grid md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <h2
                                        class="text-xl font-semibold text-gray-700 dark:text-gray-300"
                                    >
                                        À propos de moi
                                    </h2>
                                    <p>Votre texte de présentation ici...</p>
                                </div>
                                <div
                                    class="rounded-lg overflow-hidden shadow-lg"
                                >
                                    <!-- Emplacement pour une image -->
                                    <div
                                        class="bg-gray-200 dark:bg-gray-700 h-64 flex items-center justify-center"
                                    >
                                        <span class="text-gray-400"
                                            >Image à venir</span
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
