<script setup>
/**
 * 🎨 Page Portfolio
 *
 * Galerie dynamique des réalisations de manucure et nail art.
 * Utilise vue-waterfall-plugin-next pour une mise en page responsive.
 * Affiche les albums avec leurs photos dans une grille adaptative.
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires Waterfall
 * @requires Breadcrumbs
 */

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { Waterfall } from "vue-waterfall-plugin-next";
import "vue-waterfall-plugin-next/dist/style.css";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

// 📚 Props pour la gestion des données
const props = defineProps({
    albums: {
        type: Array,
        default: () => [], // Liste des albums avec leurs photos
    },
    pagination: {
        type: Object,
        default: () => ({}), // Configuration de la pagination
    },
});

/**
 * 🔍 Vérification de la disponibilité des albums
 *
 * @function hasAlbums
 * @returns {boolean} True si des albums sont disponibles
 */
const hasAlbums = () => {
    return props.albums && props.albums.length > 0;
};

/**
 * 📄 Vérification de la nécessité de pagination
 *
 * @function hasPagination
 * @returns {boolean} True si la pagination est nécessaire
 */
const hasPagination = () => {
    return (
        props.pagination && props.pagination.total > props.pagination.per_page
    );
};

/**
 * 🖼️ Génération de l'URL de l'image
 *
 * @function getImageUrl
 * @param {string} imagePath - Chemin de l'image
 * @returns {string} URL complète de l'image
 */
const getImageUrl = (imagePath) => {
    return `/storage/${imagePath}`;
};

// 🗺️ Configuration des éléments de navigation
const breadcrumbItems = [
    { label: "Accueil", route: "home" },
    { label: "Portfolio", route: "portfolio" },
];
</script>

