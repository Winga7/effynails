<script setup>
/**
 * 🎨 Administration du Portfolio
 *
 * Interface complète pour gérer les albums et photos du portfolio.
 * Permet la création, modification et suppression d'albums et de photos.
 * Gestion d'upload d'images avec prévisualisation et validation.
 * Interface responsive avec modales pour les formulaires.
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires useForm
 * @requires router
 * @requires axios
 */

import { ref, onMounted } from "vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import axios from "axios";

// 📊 Props reçues du serveur
const props = defineProps({
    albums: Array, // Liste des albums existants
    portfolios: Array, // Liste des photos du portfolio
});

// 🔄 États réactifs pour les modales
const showingAlbumModal = ref(false); // Modale de création d'album
const showingPortfolioModal = ref(false); // Modale de création de photo
const showingEditAlbumModal = ref(false); // Modale d'édition d'album
const showingDeleteConfirmModal = ref(false); // Modale de confirmation de suppression
const showingEditPortfolioModal = ref(false); // Modale d'édition de photo
const showingDeletePortfolioConfirmModal = ref(false); // Modale de confirmation de suppression de photo

// 📝 États pour la gestion des données
const selectedAlbum = ref(null); // Album sélectionné pour ajouter des photos
const albumToEdit = ref(null); // Album en cours d'édition
const albumToDelete = ref(null); // Album à supprimer
const portfolioToEdit = ref(null); // Photo en cours d'édition
const portfolioToDelete = ref(null); // Photo à supprimer
const submitError = ref(""); // Message d'erreur lors de la soumission

// 📝 Formulaires réactifs avec Inertia
const albumForm = useForm({
    title: "", // Titre de l'album
    description: "", // Description de l'album
    is_featured: false, // Mise en avant de l'album
    order: 0, // Ordre d'affichage
});

const editAlbumForm = useForm({
    title: "", // Titre de l'album en édition
    description: "", // Description de l'album en édition
    is_featured: false, // Mise en avant de l'album en édition
    order: 0, // Ordre d'affichage en édition
});

const portfolioForm = useForm({
    title: "", // Titre de la photo
    description: "", // Description de la photo
    image: null, // Fichier image
    is_featured: false, // Mise en avant de la photo
    order: 0, // Ordre d'affichage
    album_id: null, // ID de l'album parent
});

const editPortfolioForm = useForm({
    title: "", // Titre de la photo en édition
    description: "", // Description de la photo en édition
    image: null, // Nouvelle image (optionnelle)
    is_featured: false, // Mise en avant de la photo en édition
    order: 0, // Ordre d'affichage en édition
});

// 📊 État réactif des données
const loading = ref(false); // État de chargement
const errorMessage = ref(null); // Message d'erreur pour l'utilisateur

/**
 * Charge la liste des albums depuis l'API
 */
const loadAlbums = async () => {
    loading.value = true;
    errorMessage.value = null;
    try {
        const response = await axios.get("/api/albums");
        albums.value = response.data;
    } catch (error) {
        // Afficher un message d'erreur à l'utilisateur
        errorMessage.value =
            "Une erreur est survenue lors du chargement des albums. Veuillez réessayer plus tard.";
        throw error;
    } finally {
        loading.value = false;
    }
};

/**
 * 📁 Fonctions de gestion des albums
 */

/**
 * ➕ Création d'un nouvel album
 *
 * @function createAlbum
 * @description Envoie les données du formulaire pour créer un nouvel album
 */
const createAlbum = () => {
    albumForm.post("/admin/albums", {
        preserveScroll: true,
        onSuccess: () => {
            showingAlbumModal.value = false;
            albumForm.reset();
        },
    });
};

/**
 * ✏️ Édition d'un album existant
 *
 * @function editAlbum
 * @param {Object} album - L'album à éditer
 * @description Prépare le formulaire d'édition avec les données de l'album
 */
