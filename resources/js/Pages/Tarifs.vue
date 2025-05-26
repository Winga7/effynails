<script setup>
/**
 * 💰 Page des Tarifs
 *
 * Page publique affichant tous les tarifs des services de manucure et nail art.
 * Charge dynamiquement les données via l'API et affiche les tarifs
 * sous forme de cartes avec animations et boutons de réservation.
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires Breadcrumbs
 * @requires tarifService
 */

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import tarifService from "@/Services/tarifService";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";
import axios from "axios";

// 📊 État réactif des tarifs
const tarifs = ref([]); // Liste des tarifs chargés depuis l'API
const loading = ref(false); // État de chargement des données
const error = ref(null); // Message d'erreur éventuel
const errorMessage = ref(null); // Message d'erreur à afficher à l'utilisateur

// 🍞 Fil d'Ariane pour la navigation
const breadcrumbItems = [
    { label: "Accueil", route: "home" },
    { label: "Tarifs", route: "tarifs" },
];

/**
 * 🚀 Initialisation du composant
 * Charge les tarifs depuis l'API au montage de la page
 *
 * @function onMounted
 * @description Initialise le composant et charge les données des tarifs
 */
onMounted(async () => {
    // 📥 Charger les tarifs depuis l'API
    loading.value = true;
    try {
        const response = await axios.get("/api/tarifs");
        tarifs.value = response.data;
    } catch (error) {
        // Log l'erreur côté serveur
        await axios.post("/api/logs", {
            level: "error",
            message: "Erreur lors du chargement des tarifs",
            context: error.message,
        });
        // Afficher un message d'erreur à l'utilisateur
        errorMessage.value =
            "Une erreur est survenue lors du chargement des tarifs. Veuillez réessayer plus tard.";
    } finally {
        loading.value = false;
    }
});
</script>

<template>
    <!-- 🏷️ Métadonnées SEO pour la page des tarifs -->
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

    <!-- 🏗️ Layout principal avec dégradé rose -->
    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <!-- 📊 En-tête de page -->
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 leading-tight font-caveat"
            >
                Tarifs Manucure et Nail Art à Wavre
            </h2>
        </template>

        <div class="flex-grow py-12 flex flex-col justify-between">
            <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 w-full">
                <!-- 🍞 Fil d'Ariane -->
                <div class="p-6">
                    <Breadcrumbs :items="breadcrumbItems" />
                </div>

                <!-- ⏳ Indicateur de chargement -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-8"
                >
                    <div
                        class="animate-spin rounded-full h-10 w-10 border-4 border-pink-200 border-t-pink-400"
                    ></div>
                </div>

                <!-- ❌ Message d'erreur -->
                <div
                    v-if="errorMessage"
                    class="text-center py-8 text-pink-500 bg-pink-50 rounded-lg shadow-sm"
                >
                    {{ errorMessage }}
                </div>

                <!-- 🎨 Grille des cartes de tarifs -->
                <div
                    v-if="!loading && !errorMessage"
                    class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8"
                >
                    <!-- 💳 Cartes de tarifs dynamiques -->
                    <div
                        v-for="tarif in tarifs"
                        :key="tarif.id"
                        class="relative bg-white rounded-2xl p-6 shadow-md transform transition-all duration-300 hover:scale-102 hover:shadow-lg overflow-hidden group"
                    >
                        <!-- 🎆 Effet décoratif en haut à gauche -->
                        <div
                            class="absolute -top-10 -left-10 bg-pastel-pink w-20 h-20 rounded-full opacity-50"
                        ></div>

                        <!-- 🎆 Effet décoratif en bas à droite -->
                        <div
                            class="absolute -bottom-10 -right-10 bg-pastel-peach w-20 h-20 rounded-full opacity-50"
                        ></div>

                        <!-- 📝 Contenu de la carte -->
                        <div class="relative z-10">
                            <!-- 📌 Nom du service -->
                            <h3
                                class="text-xl font-caveat font-semibold text-gray-800"
                            >
                                {{ tarif.nom }}
                            </h3>

                            <!-- 💰 Prix du service -->
                            <div
                                class="mt-2 text-3xl font-caveat text-pink-500"
                            >
                                {{ tarif.prix }}€
                            </div>

                            <!-- 🎨 Séparateur décoratif -->
                            <div
                                class="w-16 h-1 bg-gradient-to-r from-pastel-pink to-pastel-peach my-3 rounded-full"
                            ></div>

                            <!-- ℹ️ Informations du service -->
                            <div class="mt-2 space-y-1 text-gray-600">
                                <!-- ⏱️ Durée du service -->
                                <p class="flex items-center">
                                    <i
                                        class="fas fa-clock text-pastel-pink mr-2"
                                    ></i>
                                    {{ tarif.duree }} minutes
                                </p>
                                <!-- 📝 Description du service -->
                                <p class="pt-1">{{ tarif.description }}</p>
                            </div>

                            <!-- 📅 Bouton réserver (visible au survol) -->
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

                    <!-- 📭 Message si aucun tarif disponible -->
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
