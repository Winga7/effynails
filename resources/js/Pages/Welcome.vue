<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, computed } from "vue";
import ImageModal from "@/Components/ImageModal.vue";
import { avisMock, avisConfig } from "@/mocks/mockAvis";

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    latestPortfolio: {
        type: Array,
        default: () => [],
    },
});

// État pour le modal
const showModal = ref(false);
const selectedImage = ref({
    title: "",
    description: "",
    image_path: "",
});

// Fonction pour générer l'URL de l'image
const getImageUrl = (imagePath) => {
    return `/storage/${imagePath}`;
};

// Fonction pour ouvrir le modal avec une image
const openImageModal = (image) => {
    selectedImage.value = {
        ...image,
        image_path: getImageUrl(image.image_path),
    };
    showModal.value = true;
};

// Fonction pour fermer le modal
const closeModal = () => {
    showModal.value = false;
    selectedImage.value = {
        title: "",
        description: "",
        image_path: "",
    };
};

// Fonction pour générer des étoiles selon la note
const getStars = (note) => {
    return Array(5)
        .fill(0)
        .map((_, index) => index < note);
};

// SUPPRESSION DE TOUTE LA LOGIQUE DE CARROUSEL COMPLEXE
// Garder uniquement une gestion très simple
const currentMobileIndex = ref(0);

const nextMobileImage = () => {
    if (
        currentMobileIndex.value < Math.min(5, props.latestPortfolio.length - 1)
    ) {
        currentMobileIndex.value++;
    } else {
        currentMobileIndex.value = 0;
    }
};

const prevMobileImage = () => {
    if (currentMobileIndex.value > 0) {
        currentMobileIndex.value--;
    } else {
        currentMobileIndex.value = Math.min(
            5,
            props.latestPortfolio.length - 1
        );
    }
};

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}

// Logique pour le carrousel d'avis
const currentAvisIndex = ref(0);
// Valeurs fixes au lieu d'un objet pour éviter les erreurs .value
const AVIS_PER_PAGE_DESKTOP = 3;
const AVIS_PER_PAGE_MOBILE = 1;

// Calcul des pages pour le desktop
const desktopTotalPages = computed(() => {
    return Math.ceil(avisMock.length / AVIS_PER_PAGE_DESKTOP);
});

// Calcul des pages pour le mobile
const mobileTotalPages = computed(() => {
    return Math.ceil(avisMock.length / AVIS_PER_PAGE_MOBILE);
});

// Avis visibles sur desktop
const desktopVisibleAvis = computed(() => {
    return avisMock.slice(
        currentAvisIndex.value,
        currentAvisIndex.value + AVIS_PER_PAGE_DESKTOP
    );
});

// Fonction simplifiée pour naviguer vers les avis précédents
const previousAvis = () => {
    if (currentAvisIndex.value === 0) {
        // Si on est au début, aller à la fin
        currentAvisIndex.value = Math.max(
            0,
            avisMock.length - AVIS_PER_PAGE_DESKTOP
        );
    } else {
        // Sinon reculer d'un élément
        currentAvisIndex.value = Math.max(0, currentAvisIndex.value - 1);
    }
};

// Fonction simplifiée pour naviguer vers les avis suivants
const nextAvis = () => {
    if (currentAvisIndex.value + AVIS_PER_PAGE_DESKTOP >= avisMock.length) {
        // Si on est à la fin, revenir au début
        currentAvisIndex.value = 0;
    } else {
        // Sinon avancer d'un élément
        currentAvisIndex.value++;
    }
};
</script>

