<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import ImageModal from "@/Components/ImageModal.vue";

const props = defineProps({
    album: {
        type: Object,
        required: true,
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
</script>

<template>
    <Head :title="album.title" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <template #header>
            <div class="flex items-center justify-between">
                <h2
                    class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
                >
                    {{ album.title }}
                </h2>
                <a
                    :href="route('portfolio')"
                    class="text-pink-600 hover:text-pink-800"
                >
                    Retour au portfolio
                </a>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-pink-100"
                >
                    <div class="p-6 lg:p-8">
                        <!-- Description de l'album -->
                        <p class="text-gray-600 mb-8 text-center">
                            {{ album.description }}
                        </p>

                        <!-- Grille d'images -->
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6"
                        >
                            <div
                                v-for="portfolio in album.portfolios"
                                :key="portfolio.id"
                                class="group relative overflow-hidden rounded-lg shadow-lg cursor-pointer"
                                @click="openImageModal(portfolio)"
                            >
                                <img
                                    :src="getImageUrl(portfolio.image_path)"
                                    :alt="portfolio.title"
                                    class="w-full h-64 object-cover transition-transform duration-300 group-hover:scale-105"
                                />

                                <!-- Overlay avec les informations -->
                                <div
                                    class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-center items-center text-white p-4"
                                >
                                    <h3 class="text-lg font-semibold mb-2">
                                        {{ portfolio.title }}
                                    </h3>
                                    <p class="text-sm text-center">
                                        {{ portfolio.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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
