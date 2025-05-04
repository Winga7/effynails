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
                                <strong>Adresse :</strong> [À COMPLÉTER - Votre
                                adresse complète]
                            </li>
                            <li>
                                <strong>Numéro de téléphone :</strong>
                                <a
                                    href="tel:+32XXXXXXXXX"
                                    class="text-pink-500 hover:underline"
                                    >0474 XX XX XX</a
                                >
                            </li>
                            <li>
                                <strong>Email :</strong>
                                <a
                                    href="mailto:effynails2@gmail.com"
                                    class="text-pink-500 hover:underline"
                                    >effynails2@gmail.com</a
                                >
                            </li>
                            <li>
                                <strong>Numéro d'entreprise :</strong> [À
                                COMPLÉTER - Votre numéro BCE]
                            </li>
                            <li>
                                <strong>TVA :</strong> [À COMPLÉTER - Votre
                                numéro de TVA]
                            </li>
                        </ul>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Hébergement
                            </h3>
                            <p class="mt-2">Ce site est hébergé par :</p>
                            <ul class="list-disc pl-5 space-y-2 mt-2">
                                <li>
                                    <strong>Nom de l'hébergeur :</strong>
                                    O2switch
                                </li>
                                <li>
                                    <strong>Adresse :</strong> 222-224 Boulevard
                                    Gustave Flaubert, 63000 Clermont-Ferrand,
                                    France
                                </li>
                                <li>
                                    <strong>Site web :</strong>
                                    <a
                                        href="https://www.o2switch.fr"
                                        target="_blank"
                                        class="text-pink-500 hover:underline"
                                        >www.o2switch.fr</a
                                    >
                                </li>
                                <li>
                                    <strong>SIRET :</strong> 510 909 80700024
                                </li>
                            </ul>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Propriété intellectuelle
                            </h3>
                            <p class="mt-2">
                                L'ensemble de ce site relève de la législation
                                belge et internationale sur le droit d'auteur et
                                la propriété intellectuelle. Tous les droits de
                                reproduction sont réservés, y compris pour les
                                documents téléchargeables et les représentations
                                iconographiques et photographiques.
                            </p>
                            <p class="mt-2">
                                Les photos des réalisations sont publiées
                                uniquement avec l'accord des clients. Le contenu
                                textuel et les éléments graphiques du site sont
                                la propriété exclusive d'EFFYNAILS.
                            </p>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Protection des données personnelles
                            </h3>
                            <p class="mt-2">
                                Conformément au Règlement Général sur la
                                Protection des Données (RGPD) et à la loi belge
                                du 30 juillet 2018 relative à la protection des
                                personnes physiques à l'égard des traitements de
                                données à caractère personnel, vous disposez
                                d'un droit d'accès, de rectification,
                                d'effacement et de limitation du traitement des
                                données vous concernant.
                            </p>
                            <p class="mt-2">
                                Pour exercer ces droits, vous pouvez nous
                                contacter par email à
                                <a
                                    href="mailto:effynails2@gmail.com"
                                    class="text-pink-500 hover:underline"
                                    >effynails2@gmail.com</a
                                >.
                            </p>
                            <p class="mt-2">
                                Pour plus de détails sur le traitement de vos
                                données, consultez notre
                                <a
                                    href="/politique-confidentialite"
                                    class="text-pink-500 hover:underline"
                                    >Politique de Confidentialité</a
                                >.
                            </p>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Cookies
                            </h3>
                            <p class="mt-2">
                                Notre site utilise des cookies pour améliorer
                                votre expérience de navigation. Conformément à
                                la législation belge, nous vous informons que
                                vous pouvez à tout moment configurer votre
                                navigateur pour refuser l'utilisation de
                                cookies.
                            </p>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Loi applicable et juridiction compétente
                            </h3>
                            <p class="mt-2">
                                Les présentes mentions légales sont soumises à
                                la loi belge. En cas de litige, les tribunaux
                                belges seront seuls compétents.
                            </p>
                        </div>

                        <div class="pt-4">
                            <h3 class="text-xl font-semibold text-pink-500">
                                Responsabilité
                            </h3>
                            <p class="mt-2">
                                EFFYNAILS met tout en œuvre pour offrir des
                                informations à jour, exactes et complètes.
                                Malgré tous les soins apportés, le site peut
                                présenter des erreurs que nous nous engageons à
                                corriger dès qu'elles sont portées à notre
                                connaissance.
                            </p>
                            <p class="mt-2">
                                Les informations fournies sur ce site le sont à
                                titre indicatif et ne sauraient engager la
                                responsabilité d'EFFYNAILS. Notre site utilise
                                les technologies standard du web et respecte les
                                meilleures pratiques en matière de sécurité pour
                                vous offrir une expérience de navigation
                                optimale.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
