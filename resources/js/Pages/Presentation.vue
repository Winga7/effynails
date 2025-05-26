<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

defineProps({
    presentation: {
        type: Object,
        required: true,
    },
});

const breadcrumbItems = [
    { label: "Accueil", route: "home" },
    { label: "Présentation", route: "presentation" },
];
</script>

<template>
    <Head title="À propos - Steffi Ledoux | Manucure et Nail Art à Wavre">
        <meta
            name="description"
            content="Découvrez Steffi Ledoux, experte en manucure et nail art à Wavre. Salon spécialisé en pose d'ongles en gel et nail art personnalisé."
        />
        <meta
            name="keywords"
            content="Steffi Ledoux, Manucure Wavre, Nail art Wavre, Pose d'ongles en gel Wavre, Salon manucure Wavre"
        />
    </Head>

    <AppLayout class="bg-gradient-to-br from-white to-pastel-purple">
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
            >
                À propos de Steffi Ledoux - Manucure Wavre
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <!-- Breadcrumbs -->
                    <div class="p-6 border-b">
                        <Breadcrumbs :items="breadcrumbItems" />
                    </div>

                    <!-- Introduction -->
                    <div class="p-6 lg:p-8">
                        <h2
                            class="text-gray-600 leading-relaxed prose max-w-none text-2xl font-semibold mb-8 text-center"
                            v-html="
                                presentation.content || 'Contenu à venir...'
                            "
                        ></h2>
                        <div
                            class="flex flex-col md:flex-row md:items-start gap-8"
                        >
                            <!-- À propos : sous les images sur mobile, à gauche sur desktop -->
                            <div class="order-2 md:order-1 w-full">
                                <div
                                    class="bg-white/90 rounded-xl shadow-lg p-6 md:p-8 mb-6 md:mb-0"
                                >
                                    <h3
                                        class="text-2xl font-caveat text-gray-800 mb-4 text-center"
                                    >
                                        {{
                                            presentation.about_title ||
                                            "À propos"
                                        }}
                                    </h3>
                                    <div
                                        class="ql-editor text-gray-600 leading-relaxed"
                                        v-html="
                                            presentation.about_content ||
                                            'Contenu à venir...'
                                        "
                                    ></div>
                                </div>
                            </div>
                            <!-- Images : en haut sur mobile, à droite sur desktop -->
                            <div
                                class="flex flex-row justify-center gap-4 mb-4 md:mb-0 order-1 md:order-2"
                            >
                                <img
                                    src="/images/Sam-Jayne.jpg"
                                    alt="Photo du salon"
                                    class="w-32 h-32 object-cover rounded-lg border-4 border-pastel-purple/30 shadow-lg md:w-auto md:h-[350px] md:object-cover md:max-w-xs"
                                />
                                <img
                                    src="/images/Fifi.jpg"
                                    alt="Photo de Steffi Ledoux"
                                    class="w-32 h-32 object-cover rounded-lg border-4 border-pastel-purple/30 shadow-lg md:w-auto md:h-[350px] md:object-cover md:max-w-xs"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Expérience -->
                    <!-- <div class="border-t border-gray-200 p-6 lg:p-8">
                        <h3 class="text-2xl font-caveat text-gray-800 mb-4">
                            {{ presentation.experience_title || "Mon expérience" }}
                        </h3>
                        <div class="prose max-w-none">
                            <div class="text-gray-600 leading-relaxed prose max-w-none" v-html="presentation.experience_content || 'Contenu à venir...'"></div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
