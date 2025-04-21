<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import tarifService from "@/Services/tarifService";

// Charger les tarifs depuis le service partagé
const tarifs = ref([]);
const loading = ref(false);
const error = ref(null);

onMounted(async () => {
    // Charger les tarifs depuis l'API
    loading.value = true;
    try {
        tarifs.value = await tarifService.getTarifs();
    } catch (err) {
        error.value = "Erreur lors du chargement des tarifs";
        console.error(err);
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <Head title="Tarifs" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Nos Tarifs
            </h2>
        </template>

        <div class="py-12 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/70 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 lg:p-8">
                        <!-- Indicateur de chargement -->
                        <div
                            v-if="loading"
                            class="flex justify-center items-center py-8"
                        >
                            <div
                                class="animate-spin rounded-full h-10 w-10 border-b-2 border-pink-300"
                            ></div>
                        </div>

                        <!-- Message d'erreur -->
                        <div
                            v-if="error"
                            class="text-center py-8 text-pink-300"
                        >
                            {{ error }}
                        </div>

                        <!-- Grille des tarifs -->
                        <div
                            v-if="!loading && !error"
                            class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6"
                        >
                            <!-- Cartes de tarifs dynamiques -->
                            <div
                                v-for="tarif in tarifs"
                                :key="tarif.id"
                                class="bg-pink-900/80 rounded-lg p-6 shadow-md transform transition duration-300 hover:scale-105"
                            >
                                <h3 class="text-lg font-semibold text-pink-100">
                                    {{ tarif.nom }}
                                </h3>
                                <div
                                    class="mt-4 text-3xl font-bold text-pink-300"
                                >
                                    {{ tarif.prix }}€
                                </div>
                                <div class="mt-4 space-y-2 text-pink-100">
                                    <p>• Durée : {{ tarif.duree }} minutes</p>
                                    <p>{{ tarif.description }}</p>
                                </div>
                            </div>

                            <!-- Message si aucun tarif -->
                            <div
                                v-if="tarifs.length === 0"
                                class="col-span-full text-center py-10 text-pink-200"
                            >
                                Aucun tarif disponible pour le moment.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
