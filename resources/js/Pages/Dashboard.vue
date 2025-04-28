<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref, onMounted } from "vue";
import { Head } from "@inertiajs/vue3";
import {
    useMockData,
    statsMock,
    derniersRendezVousMock,
    servicesPopulairesMock,
} from "@/mocks/mockDashboardData";

const stats = ref({});
const derniersRendezVous = ref([]);
const servicesPopulaires = ref([]);

onMounted(() => {
    if (useMockData) {
        stats.value = statsMock;
        derniersRendezVous.value = derniersRendezVousMock;
        servicesPopulaires.value = servicesPopulairesMock;
    } else {
        // Ici tu mets l'appel à l'API Google Calendar ou Cal.com
        // Exemple :
        // fetchDashboardDataFromAPI();
    }
});
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
                        class="bg-white/80 backdrop-blur-sm rounded-lg shadow p-6"
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
                                    Nouveaux clients
                                </p>
                                <p class="text-2xl font-semibold text-gray-900">
                                    {{ stats.clientsNouveaux }}
                                </p>
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
                    <!-- Derniers rendez-vous -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow">
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Derniers rendez-vous
                            </h3>
                            <div class="space-y-4">
                                <div
                                    v-for="rdv in derniersRendezVous"
                                    :key="rdv.id"
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                                >
                                    <div>
                                        <p class="font-medium text-gray-900">
                                            {{ rdv.client }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ rdv.service }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">
                                            {{ rdv.date }}
                                        </p>
                                        <span
                                            :class="{
                                                'px-2 py-1 text-xs rounded-full': true,
                                                'bg-green-100 text-green-800':
                                                    rdv.statut === 'confirmé',
                                                'bg-yellow-100 text-yellow-800':
                                                    rdv.statut === 'en attente',
                                            }"
                                        >
                                            {{ rdv.statut }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Services populaires -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow">
                        <div class="p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Services populaires
                            </h3>
                            <div class="space-y-4">
                                <div
                                    v-for="service in servicesPopulaires"
                                    :key="service.nom"
                                    class="flex items-center justify-between p-4 bg-gray-50 rounded-lg"
                                >
                                    <p class="font-medium text-gray-900">
                                        {{ service.nom }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ service.nombre }} rendez-vous
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