const editAlbum = (album) => {
    albumToEdit.value = album;
    editAlbumForm.title = album.title;
    editAlbumForm.description = album.description;
    editAlbumForm.is_featured = album.is_featured;
    editAlbumForm.order = album.order;
    showingEditAlbumModal.value = true;
};

/**
 * 💾 Mise à jour d'un album
 *
 * @function updateAlbum
 * @description Envoie les modifications de l'album au serveur
 */
const updateAlbum = () => {
    editAlbumForm.put(`/admin/albums/${albumToEdit.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showingEditAlbumModal.value = false;
            albumToEdit.value = null;
        },
    });
};

/**
 * 🗑️ Confirmation de suppression d'un album
 *
 * @function confirmDeleteAlbum
 * @param {Object} album - L'album à supprimer
 * @description Affiche la modale de confirmation de suppression
 */
const confirmDeleteAlbum = (album) => {
    albumToDelete.value = album;
    showingDeleteConfirmModal.value = true;
};

/**
 * 🗑️ Suppression d'un album
 *
 * @function deleteAlbum
 * @description Supprime l'album sélectionné après confirmation
 */
const deleteAlbum = () => {
    if (albumToDelete.value) {
        useForm({}).delete(`/admin/albums/${albumToDelete.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showingDeleteConfirmModal.value = false;
                albumToDelete.value = null;
                if (selectedAlbum.value?.id === albumToDelete.value.id) {
                    selectedAlbum.value = null;
                }
            },
            onError: (errors) => {
                console.error("Erreur lors de la suppression:", errors);
                alert(
                    "Erreur lors de la suppression de l'album. Vérifiez la console pour plus d'informations."
                );
            },
        });
    }
};

/**
 * 📸 Fonctions de gestion des photos du portfolio
 */

/**
 * ➕ Création d'une nouvelle photo
 *
 * @function createPortfolio
 * @description Envoie les données du formulaire pour créer une nouvelle photo
 */
const createPortfolio = () => {
    portfolioForm.post(`/admin/portfolios`, {
        preserveScroll: true,
        onSuccess: () => {
            showingPortfolioModal.value = false;
            portfolioForm.reset();
            window.location.reload();
        },
        onError: (errors) => {
            console.error("Erreurs lors de la création:", errors);
            if (errors.message) {
                alert(`Erreur: ${errors.message}`);
            } else {
                alert(
                    "Erreur lors de la création. Consultez la console pour plus de détails."
                );
            }
        },
    });
};

/**
 * 🎯 Sélection d'un album pour ajouter des photos
 *
 * @function selectAlbum
 * @param {Object} album - L'album sélectionné
 * @description Met à jour l'album sélectionné et le formulaire
 */
const selectAlbum = (album) => {
    selectedAlbum.value = album;
    portfolioForm.album_id = album.id;
};

/**
 * 📤 Gestion de l'upload d'image
 *
 * @function handleImageUpload
 * @param {Event} event - L'événement de sélection de fichier
 * @description Met à jour le formulaire avec le fichier sélectionné
 */
const handleImageUpload = (event) => {
    portfolioForm.image = event.target.files[0];
};

/**
 * ✏️ Édition d'une photo existante
 *
 * @function editPortfolio
 * @param {Object} portfolio - La photo à éditer
 * @description Prépare le formulaire d'édition avec les données de la photo
 */
const editPortfolio = (portfolio) => {
    portfolioToEdit.value = portfolio;
    editPortfolioForm.reset();
    editPortfolioForm.title = portfolio.title || "Sans titre";
    editPortfolioForm.description = portfolio.description || "";
    editPortfolioForm.is_featured = portfolio.is_featured || false;
    editPortfolioForm.order = portfolio.order || 0;
    submitError.value = "";
    showingEditPortfolioModal.value = true;
};

/**
 * 💾 Soumission du formulaire d'édition
 *
 * @function submitEditForm
 * @param {Event} event - L'événement de soumission
 * @description Envoie les modifications de la photo au serveur
 */
