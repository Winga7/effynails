<script setup>
import { Head } from "@inertiajs/vue3";
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
    <AppLayout>
        <Head title="Accueil - EFFYNAILS" />

        <!-- Section Héro -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <!-- Image à gauche -->
                <div class="relative">
                    <div
                        class="aspect-square rounded-3xl overflow-hidden shadow-xl"
                    >
                        <img
                            src="/images/hero-nails.jpg"
                            alt="Manucure EFFYNAILS"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <!-- Effet décoratif rose clair derrière l'image -->
                    <div
                        class="absolute -top-4 -left-4 w-full h-full bg-pastel-pink rounded-3xl -z-10"
                    ></div>
                </div>

                <!-- Contenu à droite -->
                <div class="space-y-6">
                    <h1 class="text-5xl font-caveat">EFFYNAILS</h1>
                    <div class="space-y-4 text-gray-600">
                        <p>
                            Lorem Ipsum Dolor Sit Amet, Aut Esse Voluptas Non
                            Ipsum Voluptatem Et Quod. Ut Quis Laudantium Aut
                            Tempore In Dolor Error Qui Harum Omnis Est Autem
                            Ipsa.
                        </p>
                        <p>
                            Eos Minima Voluptatum Est Unde Est Nihil Fugit Ut
                            Quis Sequi Et Aspernatur Consequatur. His Velit
                            Dolores Ex Modi Laboriosam Hic Harum 33 Maiores
                            Similique. Ad Error LaudantiumEt Repellat Sed
                            Delectus Amet Aut Nisi Sint Eum Dignissimos
                            Cupiditate Et Veritas Dolorem.
                        </p>
                    </div>
                    <button
                        class="bg-pastel-yellow-btn hover:bg-pastel-yellow px-6 py-3 rounded-full text-sm font-medium transition-colors duration-300"
                    >
                        Contactez-nous
                    </button>
                </div>
            </div>
        </div>

        <!-- Section Portfolio -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h2 class="text-3xl font-caveat text-center mb-8">
                Mes dernières créations
            </h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <div
                    v-for="image in latestPortfolio"
                    :key="image.id"
                    :class="{ 'col-span-2 row-span-2': image.is_featured }"
                    class="relative group overflow-hidden rounded-lg"
                >
                    <img
                        :src="image.image_url"
                        :alt="image.title"
                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                    />
                    <!-- Copyright Watermark -->
                    <div
                        class="absolute bottom-2 right-2 text-xs text-white bg-black bg-opacity-50 px-2 py-1 rounded"
                    >
                        © EFFYNAILS
                    </div>
                    <!-- Hover Overlay -->
                    <div
                        class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center"
                    >
                        <p class="text-white text-center p-4">
                            {{ image.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
