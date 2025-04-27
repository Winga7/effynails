<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
    latestPortfolio: Array, // Nouvelles images du portfolio
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <AppLayout class="bg-gradient-to-br from-white to-pastel-peach">
        <Head title="Accueil - EFFYNAILS" />

        <!-- Section Héro -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-screen">
            <div
                class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 items-center"
            >
                <!-- Image à gauche avec effet d'ombrage moderne -->
                <div class="relative">
                    <!-- Container principal de l'image -->
                    <div
                        class="relative rounded-3xl overflow-hidden z-20"
                        style="
                            box-shadow: 0 10px 25px -5px rgba(240, 193, 225, 0.6),
                                0 8px 10px -6px rgba(240, 193, 225, 0.4);
                        "
                    >
                        <img
                            src="/images/hero-nails.jpg"
                            alt="Manucure EFFYNAILS"
                            class="w-full h-full object-cover"
                        />
                    </div>

                    <!-- Effet décoratif rose clair derrière l'image - Plus visible maintenant -->
                    <div
                        class="absolute -top-5 -left-5 w-[95%] h-[95%] bg-gradient-to-br from-pastel-pink to-white rounded-3xl z-10"
                    ></div>
                </div>

                <!-- Contenu à droite -->
                <div class="space-y-7">
                    <h1 class="text-5xl font-caveat">EFFYNAILS</h1>
                    <div class="space-y-4 text-gray-600 text-justif">
                        <p>
                            Bienvenue dans mon univers dédié à la beauté des
                            ongles !
                        </p>
                        <p>
                            Je suis une prothésiste ongulaire passionnée et
                            fraîchement diplômée, prête à sublimer vos mains
                            avec des poses d'ongles soignées et personnalisées.
                        </p>
                        <p>
                            Découvrez mes services et n'hésitez pas à me
                            contacter pour prendre rendez-vous!
                        </p>
                    </div>
                    <Link
                        :href="route('reservation')"
                        class="inline-block bg-pastel-yellow-btn hover:bg-pastel-yellow px-8 py-3 rounded-full text-sm font-medium transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1"
                    >
                        Réserver
                    </Link>
                </div>
            </div>
        </div>

        <!-- Section Portfolio -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-3xl font-caveat text-center mb-8">
                Mes dernières créations
            </h2>

            <!-- Grille en mosaïque -->
            <div class="grid grid-cols-3 grid-rows-3 gap-4">
                <!-- Image 1 -->
                <div class="col-span-2 row-span-2 relative">
                    <div class="relative group">
                        <img
                            :src="latestPortfolio[0]?.image_url"
                            :alt="latestPortfolio[0]?.title"
                            class="w-full h-full object-cover rounded-lg shadow-md"
                        />
                        <div
                            class="absolute inset-0 flex items-center justify-center text-white text-opacity-50 font-bold text-2xl pointer-events-none"
                        >
                            EFFYNAILS
                        </div>
                    </div>
                </div>

                <!-- Images 2 à 9 -->
                <div
                    v-for="(image, index) in latestPortfolio.slice(1, 9)"
                    :key="index"
                    class="relative group col-span-1 row-span-1"
                >
                    <img
                        :src="image.image_url"
                        :alt="image.title"
                        class="w-full h-full object-cover rounded-lg shadow-md"
                    />
                    <div
                        class="absolute inset-0 flex items-center justify-center text-white text-opacity-50 font-bold text-2xl pointer-events-none"
                    >
                        EFFYNAILS
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
/* Ajoutez ces styles à la fin pour renforcer l'effet visuel */
.pastel-shadow {
    box-shadow: 0 10px 25px -5px rgba(240, 193, 225, 0.6),
        0 8px 10px -6px rgba(240, 193, 225, 0.4);
}
</style>
