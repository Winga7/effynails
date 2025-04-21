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

    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Administration des Tarifs
            </h2>
        </template>

        <div
            class="py-12 bg-gradient-to-br from-white to-[#F0C1E1] min-h-screen"
        >
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg p-6"
                >
                    <!-- Message d'erreur -->
                    <div
                        v-if="error"
                        class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded"
                    >
                        {{ error }}
                    </div>

                    <!-- Indicateur de chargement -->
                    <div
                        v-if="loading"
                        class="flex justify-center items-center py-4"
                    >
                        <div
                            class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-500"
                        ></div>
                    </div>

                    <!-- Tableau des tarifs existants -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium mb-4">
                            Liste des tarifs
                        </h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Nom
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Prix (€)
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Durée (min)
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Description
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="bg-white dark:bg-gray-800 divide-y divide-gray-200"
                            >
                                <tr
                                    v-for="tarif in tarifs"
                                    :key="tarif.id"
                                    class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                >
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ tarif.nom }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ tarif.prix }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ tarif.duree }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ tarif.description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button
                                            @click="editTarif(tarif)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2"
                                        >
                                            Modifier
                                        </button>
                                        <button
                                            @click="deleteTarif(tarif.id)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="tarifs.length === 0 && !loading">
                                    <td
                                        colspan="5"
                                        class="px-6 py-4 text-center text-gray-500"
                                    >
                                        Aucun tarif disponible
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Formulaire d'édition -->
                    <div v-if="editingTarif" class="mt-6 p-4 border rounded-lg">
                        <h3 class="text-lg font-medium mb-4">
                            Modifier un tarif
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nom</label
                                >
                                <input
                                    type="text"
                                    v-model="editingTarif.nom"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Prix (€)</label
                                >
                                <input
                                    type="number"
                                    v-model="editingTarif.prix"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Durée (min)</label
                                >
                                <input
                                    type="number"
                                    v-model="editingTarif.duree"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Description</label
                                >
                                <textarea
                                    v-model="editingTarif.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end space-x-3">
                            <button
                                @click="cancelEdit"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300"
                            >
                                Annuler
                            </button>
                            <button
                                @click="saveTarif"
                                :disabled="loading"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50"
                            >
                                Enregistrer
                            </button>
                        </div>
                    </div>

                    <!-- Formulaire d'ajout -->
                    <div class="mt-8 p-4 border rounded-lg">
                        <h3 class="text-lg font-medium mb-4">
                            Ajouter un nouveau tarif
                        </h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Nom</label
                                >
                                <input
                                    type="text"
                                    v-model="nouveauTarif.nom"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Prix (€)</label
                                >
                                <input
                                    type="number"
                                    v-model="nouveauTarif.prix"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Durée (min)</label
                                >
                                <input
                                    type="number"
                                    v-model="nouveauTarif.duree"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-700"
                                    >Description</label
                                >
                                <textarea
                                    v-model="nouveauTarif.description"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                ></textarea>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button
                                @click="addTarif"
                                :disabled="loading"
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                            >
                                Ajouter
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
