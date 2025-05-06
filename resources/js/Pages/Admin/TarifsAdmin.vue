<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import tarifService from "@/Services/tarifService";

// Chargement des tarifs depuis le service
const tarifs = ref([]);
const editingTarif = ref(null);
const nouveauTarif = ref({
    nom: "",
    prix: 0,
    duree: 0,
    description: "",
});

// États de chargement et d'erreur
const loading = ref(false);
const error = ref(null);

// Charger les données au démarrage
onMounted(async () => {
    await loadTarifs();
});

// Fonction pour charger les tarifs
async function loadTarifs() {
    loading.value = true;
    error.value = null;
    try {
        tarifs.value = await tarifService.getTarifs();
    } catch (err) {
        error.value = "Erreur lors du chargement des tarifs";
        console.error(err);
    } finally {
        loading.value = false;
    }
}

// Fonctions de gestion
function editTarif(tarif) {
    editingTarif.value = { ...tarif };
}

async function saveTarif() {
    if (!editingTarif.value) return;

    loading.value = true;
    error.value = null;
    try {
        await tarifService.updateTarif(editingTarif.value);
        await loadTarifs();
        editingTarif.value = null;
    } catch (err) {
        error.value = "Erreur lors de la mise à jour du tarif";
        console.error(err);
    } finally {
        loading.value = false;
    }
}

function cancelEdit() {
    editingTarif.value = null;
}

async function addTarif() {
    loading.value = true;
    error.value = null;
    try {
        await tarifService.addTarif(nouveauTarif.value);
        await loadTarifs();
        nouveauTarif.value = {
            nom: "",
            prix: 0,
            duree: 0,
            description: "",
        };
    } catch (err) {
        error.value = "Erreur lors de l'ajout du tarif";
        console.error(err);
    } finally {
        loading.value = false;
    }
}

async function deleteTarif(id) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer ce tarif ?")) return;

    loading.value = true;
    error.value = null;
    try {
        await tarifService.deleteTarif(id);
        await loadTarifs();
    } catch (err) {
        error.value = "Erreur lors de la suppression du tarif";
        console.error(err);
    } finally {
        loading.value = false;
    }
}
</script>

