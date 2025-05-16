<script setup>
/**
 * 📅 Page de Réservation
 *
 * Composant Vue pour la page de réservation en ligne
 * Intègre Cal.com pour la prise de rendez-vous
 * avec optimisation pour les appareils mobiles
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires Breadcrumbs
 */

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

// 🔧 Configuration Cal.com - à personnaliser avec votre compte
const CAL_USERNAME = "cal.com/effynailss"; // Remplacez par votre nom d'utilisateur Cal.com
const CAL_EVENT_TYPE = "manucure"; // Remplacez par le nom de votre service
const CAL_URL = "https://cal.com"; // URL de Cal.com (ou votre instance personnalisée)

// 🍞 Fil d'Ariane pour la navigation
const breadcrumbItems = [
    { label: "Accueil", route: "home" },
    { label: "Réservation", route: "reservation" },
];

/**
 * 🚀 Initialisation du composant
 * Configure l'intégration Cal.com et optimise l'affichage mobile
 *
 * @function onMounted
 * @description Initialise le composant et configure Cal.com
 */
onMounted(() => {
    // 📱 Initialisation de l'embed Cal.com
    (function (C, A, L) {
        let p = function (a, ar) {
            a.q.push(ar);
        };
        let d = C.document;
        C.Cal =
            C.Cal ||
            function () {
                let cal = C.Cal;
                let ar = arguments;
                if (!cal.loaded) {
                    cal.ns = {};
                    cal.q = cal.q || [];
                    d.head.appendChild(d.createElement("script")).src = A;
                    cal.loaded = true;
                }
                if (ar[0] === L) {
                    const api = function () {
                        p(api, arguments);
                    };
                    const namespace = ar[1];
                    api.q = api.q || [];
                    if (typeof namespace === "string") {
                        cal.ns[namespace] = cal.ns[namespace] || api;
                        p(cal.ns[namespace], ar);
                        p(cal, ["initNamespace", namespace]);
                    } else p(cal, ar);
                    return;
                }
                p(cal, ar);
            };
    })(window, "https://app.cal.com/embed/embed.js", "init");

    // 🎯 Configuration Cal.com
    Cal("init", "2h", { origin: "https://cal.com" });

    // 📅 Configuration de l'affichage inline
    Cal.ns["2h"]("inline", {
        elementOrSelector: "#my-cal-inline",
        config: {
            layout: "month_view",
            theme: "light",
        },
        calLink: "effynailss",
    });

    // 🎨 Paramètres d'interface
    Cal.ns["2h"]("ui", {
        hideEventTypeDetails: false,
        layout: "month_view",
    });

    // 📱 Optimisation mobile - Correction des problèmes d'affichage
    setTimeout(() => {
        // 🔄 Corriger les conteneurs de créneaux horaires après chargement
        const timeSlotContainers = document.querySelectorAll(
            ".cal-timeslots-container"
        );
        if (timeSlotContainers.length > 0) {
            timeSlotContainers.forEach((container) => {
                container.style.maxHeight = "60vh";
                container.style.overflowY = "auto";
                container.style.WebkitOverflowScrolling = "touch";
            });
        }

        // 👀 Observer pour les éléments dynamiques
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.addedNodes.length) {
                    const timeSlots = document.querySelectorAll(
                        ".cal-timeslots-container"
                    );
                    if (timeSlots.length > 0) {
                        timeSlots.forEach((container) => {
                            container.style.maxHeight = "60vh";
                            container.style.overflowY = "auto";
                            container.style.WebkitOverflowScrolling = "touch";
                        });
                    }
                }
            });
        });

        // 🎯 Observer le conteneur Cal.com
        const calContainer = document.getElementById("my-cal-inline");
        if (calContainer) {
            observer.observe(calContainer, { childList: true, subtree: true });
        }
    }, 1000); // ⏱️ Attendre que Cal.com soit chargé
});
</script>

<template>
    <!-- 🏷️ Métadonnées SEO pour la page -->
    <Head title="Réservation - EFFYNAILS | Manucure et Nail Art à Wavre">
        <meta
            name="description"
            content="Réservez votre séance de manucure ou nail art à Wavre. Prenez rendez-vous en ligne pour vos soins des ongles chez EFFYNAILS."
        />
        <meta
            name="keywords"
            content="Réservation manucure Wavre, Rendez-vous nail art Wavre, Prendre RDV manucure Wavre"
        />
    </Head>

    <!-- 🏗️ Layout principal avec dégradé de fond -->
    <AppLayout class="bg-gradient-to-br from-white to-pastel-purple">
        <!-- 📊 En-tête de page -->
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
            >
                Réservation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- 🍞 Fil d'Ariane -->
                <div
                    class="p-6 border-b bg-white/90 backdrop-blur-sm rounded-t-lg border border-pink-100"
                >
                    <Breadcrumbs :items="breadcrumbItems" />
                </div>

                <!-- 🎨 Conteneur principal -->
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-pink-100 border-t-0"
                >
                    <div class="p-6 lg:p-8">
                        <!-- 📝 Titre et description -->
                        <h3
                            class="text-xl font-caveat text-pink-600 text-center"
                        >
                            Prenez rendez-vous en ligne
                        </h3>

                        <p
                            class="text-gray-500 mb-8 text-center max-w-3xl mx-auto"
                        >
                            Choisissez le service souhaité et sélectionnez une
                            date et un horaire disponible dans notre calendrier
                            ci-dessous pour réserver votre séance.
                        </p>

                        <!-- 📅 Intégration Cal.com -->
                        <div
                            id="my-cal-inline"
                            class="w-full h-[700px] rounded-lg overflow-hidden border border-gray-100"
                        ></div>

                        <!-- 📞 Informations de contact alternatif -->
                        <div class="text-sm text-gray-500 text-center">
                            <p>
                                Vous préférez réserver par téléphone ?
                                Appelez-nous directement au
                                <a href="tel:+32475555555">
                                    <strong>0475 55 55 55</strong>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
/**
 * 📱 Styles pour optimiser l'affichage mobile de Cal.com
 * Ces styles corrigent les problèmes de scrolling et d'affichage
 * sur les appareils mobiles et tablettes
 */
@media (max-width: 768px) {
    /* 📦 Style pour le conteneur principal */
    #my-cal-inline {
        height: 100% !important;
        overflow: visible !important;
    }

    /* 📜 Styles pour les conteneurs de créneaux horaires */
    :deep(.Cal__Timeslots__timeslotsContainer),
    :deep(.cal-timeslots-container) {
        max-height: 60vh !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: thin !important;
    }

    /* 🔘 Style pour les boutons de créneau */
    :deep(.Cal__TimeSlot__timeSlot) {
        margin-bottom: 8px !important;
    }

    /* 📱 Assurer que les modales sont scrollables */
    :deep([role="dialog"]),
    :deep(.Cal__Dialog__dialog) {
        max-height: 90vh !important;
        overflow-y: auto !important;
    }
}
</style>
