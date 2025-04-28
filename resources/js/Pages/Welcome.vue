<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from "vue";
import ImageModal from "@/Components/ImageModal.vue";

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

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <Head title="Accueil - EFFYNAILS" />

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
                            src="/images/hero-nails.jpg"
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
                    <h1 class="text-5xl font-caveat">EFFYNAILS</h1>
                    <div class="space-y-4 text-gray-600 text-justif">
                        <p>
                            Bienvenue dans mon univers dédié à la beauté des
                            ongles !
                        </p>
                        <p>
                            Je suis une prothésiste ongulaire passionnée et
                            fraîchement diplômée, prête à sublimer vos mains
                            avec des poses d'ongles soignées et personnalisées.
                        </p>
                        <p>
                            Découvrez mes services et n'hésitez pas à me
                            contacter pour prendre rendez-vous!
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
                <div class="grid grid-cols-3 gap-4">
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="w-full h-full object-cover rounded-lg shadow-md transition-transform duration-300 group-hover:scale-105"
                            />
                            <div
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
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
                                class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white"
                            >
                                <span class="text-sm">{{
                                    latestPortfolio[8].title
                                }}</span>
                            </div>
                        </div>
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
