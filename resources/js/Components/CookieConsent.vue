<script setup>
import { ref, onMounted } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const showBanner = ref(false);
const consentGiven = ref(false);

onMounted(() => {
    // Vérifier si le consentement est déjà stocké
    const storedConsent = localStorage.getItem("cookie-consent");
    if (!storedConsent) {
        showBanner.value = true;
    } else {
        consentGiven.value = storedConsent === "accepted";

        // Si le consentement est accepté, initialiser Google Analytics
        if (consentGiven.value) {
            initializeGoogleAnalytics();
        }
    }
});

const acceptCookies = () => {
    localStorage.setItem("cookie-consent", "accepted");
    consentGiven.value = true;
    showBanner.value = false;
    initializeGoogleAnalytics();
};

const rejectCookies = () => {
    localStorage.setItem("cookie-consent", "rejected");
    consentGiven.value = false;
    showBanner.value = false;
    // Désactiver Google Analytics si refusé
    window["ga-disable-" + import.meta.env.VITE_GOOGLE_ANALYTICS_ID] = true;
};

const initializeGoogleAnalytics = () => {
    // Cette fonction sera appelée uniquement si le consentement est donné
    if (window.gtag && import.meta.env.VITE_GOOGLE_ANALYTICS_ID) {
        window.gtag("consent", "update", {
            analytics_storage: "granted",
        });
    }
};
</script>

<template>
    <transition
        enter-active-class="transform transition duration-300 ease-out"
        enter-from-class="translate-y-full opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transform transition duration-300 ease-in"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-full opacity-0"
    >
        <div v-if="showBanner" class="fixed bottom-0 inset-x-0 z-50 p-4 md:p-6">
            <div class="max-w-7xl mx-auto">
                <div
                    class="bg-white/90 backdrop-blur-sm shadow-lg rounded-lg overflow-hidden border border-pink-200"
                >
                    <div class="p-4 md:p-6">
                        <div
                            class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4"
                        >
                            <div class="flex-1">
                                <h3
                                    class="text-lg font-semibold text-pink-600 mb-2"
                                >
                                    🍪 Utilisation des cookies
                                </h3>
                                <p class="text-gray-700 text-sm md:text-base">
                                    Ce site utilise des cookies pour analyser le
                                    trafic et améliorer votre expérience. En
                                    acceptant, vous nous aidez à comprendre
                                    comment nos visiteurs utilisent notre site.
                                </p>
                                <div class="mt-2">
                                    <a
                                        href="/politique-confidentialite"
                                        class="text-sm text-pink-500 hover:underline"
                                    >
                                        En savoir plus sur notre politique de
                                        confidentialité
                                    </a>
                                </div>
                            </div>
                            <div class="flex flex-row gap-3 mt-3 md:mt-0">
                                <SecondaryButton
                                    @click="rejectCookies"
                                    class="whitespace-nowrap"
                                >
                                    Refuser
                                </SecondaryButton>
                                <PrimaryButton
                                    @click="acceptCookies"
                                    class="whitespace-nowrap bg-pastel-yellow-btn hover:bg-pastel-yellow text-gray-800"
                                >
                                    Accepter
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
