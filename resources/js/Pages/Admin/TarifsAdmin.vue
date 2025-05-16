<script setup>
/**
 * 💰 Administration des Tarifs
 *
 * Interface d'administration pour gérer les tarifs des services.
 * Permet de créer, modifier et supprimer des tarifs avec validation.
 * Interface responsive avec vue tableau (desktop) et cartes (mobile).
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires tarifService
 */

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import tarifService from "@/Services/tarifService";
import axios from "axios";

// 📊 État réactif des données
const tarifs = ref([]); // Liste des tarifs
const editingTarif = ref(null); // Tarif en cours d'édition
const nouveauTarif = ref({
    nom: "", // Nom du service
    prix: 0, // Prix en euros
    duree: 0, // Durée en minutes
    description: "", // Description du service
});

// 📡 États de chargement et d'erreur
const loading = ref(false); // État de chargement
const error = ref(null); // Message d'erreur
const errorMessage = ref(null); // Message d'erreur pour l'utilisateur

/**
 * 🚀 Initialisation du composant
 *
 * @function onMounted
 * @description Charge les tarifs au démarrage du composant
 */
onMounted(async () => {
    await loadTarifs();
});

/**
 * 📥 Chargement des tarifs
 *
 * @function loadTarifs
 * @description Récupère la liste des tarifs depuis l'API
 * @returns {Promise<void>}
 */
const loadTarifs = async () => {
    loading.value = true;
    errorMessage.value = null;
    try {
        const response = await axios.get("/api/tarifs");
        tarifs.value = response.data;
    } catch (error) {
        // Afficher un message d'erreur à l'utilisateur
        errorMessage.value =
            "Une erreur est survenue lors du chargement des tarifs. Veuillez réessayer plus tard.";
        throw error;
    } finally {
        loading.value = false;
    }
};

/**
 * ✏️ Gestion des tarifs
 */

/**
 * ✏️ Édition d'un tarif
 *
 * @function editTarif
 * @param {Object} tarif - Le tarif à éditer
 * @description Active le mode édition pour un tarif
 */
function editTarif(tarif) {
    editingTarif.value = { ...tarif };
}

/**
 * 💾 Sauvegarde des modifications
 *
 * @function saveTarif
 * @description Envoie les modifications du tarif à l'API
 * @returns {Promise<void>}
 */
const updateTarif = async (tarif) => {
    loading.value = true;
    errorMessage.value = null;
    try {
        await axios.put(`/api/tarifs/${tarif.id}`, tarif);
        await loadTarifs();
    } catch (error) {
        // Afficher un message d'erreur à l'utilisateur
        errorMessage.value =
            "Une erreur est survenue lors de la mise à jour du tarif. Veuillez réessayer plus tard.";
        throw error;
    } finally {
        loading.value = false;
    }
};

/**
 * ❌ Annulation des modifications
 *
 * @function cancelEdit
 * @description Annule l'édition en cours
 */
function cancelEdit() {
    editingTarif.value = null;
}

/**
 * ➕ Ajout d'un nouveau tarif
 *
 * @function addTarif
 * @description Crée un nouveau tarif via l'API
 * @returns {Promise<void>}
 */
const addTarif = async (tarif) => {
    loading.value = true;
    errorMessage.value = null;
    try {
        await axios.post("/api/tarifs", tarif);
        await loadTarifs();
    } catch (error) {
        // Afficher un message d'erreur à l'utilisateur
        errorMessage.value =
            "Une erreur est survenue lors de l'ajout du tarif. Veuillez réessayer plus tard.";
        throw error;
    } finally {
        loading.value = false;
    }
};

/**
 * 🗑️ Suppression d'un tarif
 *
 * @function deleteTarif
 * @param {number} id - L'identifiant du tarif à supprimer
 * @description Supprime un tarif après confirmation
 * @returns {Promise<void>}
 */