<template>
    <Head title="Administration des Tarifs" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <template #header>
            <h2
                class="font-semibold text-3xl text-gray-800 font-caveat leading-tight"
            >
                Administration des Tarifs
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Indicateur de chargement -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-4 mb-6"
                >
                    <div
                        class="animate-spin rounded-full h-10 w-10 border-4 border-pink-200 border-t-pink-500"
                    ></div>
                </div>

                <!-- Message d'erreur -->
                <div
                    v-if="error"
                    class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm"
                >
                    {{ error }}
                </div>

                <!-- Tableau des tarifs existants -->
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8"
                >
                    <h3
                        class="text-xl font-semibold font-caveat text-pink-500 mb-4"
                    >
                        Liste des tarifs
                    </h3>

                    <!-- Vue tablette/desktop -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-pink-100">
                            <thead class="bg-pink-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-pink-500 uppercase tracking-wider"
                                    >
                                        Nom
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-pink-500 uppercase tracking-wider"
                                    >
                                        Prix (€)
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-pink-500 uppercase tracking-wider"
                                    >
                                        Durée (min)
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-pink-500 uppercase tracking-wider"
                                    >
                                        Description
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-pink-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-pink-100">
                                <tr
                                    v-for="tarif in tarifs"
                                    :key="tarif.id"
                                    class="hover:bg-pink-50 transition-colors duration-150"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-medium">{{
                                            tarif.nom
                                        }}</span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-pink-500 font-semibold"
                                    >
                                        {{ tarif.prix }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i
                                                class="fas fa-clock text-pastel-pink mr-2"
                                            ></i>
                                            {{ tarif.duree }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-md truncate">
                                        {{ tarif.description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button
                                            @click="editTarif(tarif)"
                                            class="mr-2 px-3 py-1 bg-pink-100 text-pink-700 rounded-full hover:bg-pink-200 transition-colors"
                                        >
                                            <i class="fas fa-edit mr-1"></i>
                                            Modifier
                                        </button>
                                        <button
                                            @click="deleteTarif(tarif.id)"
                                            class="px-3 py-1 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors"
                                        >
                                            <i
                                                class="fas fa-trash-alt mr-1"
                                            ></i>
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="tarifs.length === 0 && !loading">
                                    <td
                                        colspan="5"
                                        class="px-6 py-8 text-center text-gray-500"
                                    >
                                        <div class="flex flex-col items-center">
                                            <i
                                                class="fas fa-list text-4xl text-pink-200 mb-2"
                                            ></i>
                                            Aucun tarif disponible
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Vue mobile (cartes) -->
                    <div class="md:hidden">
                        <div
                            v-if="tarifs.length === 0 && !loading"
                            class="py-8 text-center text-gray-500"
                        >
                            <div class="flex flex-col items-center">
                                <i
                                    class="fas fa-list text-4xl text-pink-200 mb-2"
                                ></i>
                                Aucun tarif disponible
                            </div>
                        </div>

                        <div v-else class="space-y-4">
                            <div
                                v-for="tarif in tarifs"
                                :key="tarif.id"
                                class="bg-white border border-pink-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200"
                            >
                                <div
                                    class="flex justify-between items-start mb-2"
                                >
                                    <h4
                                        class="font-semibold text-lg text-gray-800"
                                    >
                                        {{ tarif.nom }}
                                    </h4>
                                    <span class="text-pink-500 font-bold"
                                        >{{ tarif.prix }} €</span
                                    >
                                </div>

                                <div
                                    class="flex items-center text-gray-600 mb-2"
                                >
                                    <i
                                        class="fas fa-clock text-pastel-pink mr-2"
                                    ></i>
                                    <span>{{ tarif.duree }} min</span>
                                </div>

                                <p class="text-gray-600 text-sm mb-4">
                                    {{ tarif.description }}
                                </p>

                                <div
                                    class="flex space-x-2 pt-2 border-t border-pink-50"
                                >
                                    <button
                                        @click="editTarif(tarif)"
                                        class="flex-1 px-3 py-2 bg-pink-100 text-pink-700 rounded-full hover:bg-pink-200 transition-colors text-sm flex justify-center items-center"
                                    >
                                        <i class="fas fa-edit mr-1"></i>
                                        Modifier
                                    </button>
                                    <button
                                        @click="deleteTarif(tarif.id)"
                                        class="flex-1 px-3 py-2 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition-colors text-sm flex justify-center items-center"
                                    >
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire d'édition -->
                <div
                    v-if="editingTarif"
                    class="bg-white/90 backdrop-blur-sm shadow-xl sm:rounded-lg p-6 mb-8 border-l-4 border-pink-500"
                >
                    <div class="flex items-center mb-4">
                        <i class="fas fa-edit text-pink-500 mr-2 text-xl"></i>
                        <h3
                            class="text-xl font-semibold font-caveat text-pink-500"
                        >
                            Modifier un tarif
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Nom</label
                            >
                            <input
                                type="text"
                                v-model="editingTarif.nom"
                                class="block w-full rounded-md border-pink-200 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 transition-all"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Prix (€)</label
                            >
                            <input
                                type="number"
                                v-model="editingTarif.prix"
                                class="block w-full rounded-md border-pink-200 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 transition-all"
                                min="0"
                                step="0.01"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Durée (min)</label
                            >
                            <input
                                type="number"
                                v-model="editingTarif.duree"
                                class="block w-full rounded-md border-pink-200 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 transition-all"
                                min="0"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Description</label
                            >
                            <textarea
                                v-model="editingTarif.description"
                                rows="3"
                                class="block w-full rounded-md border-pink-200 shadow-sm focus:border-pink-500 focus:ring focus:ring-pink-200 focus:ring-opacity-50 transition-all"
                            ></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button
                            @click="cancelEdit"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors flex items-center"
                        >
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button
                            @click="saveTarif"
                            :disabled="loading"
                            class="px-4 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 disabled:opacity-50 transition-colors flex items-center"
                        >
                            <i class="fas fa-save mr-1"></i> Enregistrer
                        </button>
                    </div>
                </div>

                <!-- Formulaire d'ajout -->
                <div
                    class="bg-white/90 backdrop-blur-sm shadow-xl sm:rounded-lg p-6 border-t-4 border-pastel-yellow-btn"
                >
                    <div class="flex items-center mb-4">
                        <i
                            class="fas fa-plus-circle text-pastel-yellow-btn mr-2 text-xl"
                        ></i>
                        <h3
                            class="text-xl font-semibold font-caveat text-gray-800"
                        >
                            Ajouter un nouveau tarif
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Nom</label
                            >
                            <input
                                type="text"
                                v-model="nouveauTarif.nom"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pastel-yellow-btn focus:ring focus:ring-yellow-100 focus:ring-opacity-50 transition-all"
                                placeholder="Ex: Manucure simple"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Prix (€)</label
                            >
                            <input
                                type="number"
                                v-model="nouveauTarif.prix"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pastel-yellow-btn focus:ring focus:ring-yellow-100 focus:ring-opacity-50 transition-all"
                                min="0"
                                step="0.01"
                                placeholder="Ex: 25"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Durée (min)</label
                            >
                            <input
                                type="number"
                                v-model="nouveauTarif.duree"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pastel-yellow-btn focus:ring focus:ring-yellow-100 focus:ring-opacity-50 transition-all"
                                min="0"
                                placeholder="Ex: 30"
                            />
                        </div>
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 mb-1"
                                >Description</label
                            >
                            <textarea
                                v-model="nouveauTarif.description"
                                rows="3"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-pastel-yellow-btn focus:ring focus:ring-yellow-100 focus:ring-opacity-50 transition-all"
                                placeholder="Ex: Soin des ongles et des cuticules"
                            ></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button
                            @click="addTarif"
                            :disabled="loading"
                            class="px-4 py-2 bg-pastel-yellow-btn text-white rounded-full hover:bg-pastel-yellow hover:text-gray-800 disabled:opacity-50 transition-all flex items-center shadow-sm"
                        >
                            <i class="fas fa-plus mr-1"></i> Ajouter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
