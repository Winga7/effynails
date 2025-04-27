<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

// Définition des props pour recevoir les albums depuis le contrôleur
const props = defineProps({
    albums: {
        type: Array,
        default: () => [],
    },
    pagination: {
        type: Object,
        default: () => ({}),
    },
});

// Vérifier si des albums sont disponibles
const hasAlbums = () => {
    return props.albums && props.albums.length > 0;
};

// Vérifier si la pagination est nécessaire
const hasPagination = () => {
    return (
        props.pagination && props.pagination.total > props.pagination.per_page
    );
};
</script>

<template>
    <Head title="Portfolio" />

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
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-pink-100"
                >
                    <div class="p-6 lg:p-8">
                        <!-- Titre de la section -->
                        <h3
                            class="text-xl font-caveat text-pink-600 mb-6 text-center"
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
                            <!-- Filtres de catégories - à afficher uniquement si des albums existent -->
                            <div
                                class="flex flex-wrap justify-center gap-2 mb-8"
                            >
                                <button
                                    class="px-4 py-2 rounded-full bg-pink-100 text-pink-700 hover:bg-pink-200 transition duration-300 text-sm font-medium"
                                >
                                    Tous
                                </button>
                                <!-- Les filtres de catégories seraient générés dynamiquement en fonction des catégories disponibles -->
                            </div>

                            <!-- Grille d'albums dynamique -->
                            <div
                                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                            >
                                <!-- La boucle v-for pour les albums irait ici -->
                            </div>

                            <!-- Pagination - à afficher uniquement si nécessaire -->
                            <div
                                v-if="hasPagination()"
                                class="mt-8 flex justify-center"
                            >
                                <!-- Composant de pagination ici -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
