<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { onMounted } from "vue";

// Configuration Cal.com - à personnaliser avec votre compte
const CAL_USERNAME = "cal.com/effynailss"; // Remplacez par votre nom d'utilisateur Cal.com
const CAL_EVENT_TYPE = "manucure"; // Remplacez par le nom de votre service
const CAL_URL = "https://cal.com"; // URL de Cal.com (ou votre instance personnalisée)

onMounted(() => {
    // Cal.com Embed initialization
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

    Cal("init", "2h", { origin: "https://cal.com" });

    // Définir l'intégration inline
    Cal.ns["2h"]("inline", {
        elementOrSelector: "#my-cal-inline",
        config: {
            layout: "month_view",
            theme: "light"
        },
        calLink: "effynailss",
    });

    // Paramètres d'interface
    Cal.ns["2h"]("ui", {
        hideEventTypeDetails: false,
        layout: "month_view",
    });
});
</script>

<template>
    <Head title="Réservation" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
            >
                Réservation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-pink-100"
                >
                    <div class="p-6 lg:p-8">
                        <h3
                            class="text-xl font-caveat text-pink-600 mb-6 text-center"
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

                        <!-- Cal.com Embed -->
                        <div
                            id="my-cal-inline"
                            class="w-full h-[700px] rounded-lg overflow-hidden border border-gray-100"
                        ></div>

                        <div class="mt-8 text-sm text-gray-500 text-center">
                            <p>
                                Vous préférez réserver par téléphone ?
                                Appelez-nous directement au
                                <strong>0123 456 789</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
