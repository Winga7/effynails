<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
</script>

<template>
    <Head title="Plan du site" />

    <AppLayout title="Plan du site">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white">
                        <h1 class="text-3xl font-bold text-pink-500 mb-8">
                            Plan du site
                        </h1>

                        <div class="mb-8">
                            <h2
                                class="text-2xl font-semibold text-gray-800 mb-4"
                            >
                                Pages principales
                            </h2>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>
                                    <Link
                                        :href="route('home')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Accueil
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        La page d'accueil du site
                                    </p>
                                </li>
                                <li>
                                    <Link
                                        :href="route('presentation')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Présentation
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Découvrez le salon et mes prestations
                                    </p>
                                </li>
                                <li>
                                    <Link
                                        :href="route('tarifs')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Tarifs
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Consultez les différents tarifs proposés
                                    </p>
                                </li>
                                <li>
                                    <Link
                                        :href="route('portfolio')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Portfolio
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Galerie des réalisations
                                    </p>
                                </li>
                                <li>
                                    <Link
                                        :href="route('contact')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Contact
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Formulaire de contact et informations
                                    </p>
                                </li>
                                <li>
                                    <Link
                                        :href="route('reservation')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Réservation
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Prendre rendez-vous en ligne
                                    </p>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h2
                                class="text-2xl font-semibold text-gray-800 mb-4"
                            >
                                Pages légales
                            </h2>
                            <ul class="list-disc pl-5 space-y-2">
                                <li>
                                    <Link
                                        :href="route('mentions-legales')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Mentions légales
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Informations légales du site
                                    </p>
                                </li>
                                <li>
                                    <Link
                                        :href="
                                            route('politique-confidentialite')
                                        "
                                        class="text-pink-500 hover:underline"
                                    >
                                        Politique de confidentialité
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Traitement des données personnelles
                                    </p>
                                </li>
                                <li>
                                    <Link
                                        :href="route('plan-du-site')"
                                        class="text-pink-500 hover:underline"
                                    >
                                        Plan du site
                                    </Link>
                                    <p class="text-sm text-gray-600 ml-2">
                                        Vue d'ensemble de la structure du site
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
