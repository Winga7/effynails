<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Tarifs" />

    <AppLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Nos Tarifs
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 lg:p-8">
                        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Exemple de carte tarif -->
                            <div
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 shadow-md"
                            >
                                <h3
                                    class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                                >
                                    Manucure Simple
                                </h3>
                                <div
                                    class="mt-4 text-3xl font-bold text-indigo-600 dark:text-indigo-400"
                                >
                                    30€
                                </div>
                                <ul
                                    class="mt-4 space-y-2 text-gray-600 dark:text-gray-300"
                                >
                                    <li>• Durée : 45 minutes</li>
                                    <li>• Soin des cuticules</li>
                                    <li>• Mise en forme</li>
                                    <li>• Pose vernis classique</li>
                                </ul>
                            </div>
                            <!-- Répéter pour d'autres prestations -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
