<script setup>
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

const props = defineProps({
    albums: Array,
    portfolios: Array,
});

const showingAlbumModal = ref(false);
const showingPortfolioModal = ref(false);
const showingEditAlbumModal = ref(false);
const showingDeleteConfirmModal = ref(false);
const selectedAlbum = ref(null);
const albumToEdit = ref(null);
const albumToDelete = ref(null);
const showingEditPortfolioModal = ref(false);
const showingDeletePortfolioConfirmModal = ref(false);
const portfolioToEdit = ref(null);
const portfolioToDelete = ref(null);
const submitError = ref("");

const albumForm = useForm({
    title: "",
    description: "",
    is_featured: false,
    order: 0,
});

const editAlbumForm = useForm({
    title: "",
    description: "",
    is_featured: false,
    order: 0,
});

const portfolioForm = useForm({
    title: "",
    description: "",
    image: null,
    is_featured: false,
    order: 0,
    album_id: null,
});

const editPortfolioForm = useForm({
    title: "",
    description: "",
    image: null,
    is_featured: false,
    order: 0,
});

const createAlbum = () => {
    albumForm.post("/admin/portfolioadmin/album", {
        preserveScroll: true,
        onSuccess: () => {
            showingAlbumModal.value = false;
            albumForm.reset();
        },
    });
};

const editAlbum = (album) => {
    albumToEdit.value = album;
    editAlbumForm.title = album.title;
    editAlbumForm.description = album.description;
    editAlbumForm.is_featured = album.is_featured;
    editAlbumForm.order = album.order;
    showingEditAlbumModal.value = true;
};

const updateAlbum = () => {
    editAlbumForm.put(`/admin/portfolioadmin/album/${albumToEdit.value.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            showingEditAlbumModal.value = false;
            albumToEdit.value = null;
        },
    });
};

const confirmDeleteAlbum = (album) => {
    albumToDelete.value = album;
    showingDeleteConfirmModal.value = true;
};

const deleteAlbum = () => {
    if (albumToDelete.value) {
        useForm({}).delete(
            `/admin/portfolioadmin/album/${albumToDelete.value.id}`,
            {
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
            }
        );
    }
};

const createPortfolio = () => {
    // Utilisons l'URL directe plutôt que la fonction route()
    portfolioForm.post(`/admin/portfolios`, {
        preserveScroll: true,
        onSuccess: () => {
            showingPortfolioModal.value = false;
            portfolioForm.reset();

            // Rechargement de la page
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

const selectAlbum = (album) => {
    selectedAlbum.value = album;
    portfolioForm.album_id = album.id;
};

const handleImageUpload = (event) => {
    portfolioForm.image = event.target.files[0];
};

const editPortfolio = (portfolio) => {
    portfolioToEdit.value = portfolio;

    // Réinitialiser le formulaire avant de le remplir
    editPortfolioForm.reset();

    // S'assurer que le titre est toujours défini et non vide
    editPortfolioForm.title = portfolio.title || "Sans titre";
    editPortfolioForm.description = portfolio.description || "";
    editPortfolioForm.is_featured = portfolio.is_featured || false;
    editPortfolioForm.order = portfolio.order || 0;

    // Réinitialiser l'erreur de soumission
    submitError.value = "";

    showingEditPortfolioModal.value = true;
};

const submitEditForm = async (event) => {
    event.preventDefault();
    submitError.value = "";

    // Validation côté client
    if (!editPortfolioForm.title || editPortfolioForm.title.trim() === "") {
        submitError.value = "Le titre est obligatoire";
        return;
    }

    try {
        // Création d'un FormData pour l'envoi de fichiers
        const formData = new FormData();
        formData.append("_method", "PUT");
        formData.append("title", editPortfolioForm.title);

        if (editPortfolioForm.description) {
            formData.append("description", editPortfolioForm.description);
        }

        if (editPortfolioForm.image) {
            formData.append("image", editPortfolioForm.image);
        }

        // Configuration de la requête avec axios
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

        // Recharger la page pour afficher les changements
        window.location.reload();
    } catch (error) {
        console.error(
            "Erreur lors de la mise à jour:",
            error.response?.data || error.message
        );

        if (error.response && error.response.data) {
            if (error.response.data.errors) {
                const errors = error.response.data.errors;
                if (errors.title) {
                    submitError.value = `Erreur de titre: ${errors.title[0]}`;
                } else if (errors.image) {
                    submitError.value = `Erreur d'image: ${errors.image[0]}`;
                } else {
                    submitError.value =
                        "Erreur de validation. Vérifiez les champs du formulaire.";
                }
            } else if (error.response.data.message) {
                submitError.value = error.response.data.message;
            } else {
                submitError.value =
                    "Une erreur est survenue lors de la mise à jour.";
            }
        } else {
            submitError.value =
                "Erreur de connexion. Veuillez vérifier votre connexion Internet.";
        }
    }
};

