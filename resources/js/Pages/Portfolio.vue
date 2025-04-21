<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Portfolio" />

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Portfolio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 lg:p-8">
                        <!-- Grille de photos -->
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4"
                        >
                            <!-- Exemple de carte photo -->
                            <div
                                class="group relative overflow-hidden rounded-lg shadow-md"
                            >
                                <div
                                    class="aspect-square bg-gray-200 dark:bg-gray-700 flex items-center justify-center"
                                >
                                    <span class="text-gray-400">Photo</span>
                                </div>
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center"
                                >
                                    <span class="text-white text-sm"
                                        >Description de la réalisation</span
                                    >
                                </div>
                            </div>
                            <!-- Répéter pour d'autres photos -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
