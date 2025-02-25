<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";

// Configuration Cal.com
const CAL_URL = import.meta.env.VITE_CAL_URL || "http://localhost:3000"; // À configurer dans .env

onMounted(() => {
    // Initialisation de Cal.com
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
                    typeof namespace === "string"
                        ? (cal.ns[namespace] = api) && p(api, ar)
                        : p(cal, ar);
                    return;
                }
                p(cal, ar);
            };
    })(window, `${CAL_URL}/embed/embed.js`, "init");

    // Initialisation du composant Cal
    Cal("init", {
        origin: CAL_URL,
    });

    // Intégration de l'interface de réservation
    Cal("ui", {
        theme: "light",
        styles: {
            branding: {
                brandColor: "#000000", // À personnaliser selon votre charte graphique
            },
        },
        hideEventTypeDetails: false,
    });
});
</script>

<template>
    <Head title="Réservation" />

    <AppLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Réservation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 lg:p-8">
                        <!-- Cal.com Embed -->
                        <div
                            class="w-full min-h-[600px]"
                            data-cal-embed
                            data-cal-link="votre-nom/service-manucure"
                            <!--
                            À
                            personnaliser
                            --
                        >
                            data-cal-config='{"layout":"month_view"}' >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
