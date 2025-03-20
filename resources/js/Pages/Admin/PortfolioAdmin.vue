<script setup>
import { ref, onMounted } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

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

const createAlbum = () => {
    albumForm.post(route("admin.albums.store"), {
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
    editAlbumForm.put(route("admin.albums.update", albumToEdit.value.id), {
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
            route("admin.albums.destroy", albumToDelete.value.id),
            {
                preserveScroll: true,
                onSuccess: () => {
                    showingDeleteConfirmModal.value = false;
                    albumToDelete.value = null;
                    if (selectedAlbum.value?.id === albumToDelete.value.id) {
                        selectedAlbum.value = null;
                    }
                },
            }
        );
    }
};

const createPortfolio = () => {
    portfolioForm.post(route("admin.portfolios.store"), {
        preserveScroll: true,
        onSuccess: () => {
            showingPortfolioModal.value = false;
            portfolioForm.reset();
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
</script>

<template>
    <Head title="Gestion du Portfolio" />

    <AppLayout>
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
                            class="border p-4 rounded-lg"
                        >
                            <img
                                :src="portfolio.image_url"
                                class="w-full h-48 object-cover rounded"
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
    </AppLayout>
</template>