<template>
    <Head title="Portfolio - EFFYNAILS | Manucure et Nail Art à Wavre">
        <meta
            name="description"
            content="Découvrez notre portfolio de manucure et nail art à Wavre. Retrouvez nos plus belles réalisations en pose d'ongles en gel et nail art personnalisé."
        />
        <meta
            name="keywords"
            content="Portfolio manucure Wavre, Nail art Wavre, Pose d'ongles en gel Wavre, Galerie photos manucure"
        />
    </Head>

    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
            >
                Portfolio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Breadcrumbs -->
                <div
                    class="p-6 border-b bg-white/90 backdrop-blur-sm rounded-t-lg border border-pink-100"
                >
                    <Breadcrumbs :items="breadcrumbItems" />
                </div>

                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-pink-100 border-t-0"
                >
                    <div class="p-6 lg:p-8">
                        <!-- Titre de la section -->
                        <h3
                            class="text-2xl font-caveat text-pink-600 mb-6 text-center"
                        >
                            Nos plus belles réalisations
                        </h3>

                        <!-- État vide - aucun album disponible -->
                        <div v-if="!hasAlbums()" class="py-12 text-center">
                            <div
                                class="border-2 border-dashed border-pink-200 rounded-lg py-16 px-4 mx-auto max-w-lg"
                            >
                                <svg
                                    class="w-16 h-16 text-pink-300 mx-auto mb-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    ></path>
                                </svg>
                                <h4
                                    class="text-lg font-medium text-gray-700 mb-2"
                                >
                                    Notre galerie se prépare
                                </h4>
                                <p class="text-gray-500 mb-6">
                                    Nos plus belles réalisations seront bientôt
                                    disponibles. Revenez nous voir prochainement
                                    !
                                </p>
                                <a
                                    href="{{ route('contact') }}"
                                    class="inline-flex items-center px-4 py-2 bg-pink-100 border border-transparent rounded-md font-semibold text-xs text-pink-700 uppercase tracking-widest hover:bg-pink-200 focus:bg-pink-200 active:bg-pink-300 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition"
                                >
                                    Prendre rendez-vous
                                </a>
                            </div>
                        </div>

                        <!-- Contenu dynamique - quand des albums existent -->
                        <div v-else>
                            <!-- Grille d'albums avec Waterfall -->
                            <Waterfall
                                :list="albums"
                                :column-width="320"
                                :gutter="24"
                            >
                                <template #item="{ item: album }">
                                    <a
                                        :href="
                                            route('portfolio.show', album.id)
                                        "
                                        class="block"
                                    >
                                        <div
                                            class="relative overflow-hidden rounded-lg shadow-lg"
                                        >
                                            <!-- Album Images Grid -->
                                            <div
                                                class="grid grid-cols-2 grid-rows-2 gap-1 w-full aspect-square relative h-full min-h-0 min-w-0"
                                                :class="'sm:aspect-square aspect-[4/3]'"
                                            >
                                                <template
                                                    v-if="
                                                        album.portfolios
                                                            .length === 1
                                                    "
                                                >
                                                    <div
                                                        class="col-span-2 row-span-2 aspect-square overflow-hidden h-full w-full min-h-0 min-w-0 group"
                                                    >
                                                        <img
                                                            :src="
                                                                getImageUrl(
                                                                    album
                                                                        .portfolios[0]
                                                                        .image_path
                                                                )
                                                            "
                                                            :alt="
                                                                album
                                                                    .portfolios[0]
                                                                    .title
                                                            "
                                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                        />
                                                    </div>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        album.portfolios
                                                            .length === 2
                                                    "
                                                >
                                                    <div
                                                        v-for="(
                                                            portfolio, idx
                                                        ) in album.portfolios"
                                                        :key="portfolio.id"
                                                        class="col-span-2 row-span-1 aspect-auto overflow-hidden h-full w-full min-h-0 min-w-0 group"
                                                    >
                                                        <img
                                                            :src="
                                                                getImageUrl(
                                                                    portfolio.image_path
                                                                )
                                                            "
                                                            :alt="
                                                                portfolio.title
                                                            "
                                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                        />
                                                    </div>
                                                </template>
                                                <template
                                                    v-else-if="
                                                        album.portfolios
                                                            .length === 3
                                                    "
                                                >
                                                    <div
                                                        v-for="(
                                                            portfolio, idx
                                                        ) in album.portfolios"
                                                        :key="portfolio.id"
                                                        :class="
                                                            (idx < 2
                                                                ? 'col-span-1 row-span-1'
                                                                : 'col-span-2 row-span-1') +
                                                            ' aspect-auto overflow-hidden h-full w-full min-h-0 min-w-0 group'
                                                        "
                                                    >
                                                        <img
                                                            :src="
                                                                getImageUrl(
                                                                    portfolio.image_path
                                                                )
                                                            "
                                                            :alt="
                                                                portfolio.title
                                                            "
                                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                        />
                                                    </div>
                                                </template>
                                                <template v-else>
                                                    <div
                                                        v-for="portfolio in album.portfolios.slice(
                                                            0,
                                                            4
                                                        )"
                                                        :key="portfolio.id"
                                                        class="col-span-1 row-span-1 aspect-square overflow-hidden h-full w-full min-h-0 min-w-0 group"
                                                    >
                                                        <img
                                                            :src="
                                                                getImageUrl(
                                                                    portfolio.image_path
                                                                )
                                                            "
                                                            :alt="
                                                                portfolio.title
                                                            "
                                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                                        />
                                                    </div>
                                                </template>
                                                <!-- Badge '+X photos' si plus de 4 images -->
                                                <div
                                                    v-if="
                                                        album.portfolios
                                                            .length > 4
                                                    "
                                                    class="absolute bottom-2 right-2 bg-pink-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-20"
                                                >
                                                    +{{
                                                        album.portfolios
                                                            .length - 4
                                                    }}
                                                    photos
                                                </div>
                                            </div>
                                            <!-- Album Title -->
                                            <h4
                                                class="absolute bottom-0 left-0 right-0 bg-black/30 backdrop-blur-sm text-white p-4 z-10 text-center"
                                            >
                                                {{ album.title }}
                                            </h4>
                                            <!-- Album Info Overlay -->
                                            <div
                                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-center items-center text-white p-4"
                                            >
                                                <p
                                                    class="text-lg font-semibold mb-2"
                                                >
                                                    {{ album.title }}
                                                </p>
                                                <p class="text-sm text-center">
                                                    {{ album.description }}
                                                </p>
                                                <p class="mt-2 text-sm">
                                                    {{
                                                        album.portfolios.length
                                                    }}
                                                    photos
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </Waterfall>
                            <!-- Pagination -->
                            <div
                                v-if="hasPagination()"
                                class="mt-8 flex justify-center"
                            >
                                <!-- Ajouter la pagination si nécessaire -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
