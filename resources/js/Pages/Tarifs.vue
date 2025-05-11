<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import tarifService from "@/Services/tarifService";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

// Charger les tarifs depuis le service partagé
const tarifs = ref([]);
const loading = ref(false);
const error = ref(null);

const breadcrumbItems = [
    { label: "Accueil", route: "home" },
    { label: "Tarifs", route: "tarifs" },
];

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
    <Head
        title="Tarifs Manucure Wavre | EFFYNAILS - Pose d'Ongles en Gel et Nail Art"
    >
        <meta
            name="description"
            content="Découvrez nos tarifs pour la manucure, pose d'ongles en gel et nail art à Wavre. Prix compétitifs pour tous nos services de beauté des ongles."
        />
        <meta
            name="keywords"
            content="Tarifs manucure Wavre, Prix pose ongles gel Wavre, Tarifs nail art Wavre, Steffi Ledoux tarifs"
        />
    </Head>

    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 leading-tight font-caveat"
            >
                Tarifs Manucure et Nail Art à Wavre
            </h2>
        </template>

        <div class="flex-grow py-12 flex flex-col justify-between">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 w-full">
                <!-- Breadcrumbs -->
                <div class="p-6 border-b">
                    <Breadcrumbs :items="breadcrumbItems" />
                </div>

                <!-- Indicateur de chargement -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-8"
                >
                    <div
                        class="animate-spin rounded-full h-10 w-10 border-4 border-pink-200 border-t-pink-400"
                    ></div>
                </div>

                <!-- Message d'erreur -->
                <div
                    v-if="error"
                    class="text-center py-8 text-pink-500 bg-pink-50 rounded-lg shadow-sm"
                >
                    {{ error }}
                </div>

                <!-- Grille des tarifs -->
                <div
                    v-if="!loading && !error"
                    class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <!-- Cartes de tarifs dynamiques -->
                    <div
                        v-for="tarif in tarifs"
                        :key="tarif.id"
                        class="relative bg-white rounded-2xl p-6 shadow-md transform transition-all duration-300 hover:scale-102 hover:shadow-lg overflow-hidden group"
                    >
                        <!-- Effet décoratif en haut à gauche -->
                        <div
                            class="absolute -top-10 -left-10 bg-pastel-pink w-20 h-20 rounded-full opacity-50"
                        ></div>

                        <!-- Effet décoratif en bas à droite -->
                        <div
                            class="absolute -bottom-10 -right-10 bg-pastel-peach w-20 h-20 rounded-full opacity-50"
                        ></div>

                        <div class="relative z-10">
                            <h3
                                class="text-xl font-caveat font-semibold text-gray-800"
                            >
                                {{ tarif.nom }}
                            </h3>
                            <div
                                class="mt-2 text-3xl font-caveat text-pink-500"
                            >
                                {{ tarif.prix }}€
                            </div>
                            <div
                                class="w-16 h-1 bg-gradient-to-r from-pastel-pink to-pastel-peach my-3 rounded-full"
                            ></div>
                            <div class="mt-2 space-y-1 text-gray-600">
                                <p class="flex items-center">
                                    <i
                                        class="fas fa-clock text-pastel-pink mr-2"
                                    ></i>
                                    {{ tarif.duree }} minutes
                                </p>
                                <p class="pt-1">{{ tarif.description }}</p>
                            </div>

                            <!-- Bouton réserver au survol -->
                            <div
                                class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                            >
                                <a
                                    href="#"
                                    class="inline-block px-5 py-1.5 bg-pastel-yellow-btn hover:bg-pastel-yellow text-sm font-medium rounded-full transition-colors duration-300"
                                >
                                    Réserver
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Message si aucun tarif -->
                    <div
                        v-if="tarifs.length === 0"
                        class="col-span-full text-center py-10 text-gray-500 bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm"
                    >
                        Aucun tarif disponible pour le moment.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