<template>
    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <Head
            title="Manucure Wavre - EFFYNAILS | Salon de Nail Art et Pose d'Ongles en Gel"
        >
            <meta
                name="description"
                content="Salon de manucure à Wavre par Steffi Ledoux. Découvrez nos services de nail art et pose d'ongles en gel. Expert en beauté des ongles à Wavre."
            />
            <meta
                name="keywords"
                content="Manucure Wavre, Nail art Wavre, Pose d'ongles en gel Wavre, Steffi Ledoux, Salon manucure Wavre"
            />
        </Head>

        <!-- Section Héro -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 items-center"
            >
                <!-- Image à gauche avec effet d'ombrage moderne -->
                <div class="relative">
                    <!-- Container principal de l'image -->
                    <div
                        class="relative rounded-3xl overflow-hidden z-20"
                        style="
                            box-shadow: 0 10px 25px -5px rgba(240, 193, 225, 0.6),
                                0 8px 10px -6px rgba(240, 193, 225, 0.4);
                        "
                    >
                        <img
                            src="/images/hero-nails.webp"
                            alt="Manucure EFFYNAILS"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Effet décoratif rose clair derrière l'image - Plus visible maintenant -->
                    <div
                        class="absolute -top-5 -left-5 w-[95%] h-[95%] bg-gradient-to-br from-pastel-pink to-white rounded-3xl z-10"
                    ></div>
                </div>

                <!-- Contenu à droite -->
                <div class="space-y-7">
                    <h1 class="text-5xl font-caveat">
                        EFFYNAILS - Manucure Wavre
                    </h1>
                    <div class="space-y-4 text-gray-600 text-justif">
                        <p>
                            Bienvenue dans mon salon de manucure à Wavre, votre
                            destination pour une beauté des ongles
                            exceptionnelle !
                        </p>
                        <p>
                            Je suis Steffi Ledoux, prothésiste ongulaire
                            passionnée et fraîchement diplômée, spécialisée en
                            nail art et pose d'ongles en gel à Wavre. Je suis
                            prête à sublimer vos mains avec des poses d'ongles
                            soignées et personnalisées.
                        </p>
                        <p>
                            Découvrez mes services de manucure et n'hésitez pas
                            à me contacter pour prendre rendez-vous dans mon
                            salon de Wavre !
                        </p>
                    </div>
                    <Link
                        :href="route('reservation')"
                        class="inline-block bg-pastel-yellow-btn hover:bg-pastel-yellow px-8 py-3 rounded-full text-sm font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1"
                    >
                        Réserver
                    </Link>
                </div>
            </div>
        </div>

        <!-- Section Portfolio -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-caveat text-pink-600 mb-8">
                        Mes dernières réalisations
                    </h2>
                </div>

                <!-- Mosaïque des dernières photos -->
                <!-- Desktop: 3 colonnes -->
                <div class="hidden md:grid md:grid-cols-3 gap-4">
                    <!-- Première colonne -->
                    <div class="flex flex-col gap-4">
                        <!-- Image 1 -->
                        <div
                            v-if="latestPortfolio.length > 0"
                            class="h-48 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[0])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[0].image_path)
                                "
                                :alt="latestPortfolio[0].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[0].title
                                }}</span>
                            </div>
                        </div>
                        <!-- Image 2 -->
                        <div
                            v-if="latestPortfolio.length > 1"
                            class="h-85 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[1])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[1].image_path)
                                "
                                :alt="latestPortfolio[1].title"
                                loading="lazy"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[1].title
                                }}</span>
                            </div>
                        </div>
                        <!-- Image 3 -->
                        <div
                            v-if="latestPortfolio.length > 2"
                            class="h-64 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[2])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[2].image_path)
                                "
                                :alt="latestPortfolio[2].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[2].title
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Deuxième colonne -->
                    <div class="flex flex-col gap-4">
                        <!-- Image 4 -->
                        <div
                            v-if="latestPortfolio.length > 3"
                            class="h-85 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[3])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[3].image_path)
                                "
                                :alt="latestPortfolio[3].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[3].title
                                }}</span>
                            </div>
                        </div>
                        <!-- Image 5 -->
                        <div
                            v-if="latestPortfolio.length > 4"
                            class="h-64 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[4])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[4].image_path)
                                "
                                :alt="latestPortfolio[4].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[4].title
                                }}</span>
                            </div>
                        </div>
                        <!-- Image 6 -->
                        <div
                            v-if="latestPortfolio.length > 5"
                            class="h-48 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[5])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[5].image_path)
                                "
                                :alt="latestPortfolio[5].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[5].title
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Troisième colonne -->
                    <div class="flex flex-col gap-4">
                        <!-- Image 7 -->
                        <div
                            v-if="latestPortfolio.length > 6"
                            class="h-48 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[6])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[6].image_path)
                                "
                                :alt="latestPortfolio[6].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[6].title
                                }}</span>
                            </div>
                        </div>
                        <!-- Image 8 -->
                        <div
                            v-if="latestPortfolio.length > 7"
                            class="h-64 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[7])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[7].image_path)
                                "
                                :alt="latestPortfolio[7].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[7].title
                                }}</span>
                            </div>
                        </div>
                        <!-- Image 9 -->
                        <div
                            v-if="latestPortfolio.length > 8"
                            class="h-85 relative group cursor-pointer"
                            @click="openImageModal(latestPortfolio[8])"
                        >
                            <img
                                :src="
                                    getImageUrl(latestPortfolio[8].image_path)
                                "
                                :alt="latestPortfolio[8].title"
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[8].title
                                }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile: Simple carrousel d'une image à la fois -->
                <div class="md:hidden relative">
                    <!-- Flèche gauche -->
                    <button
                        v-if="props.latestPortfolio.length > 1"
                        @click="prevMobileImage"
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 -ml-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors duration-300"
                        aria-label="Image précédente"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-pink-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>

                    <!-- Image courante -->
                    <div
                        class="px-8 mb-4"
                        v-if="props.latestPortfolio.length > 0"
                    >
                        <div
                            class="h-64 relative group cursor-pointer"
                            @click="
                                openImageModal(
                                    props.latestPortfolio[currentMobileIndex]
                                )
                            "
                        >
                            <img
                                :src="
                                    getImageUrl(
                                        props.latestPortfolio[
                                            currentMobileIndex
                                        ].image_path
                                    )
                                "
                                :alt="
                                    props.latestPortfolio[currentMobileIndex]
                                        .title
                                "
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white rounded-md transform scale-95"
                            >
                                <span class="text-sm">{{
                                    props.latestPortfolio[currentMobileIndex]
                                        .title
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Flèche droite -->
                    <button
                        v-if="props.latestPortfolio.length > 1"
                        @click="nextMobileImage"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 -mr-4 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors duration-300"
                        aria-label="Image suivante"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-pink-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>

                    <!-- Indicateurs de pagination (points) -->
                    <div
                        class="flex justify-center mt-2 space-x-2"
                        v-if="props.latestPortfolio.length > 1"
                    >
                        <button
                            v-for="(_, index) in Math.min(
                                6,
                                props.latestPortfolio.length
                            )"
                            :key="index"
                            @click="currentMobileIndex = index"
                            :class="[
                                'w-2.5 h-2.5 rounded-full transition-all duration-300',
                                currentMobileIndex === index
                                    ? 'bg-pink-600 scale-110'
                                    : 'bg-gray-300 hover:bg-gray-400',
                            ]"
                            :aria-label="`Image ${index + 1}`"
                        ></button>
                    </div>
                </div>

                <!-- Lien vers le portfolio complet -->
                <div class="text-center mt-8">
                    <a
                        :href="route('portfolio')"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700"
                    >
                        Voir tout le portfolio
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Avis Clients -->
        <div
            v-if="avisConfig.enabled"
            class="py-12 bg-gradient-to-br from-white to-pastel-pink"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-caveat text-pink-600">
                        Ce que disent mes clientes
                    </h2>
                    <p class="mt-3 text-gray-600">
                        Découvrez les témoignages de mes clientes satisfaites
                    </p>
                </div>

                <!-- Carrousel d'avis clients -->
                <div class="relative">
                    <!-- Flèche gauche -->
                    <button
                        @click="previousAvis"
                        class="absolute left-0 top-1/2 transform -translate-y-1/2 -ml-4 md:-ml-6 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors duration-300"
                        aria-label="Témoignages précédents"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-pink-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>
                    </button>

                    <!-- Container du carrousel -->
                    <div class="overflow-hidden">
                        <!-- Desktop: 3 avis par ligne -->
                        <div class="hidden md:grid md:grid-cols-3 gap-6">
                            <div
                                v-for="avis in desktopVisibleAvis"
                                :key="avis.id"
                                class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                            >
                                <div class="p-6">
                                    <!-- Avatar et nom -->
                                    <div class="flex items-center mb-4">
                                        <div
                                            class="w-12 h-12 rounded-full bg-pastel-pink flex items-center justify-center overflow-hidden mr-4"
                                        >
                                            <span
                                                v-if="!avis.photo"
                                                class="text-white text-xl font-semibold"
                                                >{{ avis.nom.charAt(0) }}</span
                                            >
                                            <img
                                                v-else
                                                :src="avis.photo"
                                                :alt="avis.nom"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div>
                                            <h3
                                                class="font-semibold text-gray-800"
                                            >
                                                {{ avis.nom }}
                                            </h3>
                                            <p class="text-xs text-gray-500">
                                                {{ avis.date }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Service reçu -->
                                    <div class="mb-3">
                                        <span
                                            class="inline-block bg-pastel-peach px-3 py-1 rounded-full text-xs"
                                        >
                                            {{ avis.service }}
                                        </span>
                                    </div>

                                    <!-- Note en étoiles -->
                                    <div class="flex mb-3">
                                        <template
                                            v-for="(filled, index) in getStars(
                                                avis.note
                                            )"
                                            :key="index"
                                        >
                                            <svg
                                                v-if="filled"
                                                class="w-5 h-5 text-yellow-400"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                ></path>
                                            </svg>
                                            <svg
                                                v-else
                                                class="w-5 h-5 text-gray-300"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                ></path>
                                            </svg>
                                        </template>
                                    </div>

                                    <!-- Commentaire -->
                                    <p class="text-gray-600 italic text-sm">
                                        {{ avis.commentaire }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile: 1 avis à la fois -->
                        <div class="md:hidden grid grid-cols-1 gap-4">
                            <div
                                v-if="avisMock[currentAvisIndex]"
                                class="bg-white rounded-xl shadow-md overflow-hidden"
                            >
                                <div class="p-4">
                                    <!-- Avatar et nom -->
                                    <div class="flex items-center mb-3">
                                        <div
                                            class="w-10 h-10 rounded-full bg-pastel-pink flex items-center justify-center overflow-hidden mr-3"
                                        >
                                            <span
                                                v-if="
                                                    !avisMock[currentAvisIndex]
                                                        .photo
                                                "
                                                class="text-white text-lg font-semibold"
                                            >
                                                {{
                                                    avisMock[
                                                        currentAvisIndex
                                                    ].nom.charAt(0)
                                                }}
                                            </span>
                                            <img
                                                v-else
                                                :src="
                                                    avisMock[currentAvisIndex]
                                                        .photo
                                                "
                                                :alt="
                                                    avisMock[currentAvisIndex]
                                                        .nom
                                                "
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div>
                                            <h3
                                                class="font-semibold text-gray-800 text-sm"
                                            >
                                                {{
                                                    avisMock[currentAvisIndex]
                                                        .nom
                                                }}
                                            </h3>
                                            <p class="text-xs text-gray-500">
                                                {{
                                                    avisMock[currentAvisIndex]
                                                        .date
                                                }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Service reçu -->
                                    <div class="mb-2">
                                        <span
                                            class="inline-block bg-pastel-peach px-2 py-0.5 rounded-full text-xs"
                                        >
                                            {{
                                                avisMock[currentAvisIndex]
                                                    .service
                                            }}
                                        </span>
                                    </div>

                                    <!-- Note en étoiles -->
                                    <div class="flex mb-2">
                                        <template
                                            v-for="(filled, index) in getStars(
                                                avisMock[currentAvisIndex].note
                                            )"
                                            :key="index"
                                        >
                                            <svg
                                                v-if="filled"
                                                class="w-4 h-4 text-yellow-400"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                ></path>
                                            </svg>
                                            <svg
                                                v-else
                                                class="w-4 h-4 text-gray-300"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                ></path>
                                            </svg>
                                        </template>
                                    </div>

                                    <!-- Commentaire -->
                                    <p class="text-gray-600 italic text-xs">
                                        {{
                                            avisMock[currentAvisIndex]
                                                .commentaire
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Flèche droite -->
                    <button
                        @click="nextAvis"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 -mr-4 md:-mr-6 z-10 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors duration-300"
                        aria-label="Témoignages suivants"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="h-6 w-6 text-pink-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            />
                        </svg>
                    </button>

                    <!-- Indicateurs de pagination (points) -->
                    <div class="flex justify-center mt-6 space-x-2">
                        <!-- Points de pagination pour mobile (tous les avis) -->
                        <div class="md:hidden flex space-x-2">
                            <template
                                v-for="(_, index) in mobileTotalPages"
                                :key="`mobile-${index}`"
                            >
                                <button
                                    @click="currentAvisIndex = index"
                                    :class="[
                                        'w-2.5 h-2.5 rounded-full transition-all duration-300',
                                        currentAvisIndex === index
                                            ? 'bg-pink-600 scale-110'
                                            : 'bg-gray-300 hover:bg-gray-400',
                                    ]"
                                    :aria-label="`Page d'avis mobile ${
                                        index + 1
                                    }`"
                                ></button>
                            </template>
                        </div>

                        <!-- Points de pagination pour desktop (groupés par 3) -->
                        <div class="hidden md:flex space-x-2">
                            <template
                                v-for="(_, index) in desktopTotalPages"
                                :key="`desktop-${index}`"
                            >
                                <button
                                    @click="
                                        currentAvisIndex =
                                            index * AVIS_PER_PAGE_DESKTOP
                                    "
                                    :class="[
                                        'w-2.5 h-2.5 rounded-full transition-all duration-300',
                                        Math.floor(
                                            currentAvisIndex /
                                                AVIS_PER_PAGE_DESKTOP
                                        ) === index
                                            ? 'bg-pink-600 scale-110'
                                            : 'bg-gray-300 hover:bg-gray-400',
                                    ]"
                                    :aria-label="`Page d'avis desktop ${
                                        index + 1
                                    }`"
                                ></button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Bouton pour laisser un avis -->
                <div class="mt-10 text-center">
                    <button
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-pink-600 hover:bg-pink-700 transition-all duration-300 shadow-md hover:shadow-lg"
                    >
                        <span class="mr-2">Laisser un avis</span>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-5 h-5"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal pour l'image en grand -->
        <ImageModal
            :show="showModal"
            :image="selectedImage"
            @close="closeModal"
        />
    </AppLayout>
</template>

<style>
/* Ajoutez ces styles à la fin pour renforcer l'effet visuel */
.pastel-shadow {
    box-shadow: 0 10px 25px -5px rgba(240, 193, 225, 0.6),
        0 8px 10px -6px rgba(240, 193, 225, 0.4);
}
</style>
