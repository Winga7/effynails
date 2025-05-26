<script setup>
/**
 * 📊 Tableau de Bord
 *
 * Interface d'administration principale affichant les statistiques
 * et les informations importantes pour la gestion du salon.
 * Inclut les rendez-vous, les revenus et les services populaires.
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires axios
 */

import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted, watch } from "vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import {
    useMockData,
    statsMock,
    derniersRendezVousMock,
    servicesPopulairesMock,
} from "@/mocks/mockDashboardData";

// 📊 État réactif des données du tableau de bord
const stats = ref({}); // Statistiques générales
const derniersRendezVous = ref([]); // Liste des derniers rendez-vous
const servicesPopulaires = ref([]); // Services les plus demandés
const prochainRendezVous = ref(null); // Prochain rendez-vous à venir
const showModal = ref(false); // État de la modale principale
const showDernierRdvModal = ref(false); // État de la modale des derniers RDV
const selectedDernierRdv = ref(null); // RDV sélectionné pour la modale
const prochainsRendezVous = ref([]); // Liste des prochains rendez-vous
const dashboardData = ref({}); // Pour stocker les données du tableau de bord
const errorMessage = ref(""); // Pour stocker les messages d'erreur
const selectedPeriod = ref("3"); // Période sélectionnée en mois
const periodOptions = [
    { value: "3", label: "3 derniers mois" },
    { value: "6", label: "6 derniers mois" },
    { value: "12", label: "12 derniers mois" },
];

/**
 * 📥 Récupération des données du tableau de bord
 *
 * @function fetchDashboardData
 * @description Charge les données depuis l'API du tableau de bord
 * @returns {Promise<void>}
 */
const fetchDashboardData = async () => {
    try {
        const response = await axios.get("/api/dashboard");
        dashboardData.value = response.data;
        stats.value = response.data.stats;
        prochainsRendezVous.value = response.data.prochainsRendezVous;
        servicesPopulaires.value = response.data.servicesPopulaires;
        prochainRendezVous.value = response.data.prochainRendezVous;
    } catch (error) {
        // Afficher un message d'erreur à l'utilisateur
        errorMessage.value =
            "Une erreur est survenue lors du chargement des données. Veuillez réessayer plus tard.";
        throw error;
    }
};

/**
 * 📊 Mise à jour des services populaires
 *
 * @function updateServicesPopulaires
 * @description Met à jour les services populaires en fonction de la période sélectionnée
 */
const updateServicesPopulaires = async () => {
    try {
        const response = await axios.get(
            `/api/services-populaires?period=${selectedPeriod.value}`
        );
        servicesPopulaires.value = response.data;
    } catch (error) {
        errorMessage.value =
            "Erreur lors de la récupération des services populaires";
        console.error(error);
    }
};

/**
 * 🚀 Initialisation du composant
 *
 * @function onMounted
 * @description Charge les données au montage du composant
 */
onMounted(() => {
    if (useMockData) {
        stats.value = statsMock;
        derniersRendezVous.value = derniersRendezVousMock;
        servicesPopulaires.value = servicesPopulairesMock;
    } else {
        selectedPeriod.value = "3";
        fetchDashboardData();
        updateServicesPopulaires();
    }
});

// Ajouter un watcher sur selectedPeriod
watch(selectedPeriod, () => {
    updateServicesPopulaires();
});

// AJOUTE ceci pour forcer le rafraîchissement à chaque navigation Inertia
if (typeof router !== "undefined") {
    router.on("navigate", () => {
        updateServicesPopulaires();
    });
}

/**
 * 🎯 Gestion des modales
 *
 * @function openModal
 * @description Ouvre la modale principale
 */
const openModal = () => {
    showModal.value = true;
};

/**
 * 🎯 Fermeture de la modale principale
 *
 * @function closeModal
 * @description Ferme la modale principale
 */
const closeModal = () => {
    showModal.value = false;
};

/**
 * 🎯 Ouverture de la modale des derniers RDV
 *
 * @function openDernierRdvModal
 * @param {Object} rdv - Le rendez-vous sélectionné
 */
const openDernierRdvModal = (rdv) => {
    selectedDernierRdv.value = rdv;
    showDernierRdvModal.value = true;
};