const confirmDeletePortfolio = (portfolio) => {
    portfolioToDelete.value = portfolio;
    showingDeletePortfolioConfirmModal.value = true;
};

const deletePortfolio = () => {
    if (portfolioToDelete.value) {
        // Utilisons l'URL directe plutôt que la fonction route()
        useForm({}).delete(`/admin/portfolios/${portfolioToDelete.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                showingDeletePortfolioConfirmModal.value = false;
                portfolioToDelete.value = null;

                // Rechargement de la page après suppression
                window.location.reload();
            },
            onError: (errors) => {
                console.error("Erreur lors de la suppression:", errors);
                // Affichons plus d'informations sur l'erreur
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

const handleEditImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        editPortfolioForm.image = file;
    } else {
        editPortfolioForm.image = null;
    }
};

const debugPortfolio = (portfolio) => {
    // Garder cette fonction sans logs pour référence future si nécessaire
    const imagePath = getImageUrl(portfolio);
};

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

const handleImageError = (event) => {
    // Remplacer l'image par un placeholder en cas d'erreur
    event.target.src = "/images/no-image.jpg";
};

onMounted(() => {
    // Initialiser la page - Aucun log nécessaire ici
});
</script>

<template>
    <Head title="Gestion du Portfolio" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion du Portfolio
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Section Albums -->
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6"
                >
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Albums</h3>
                        <PrimaryButton @click="showingAlbumModal = true">
                            Créer un album
                        </PrimaryButton>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                    >
                        <div
                            v-for="album in albums"
                            :key="album.id"
                            class="border p-4 rounded-lg relative"
                            :class="{
                                'bg-blue-50': selectedAlbum?.id === album.id,
                            }"
                        >
                            <div class="absolute top-2 right-2 flex space-x-2">
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

                <!-- Section Photos -->
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

                    <div
                        class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4"
                    >
                        <div
                            v-for="portfolio in selectedAlbum.portfolios"
                            :key="portfolio.id"
                            class="border p-4 rounded-lg relative"
                        >
                            <div class="absolute top-2 right-2 flex space-x-2">
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
                                <button
                                    @click="debugPortfolio(portfolio)"
                                    class="text-gray-600 hover:text-gray-800"
                                    title="Debug"
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
                                            d="M12 12.75c1.148 0 2.278.08 3.383.237 1.037.146 1.866.966 1.866 2.013 0 3.728-2.35 6.75-5.25 6.75S6.75 18.728 6.75 15c0-1.046.83-1.867 1.866-2.013A24.204 24.204 0 0 1 12 12.75zm0 0c2.883 0 5.647.508 8.207 1.44a23.91 23.91 0 0 1-1.152 6.06M12 12.75c-2.883 0-5.647.508-8.208 1.44.125 2.104.52 4.136 1.153 6.06M12 12.75a2.25 2.25 0 0 0 2.248-2.354M12 12.75a2.25 2.25 0 0 1-2.248-2.354M12 8.25c.995 0 1.971-.08 2.922-.236.403-.066.74-.358.795-.762a3.778 3.778 0 0 0-.399-2.25M12 8.25c-.995 0-1.97-.08-2.922-.236-.402-.066-.74-.358-.795-.762a3.734 3.734 0 0 1 .4-2.253M12 8.25a2.25 2.25 0 0 0-2.248 2.146M12 8.25a2.25 2.25 0 0 1 2.248 2.146M8.683 5a6.032 6.032 0 0 1-1.155-1.002c.07-.63.27-1.222.574-1.747m.581 2.749A3.75 3.75 0 0 1 15.318 5m0 0c-.427-.609-.98-1.099-1.591-1.425a5.548 5.548 0 0 0-2.777-.696c-1.024.013-1.965.302-2.799.778l.004-.001z"
                                        />
                                    </svg>
                                </button>
                            </div>
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

        <!-- Modal Album -->
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

        <!-- Modal Edit Album -->
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

        <!-- Modal Delete Confirm -->
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

        <!-- Modal Portfolio -->
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

        <!-- Modal Edit Portfolio -->
        <Modal
            :show="showingEditPortfolioModal"
            @close="showingEditPortfolioModal = false"
        >
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Modifier la photo
                </h2>

                <!-- Formulaire HTML natif pour contourner les problèmes avec Inertia -->
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

        <!-- Modal Delete Portfolio Confirm -->
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
