<script setup>
import { ref, onMounted } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Modal from "@/Components/Modal.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
    albums: Array,
    portfolios: Array,
});

const showingAlbumModal = ref(false);
const showingPortfolioModal = ref(false);
const selectedAlbum = ref(null);

const albumForm = useForm({
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
    albumForm.post(route("albums.store"), {
        preserveScroll: true,
        onSuccess: () => {
            showingAlbumModal.value = false;
            albumForm.reset();
        },
    });
};

const createPortfolio = () => {
    portfolioForm.post(route("portfolios.store"), {
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
                            class="border p-4 rounded-lg cursor-pointer"
                            :class="{
                                'bg-blue-50': selectedAlbum?.id === album.id,
                            }"
                            @click="selectAlbum(album)"
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
