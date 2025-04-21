<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Mentions Légales" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <template #header>
            <h2
                class="font-semibold text-3xl text-gray-800 font-caveat leading-tight"
            >
                Mentions Légales
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-sm rounded-lg"
                >
                    <div class="p-6 lg:p-8 space-y-6 text-gray-700">
                        <h3 class="text-xl font-semibold text-pink-500">
                            Informations légales
                        </h3>
                        <p>Le site web EFFYNAILS est édité par :</p>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>
                                <strong>Nom de l'entreprise :</strong> EFFYNAILS
                            </li>
                            <li>
                                <strong>Forme juridique :</strong> Entreprise
                                individuelle
                            </li>
                            <li>
                                <strong>Adresse :</strong> [Votre adresse
                                complète]
                            </li>
                            <li>
                                <strong>Numéro de téléphone :</strong> [Votre
                                numéro de téléphone]
                            </li>
                            <li><strong>Email :</strong> [Votre email]</li>
                            <li>
                                <strong>SIRET :</strong> [Votre numéro SIRET]
                            </li>
                        </ul>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Hébergement
                            </h3>
                            <p class="mt-2">Ce site est hébergé par :</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                <li>
                                    <strong>Nom de l'hébergeur :</strong> [Nom
                                    de l'hébergeur]
                                </li>
                                <li>
                                    <strong>Adresse :</strong> [Adresse de
                                    l'hébergeur]
                                </li>
                            </ul>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Propriété intellectuelle
                            </h3>
                            <p class="mt-2">
                                L'ensemble de ce site relève de la législation
                                française et internationale sur le droit
                                d'auteur et la propriété intellectuelle. Tous
                                les droits de reproduction sont réservés, y
                                compris pour les documents téléchargeables et
                                les représentations iconographiques et
                                photographiques.
                            </p>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Données personnelles
                            </h3>
                            <p class="mt-2">
                                Conformément à la loi Informatique et Libertés
                                du 6 janvier 1978 modifiée, vous disposez d'un
                                droit d'accès, de rectification et de
                                suppression des données vous concernant. Pour
                                exercer ce droit, vous pouvez nous contacter par
                                email à [votre email].
                            </p>
                            <p class="mt-2">
                                Pour plus de détails sur le traitement de vos
                                données, consultez notre
                                <a
                                    href="/privacy-policy"
                                    class="text-pink-500 hover:underline"
                                    >Politique de Confidentialité</a
                                >.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