const submitEditForm = async (event) => {
    event.preventDefault();
    submitError.value = "";

    if (!editPortfolioForm.title || editPortfolioForm.title.trim() === "") {
        submitError.value = "Le titre est obligatoire";
        return;
    }

    try {
        const formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("title", editPortfolioForm.title);

        if (editPortfolioForm.description) {
            formData.append("description", editPortfolioForm.description);
        }

        if (editPortfolioForm.image) {
            formData.append("image", editPortfolioForm.image);
        }

        const response = await axios.post(
            `/admin/portfolios/${portfolioToEdit.value.id}`,
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                    "X-Requested-With": "XMLHttpRequest",
                },
            }
        );

        showingEditPortfolioModal.value = false;
        portfolioToEdit.value = null;
        window.location.reload();
    } catch (error) {
        console.error(
            "Erreur lors de la mise à jour:",
            error.response?.data || error.message
        );
    }
};

// Confirmer la suppression d'une photo
const confirmDeletePortfolio = (portfolio) => {
    portfolioToDelete.value = portfolio;
    showingDeletePortfolioConfirmModal.value = true;
};

// Supprimer une photo
const deletePortfolio = () => {
    if (portfolioToDelete.value) {
        useForm({}).delete(`/admin/portfolios/${portfolioToDelete.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showingDeletePortfolioConfirmModal.value = false;
                portfolioToDelete.value = null;
                // 🔄 Rechargement après suppression
                window.location.reload();
            },
            onError: (errors) => {
                console.error("Erreur lors de la suppression:", errors);
                if (errors.message) {
                    alert(`Erreur: ${errors.message}`);
                } else {
                    alert(
                        "Erreur lors de la suppression. Consultez la console pour plus de détails."
                    );
                }
            },
        });
    }
};

// Gérer l'upload d'image pour édition
const handleEditImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        editPortfolioForm.image = file;
    } else {
        editPortfolioForm.image = null;
    }
};

/**
 * 🔧 Fonctions utilitaires
 */

// Debug portfolio (pour développement)
const debugPortfolio = (portfolio) => {
    // Fonction de debug sans logs pour usage futur
    const imagePath = getImageUrl(portfolio);
};

// Générer l'URL de l'image avec fallback
const getImageUrl = (portfolio) => {
    if (portfolio.image_path) {
        return `/storage/${portfolio.image_path}`;
    }

    if (portfolio.image_url) {
        return portfolio.image_url;
    }

    if (portfolio.image) {
        return `/storage/${portfolio.image}`;
    }

    return "/images/no-image.jpg";
};

// Gérer les erreurs de chargement d'image
const handleImageError = (event) => {
    // 🖼️ Remplacer par placeholder en cas d'erreur
    event.target.src = "/images/no-image.jpg";
};

/**
 * 🚀 Initialisation du composant
 */
onMounted(() => {
    // Initialisation de la page d'administration
});
</script>

<template>
    <!-- 🏷️ Métadonnées pour l'administration du portfolio -->
    <Head title="Gestion du Portfolio" />

    <!-- 🏗️ Layout principal avec dégradé pêche -->
    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <!-- 📊 En-tête de page -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion du Portfolio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- 📁 Section Albums -->
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Albums</h3>
                        <PrimaryButton @click="showingAlbumModal = true">
                            Créer un album
                        </PrimaryButton>
                    </div>

                    <!-- 🎨 Grille des albums -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <div
                            v-for="album in props.albums"
                            :key="album.id"
                            class="border p-4 rounded-lg relative"
                            :class="{
                                'bg-blue-50': selectedAlbum?.id === album.id,
                            }"
                        >
                            <!-- 🛠️ Boutons d'action pour chaque album -->
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <!-- ✏️ Bouton modifier -->
                                <button
                                    @click.stop="editAlbum(album)"
                                    class="text-blue-600 hover:text-blue-800"
                                >
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
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                        />
                                    </svg>
                                </button>
                                <!-- 🗑️ Bouton supprimer -->
                                <button
                                    @click.stop="confirmDeleteAlbum(album)"
                                    class="text-red-600 hover:text-red-800"
                                >
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
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- 📝 Contenu de l'album (clic pour sélectionner) -->
                            <div
                                @click="selectAlbum(album)"
                                class="cursor-pointer mt-4"
                            >
                                <h4 class="font-semibold">{{ album.title }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ album.description }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ album.portfolios.length }} photos
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 📸 Section Photos (affichée si un album est sélectionné) -->
                <div
                    v-if="selectedAlbum"
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">
                            Photos de l'album : {{ selectedAlbum.title }}
                        </h3>
                        <PrimaryButton @click="showingPortfolioModal = true">
                            Ajouter une photo
                        </PrimaryButton>
                    </div>

                    <!-- 🖼️ Grille des photos -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4"
                    >
                        <div
                            v-for="portfolio in selectedAlbum.portfolios"
                            :key="portfolio.id"
                            class="border p-4 rounded-lg relative"
                        >
                            <!-- 🛠️ Boutons d'action pour chaque photo -->
                            <div class="absolute top-2 right-2 flex space-x-2">
                                <!-- ✏️ Bouton modifier photo -->
                                <button
                                    @click="editPortfolio(portfolio)"
                                    class="text-blue-600 hover:text-blue-800"
                                >
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
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                        />
                                    </svg>
                                </button>
                                <!-- 🗑️ Bouton supprimer photo -->
                                <button
                                    @click="confirmDeletePortfolio(portfolio)"
                                    class="text-red-600 hover:text-red-800"
                                >
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
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- 🖼️ Image et informations -->
                            <img
                                :src="getImageUrl(portfolio)"
                                class="w-full h-48 object-cover rounded"
                                alt=""
                                @error="handleImageError"
                            />
                            <h4 class="font-semibold mt-2">
                                {{ portfolio.title }}
                            </h4>
                            <p class="text-sm text-gray-600">
                                {{ portfolio.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 📁 Modales pour la gestion des albums -->

        <!-- ➕ Modal Créer Album -->
        <Modal :show="showingAlbumModal" @close="showingAlbumModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Créer un nouvel album
                </h2>

                <form @submit.prevent="createAlbum" class="mt-6">
                    <div>
                        <InputLabel for="title" value="Titre" />
                        <TextInput
                            id="title"
                            v-model="albumForm.title"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError
                            :message="albumForm.errors.title"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="description" value="Description" />
                        <textarea
                            id="description"
                            v-model="albumForm.description"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="3"
                        />
                        <InputError
                            :message="albumForm.errors.description"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4 flex justify-end">
                        <PrimaryButton :disabled="albumForm.processing">
                            Créer l'album
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- ✏️ Modal Éditer Album -->
        <Modal
            :show="showingEditAlbumModal"
            @close="showingEditAlbumModal = false"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Modifier l'album
                </h2>

                <form @submit.prevent="updateAlbum" class="mt-6">
                    <div>
                        <InputLabel for="edit-title" value="Titre" />
                        <TextInput
                            id="edit-title"
                            v-model="editAlbumForm.title"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError
                            :message="editAlbumForm.errors.title"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="edit-description"
                            value="Description"
                        />
                        <textarea
                            id="edit-description"
                            v-model="editAlbumForm.description"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="3"
                        />
                        <InputError
                            :message="editAlbumForm.errors.description"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4 flex justify-end">
                        <PrimaryButton :disabled="editAlbumForm.processing">
                            Mettre à jour l'album
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- 🗑️ Modal Confirmer Suppression Album -->
        <Modal
            :show="showingDeleteConfirmModal"
            @close="showingDeleteConfirmModal = false"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Confirmer la suppression
                </h2>
                <p class="mt-4">
                    Êtes-vous sûr de vouloir supprimer cet album ? Cette action
                    est irréversible.
                </p>
                <div class="mt-4 flex justify-end">
                    <SecondaryButton @click="showingDeleteConfirmModal = false">
                        Annuler
                    </SecondaryButton>
                    <DangerButton @click="deleteAlbum" class="ml-2">
                        Supprimer
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <!-- 📸 Modales pour la gestion des photos -->

        <!-- ➕ Modal Ajouter Photo -->
        <Modal
            :show="showingPortfolioModal"
            @close="showingPortfolioModal = false"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Ajouter une photo
                </h2>

                <form @submit.prevent="createPortfolio" class="mt-6">
                    <div>
                        <InputLabel for="portfolio-title" value="Titre" />
                        <TextInput
                            id="portfolio-title"
                            v-model="portfolioForm.title"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError
                            :message="portfolioForm.errors.title"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="portfolio-description"
                            value="Description"
                        />
                        <textarea
                            id="portfolio-description"
                            v-model="portfolioForm.description"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="3"
                        />
                        <InputError
                            :message="portfolioForm.errors.description"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4">
                        <InputLabel for="image" value="Image" />
                        <input
                            type="file"
                            @change="handleImageUpload"
                            class="mt-1 block w-full"
                            accept="image/*"
                            required
                        />
                        <InputError
                            :message="portfolioForm.errors.image"
                            class="mt-2"
                        />
                    </div>

                    <div class="mt-4 flex justify-end">
                        <PrimaryButton :disabled="portfolioForm.processing">
                            Ajouter la photo
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- ✏️ Modal Éditer Photo -->
        <Modal
            :show="showingEditPortfolioModal"
            @close="showingEditPortfolioModal = false"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Modifier la photo
                </h2>

                <!-- 📝 Formulaire HTML natif pour gérer l'upload de fichiers -->
                <form @submit="submitEditForm" class="mt-6">
                    <div>
                        <InputLabel for="edit-portfolio-title" value="Titre" />
                        <input
                            id="edit-portfolio-title"
                            v-model="editPortfolioForm.title"
                            type="text"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            placeholder="Titre de la photo"
                            required
                        />
                        <div
                            v-if="editPortfolioForm.errors.title"
                            class="text-red-600 mt-2"
                        >
                            {{ editPortfolioForm.errors.title }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <InputLabel
                            for="edit-portfolio-description"
                            value="Description"
                        />
                        <textarea
                            id="edit-portfolio-description"
                            v-model="editPortfolioForm.description"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="3"
                        ></textarea>
                        <div
                            v-if="editPortfolioForm.errors.description"
                            class="text-red-600 mt-2"
                        >
                            {{ editPortfolioForm.errors.description }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="edit-image" value="Image" />
                        <input
                            type="file"
                            id="edit-image"
                            @change="handleEditImageUpload"
                            class="mt-1 block w-full"
                            accept="image/*"
                        />
                        <div
                            v-if="editPortfolioForm.errors.image"
                            class="text-red-600 mt-2"
                        >
                            {{ editPortfolioForm.errors.image }}
                        </div>
                    </div>

                    <div class="mt-4">
                        <!-- 🚨 Affichage des erreurs de soumission -->
                        <div class="text-red-600 mb-2" v-if="submitError">
                            {{ submitError }}
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Mettre à jour la photo
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- 🗑️ Modal Confirmer Suppression Photo -->
        <Modal
            :show="showingDeletePortfolioConfirmModal"
            @close="showingDeletePortfolioConfirmModal = false"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Confirmer la suppression
                </h2>
                <p class="mt-4">
                    Êtes-vous sûr de vouloir supprimer cette photo ? Cette
                    action est irréversible.
                </p>
                <div class="mt-4 flex justify-end">
                    <SecondaryButton
                        @click="showingDeletePortfolioConfirmModal = false"
                    >
                        Annuler
                    </SecondaryButton>
                    <DangerButton @click="deletePortfolio" class="ml-2">
                        Supprimer
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