const deleteTarif = async (tarifId) => {
    loading.value = true;
    errorMessage.value = null;
    try {
        await axios.delete(`/api/tarifs/${tarifId}`);
        await loadTarifs();
    } catch (error) {
        // Afficher un message d'erreur à l'utilisateur
        errorMessage.value =
            "Une erreur est survenue lors de la suppression du tarif. Veuillez réessayer plus tard.";
        throw error;
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <!-- 🏷️ Métadonnées pour l'administration -->
    <Head title="Administration des Tarifs" />

    <!-- 🏗️ Layout avec dégradé rose -->
    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <!-- 📊 En-tête de la page -->
        <template #header>
            <h2
                class="font-semibold text-3xl text-gray-800 font-caveat leading-tight"
            >
                Administration des Tarifs
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- ⏳ Indicateur de chargement global -->
                <div
                    v-if="loading"
                    class="flex justify-center items-center py-4 mb-6"
                >
                    <div
                        class="animate-spin rounded-full h-10 w-10 border-4 border-pink-200 border-t-pink-500"
                    ></div>
                </div>

                <!-- ❌ Message d'erreur global -->
                <div
                    v-if="error"
                    class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm"
                >
                    {{ error }}
                </div>

                <!-- 📋 Section - Liste des tarifs existants -->
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8"
                >
                    <h3
                        class="text-xl font-semibold font-caveat text-pink-500 mb-4"
                    >
                        Liste des tarifs
                    </h3>

                    <!-- 🖥️ Vue tableau pour desktop/tablette -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-pink-100">
                            <!-- 📊 En-tête du tableau -->
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

                            <!-- 📋 Corps du tableau -->
                            <tbody class="bg-white divide-y divide-pink-100">
                                <!-- 📄 Ligne pour chaque tarif -->
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
                                        <!-- ✏️ Bouton modifier -->
                                        <button
                                            @click="editTarif(tarif)"
                                            class="mr-2 px-3 py-1 bg-pink-100 text-pink-700 rounded-full hover:bg-pink-200 transition-colors"
                                        >
                                            <i class="fas fa-edit mr-1"></i>
                                            Modifier
                                        </button>
                                        <!-- 🗑️ Bouton supprimer -->
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

                                <!-- 📭 Message si aucun tarif -->
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

                    <!-- 📱 Vue cartes pour mobile -->
                    <div class="md:hidden">
                        <!-- 📭 Message si aucun tarif (mobile) -->
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

                        <!-- 🃏 Cartes pour chaque tarif -->
                        <div v-else class="space-y-4">
                            <div
                                v-for="tarif in tarifs"
                                :key="tarif.id"
                                class="bg-white border border-pink-100 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow duration-200"
                            >
                                <!-- 📝 Informations du tarif -->
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

                                <!-- ⏱️ Durée -->
                                <div
                                    class="flex items-center text-gray-600 mb-2"
                                >
                                    <i
                                        class="fas fa-clock text-pastel-pink mr-2"
                                    ></i>
                                    <span>{{ tarif.duree }} min</span>
                                </div>

                                <!-- 📝 Description -->
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ tarif.description }}
                                </p>

                                <!-- 🛠️ Actions sur mobile -->
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

                <!-- ✏️ Section - Formulaire d'édition -->
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

                    <!-- 📝 Champs du formulaire d'édition -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- 📌 Nom -->
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

                        <!-- 💰 Prix -->
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

                        <!-- ⏱️ Durée -->
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

                        <!-- 📝 Description -->
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

                    <!-- 🛠️ Actions du formulaire d'édition -->
                    <div class="mt-6 flex justify-end space-x-3">
                        <button
                            @click="cancelEdit"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors flex items-center"
                        >
                            <i class="fas fa-times mr-1"></i> Annuler
                        </button>
                        <button
                            @click="updateTarif(editingTarif)"
                            :disabled="loading"
                            class="px-4 py-2 bg-pink-500 text-white rounded-full hover:bg-pink-600 disabled:opacity-50 transition-colors flex items-center"
                        >
                            <i class="fas fa-save mr-1"></i> Enregistrer
                        </button>
                    </div>
                </div>

                <!-- ➕ Section - Formulaire d'ajout -->
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

                    <!-- 📝 Champs du formulaire d'ajout -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- 📌 Nom -->
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

                        <!-- 💰 Prix -->
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

                        <!-- ⏱️ Durée -->
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

                        <!-- 📝 Description -->
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

                    <!-- ➕ Bouton d'ajout -->
                    <div class="mt-6 flex justify-end">
                        <button
                            @click="addTarif(nouveauTarif)"
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