/**
 * 🎯 Fermeture de la modale des derniers RDV
 *
 * @function closeDernierRdvModal
 * @description Ferme la modale et réinitialise la sélection
 */
const closeDernierRdvModal = () => {
    showDernierRdvModal.value = false;
    selectedDernierRdv.value = null;
};
</script>

<template>
    <Head title="Tableau de bord" />

    <AppLayout
        title="Tableau de bord"
        class="bg-gradient-to-br from-white to-pastel-pink"
    >
        <template #header>
            <h2
                class="font-semibold text-3xl text-gray-800 font-caveat leading-tight"
            >
                Tableau de bord
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Statistiques -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-full bg-pink-100 text-pink-600"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">
                                    Rendez-vous aujourd'hui
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats.rendezVousAujourdhui }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-full bg-purple-100 text-purple-600"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">
                                    Rendez-vous cette semaine
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats.rendezVousSemaine }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6 transition cursor-pointer hover:shadow-lg hover:bg-yellow-50"
                        @click="prochainRendezVous && openModal()"
                    >
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-full bg-yellow-100 text-yellow-600"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">
                                    Prochain rendez-vous à venir
                                </p>
                                <div v-if="prochainRendezVous">
                                    <p
                                        class="text-base text-gray-900 font-semibold"
                                    >
                                        {{ prochainRendezVous.type }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Client :
                                        <span
                                            class="font-medium text-gray-800"
                                            >{{
                                                prochainRendezVous.client
                                            }}</span
                                        >
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Date :
                                        <span
                                            class="font-medium text-gray-800"
                                            >{{ prochainRendezVous.date }}</span
                                        >
                                    </p>
                                </div>
                                <div v-else>
                                    <p class="text-gray-400 text-sm">
                                        Aucun rendez-vous à venir
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6"
                    >
                        <div class="flex items-center">
                            <div
                                class="p-3 rounded-full bg-green-100 text-green-600"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">
                                    Revenus du mois
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats.revenusMois }}€
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Derniers rendez-vous et Services populaires -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Prochains rendez-vous -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow">
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Prochains rendez-vous
                            </h3>
                            <div class="space-y-4">
                                <div
                                    v-for="rdv in prochainsRendezVous"
                                    :key="rdv.id"
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg cursor-pointer hover:bg-pink-100 transition"
                                    @click="openDernierRdvModal(rdv)"
                                >
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ rdv.type }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            Client :
                                            <span
                                                class="font-medium text-gray-800"
                                                >{{ rdv.client }}</span
                                            >
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">
                                            {{ rdv.date }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services populaires -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Services populaires
                                </h3>
                                <select
                                    v-model="selectedPeriod"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                >
                                    <option
                                        v-for="option in periodOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                            </div>
                            <div class="space-y-6">
                                <div
                                    v-for="service in servicesPopulaires"
                                    :key="service.nom"
                                    class="bg-gray-50 rounded-lg p-4"
                                >
                                    <div
                                        class="flex justify-between items-center mb-2"
                                    >
                                        <h4 class="font-medium text-gray-900">
                                            {{ service.nom }}
                                        </h4>
                                        <span
                                            class="text-sm font-semibold text-pink-600"
                                        >
                                            Total:
                                            {{ service.total }} rendez-vous
                                        </span>
                                    </div>
                                    <div class="space-y-2">
                                        <div
                                            v-for="(
                                                count, month
                                            ) in service.historique"
                                            :key="month"
                                            class="flex justify-between text-sm"
                                        >
                                            <span class="text-gray-600">{{
                                                month
                                            }}</span>
                                            <span
                                                class="text-gray-800 font-medium"
                                                >{{ count }} rendez-vous</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modale détails rendez-vous -->
                <div
                    v-if="showModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
                    @click.self="closeModal"
                >
                    <div
                        class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6 relative animate-fade-in"
                    >
                        <button
                            @click="closeModal"
                            class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl"
                        >
                            &times;
                        </button>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Détails du rendez-vous
                        </h3>
                        <div v-if="prochainRendezVous">
                            <p>
                                <span class="font-semibold">Type :</span>
                                {{ prochainRendezVous.type }}
                            </p>
                            <p>
                                <span class="font-semibold">Client :</span>
                                {{ prochainRendezVous.client }}
                            </p>
                            <p v-if="prochainRendezVous.email">
                                <span class="font-semibold">Email :</span>
                                <a
                                    :href="`mailto:${prochainRendezVous.email}`"
                                    class="text-blue-600 underline hover:text-blue-800"
                                    >{{ prochainRendezVous.email }}</a
                                >
                            </p>
                            <p v-if="prochainRendezVous.telephone">
                                <span class="font-semibold">Téléphone :</span>
                                <a
                                    :href="`tel:${prochainRendezVous.telephone.replace(
                                        /\s+/g,
                                        ''
                                    )}`"
                                    class="text-blue-600 underline hover:text-blue-800"
                                    >{{ prochainRendezVous.telephone }}</a
                                >
                            </p>
                            <p>
                                <span class="font-semibold">Date :</span>
                                {{ prochainRendezVous.date }}
                            </p>
                            <p v-if="prochainRendezVous.message" class="mt-2">
                                <span class="font-semibold"
                                    >Message du client :</span
                                ><br /><span class="italic">{{
                                    prochainRendezVous.message
                                }}</span>
                            </p>
                            <p v-else class="text-gray-400 mt-2">
                                Aucun message laissé par le client.
                            </p>
                            <div
                                v-if="prochainRendezVous.annulation_url"
                                class="mt-4"
                            >
                                <a
                                    :href="prochainRendezVous.annulation_url"
                                    target="_blank"
                                    class="inline-block bg-red-100 text-red-700 px-4 py-2 rounded hover:bg-red-200 font-semibold transition"
                                    >Annuler ou replanifiez le rendez-vous</a
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modale détails dernier rendez-vous -->
                <div
                    v-if="showDernierRdvModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40"
                    @click.self="closeDernierRdvModal"
                >
                    <div
                        class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6 relative animate-fade-in"
                    >
                        <button
                            @click="closeDernierRdvModal"
                            class="absolute top-2 right-2 text-gray-400 hover:text-gray-700 text-2xl"
                        >
                            &times;
                        </button>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Détails du rendez-vous
                        </h3>
                        <div v-if="selectedDernierRdv">
                            <p>
                                <span class="font-semibold">Type :</span>
                                {{ selectedDernierRdv.type }}
                            </p>
                            <p>
                                <span class="font-semibold">Client :</span>
                                {{ selectedDernierRdv.client }}
                            </p>
                            <p v-if="selectedDernierRdv.email">
                                <span class="font-semibold">Email :</span>
                                <a
                                    :href="`mailto:${selectedDernierRdv.email}`"
                                    class="text-blue-600 underline hover:text-blue-800"
                                    >{{ selectedDernierRdv.email }}</a
                                >
                            </p>
                            <p v-if="selectedDernierRdv.telephone">
                                <span class="font-semibold">Téléphone :</span>
                                <a
                                    :href="`tel:${selectedDernierRdv.telephone.replace(
                                        /\s+/g,
                                        ''
                                    )}`"
                                    class="text-blue-600 underline hover:text-blue-800"
                                    >{{ selectedDernierRdv.telephone }}</a
                                >
                            </p>
                            <p>
                                <span class="font-semibold">Date :</span>
                                {{ selectedDernierRdv.date }}
                            </p>
                            <p v-if="selectedDernierRdv.message" class="mt-2">
                                <span class="font-semibold"
                                    >Message du client :</span
                                ><br /><span class="italic">{{
                                    selectedDernierRdv.message
                                }}</span>
                            </p>
                            <p v-else class="text-gray-400 mt-2">
                                Aucun message laissé par le client.
                            </p>
                            <div
                                v-if="selectedDernierRdv.annulation_url"
                                class="mt-4"
                            >
                                <a
                                    :href="selectedDernierRdv.annulation_url"
                                    target="_blank"
                                    class="inline-block bg-red-100 text-red-700 px-4 py-2 rounded hover:bg-red-200 font-semibold transition"
                                    >Annuler ou replanifiez le rendez-vous</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
