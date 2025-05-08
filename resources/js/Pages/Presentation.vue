<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    presentation: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Présentation" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-purple">
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
            >
                Présentation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <!-- Introduction -->
                    <div class="p-6 lg:p-8">
                        <div class="grid md:grid-cols-2 gap-8 items-center">
                            <div>
                                <div
                                    class="text-gray-600 leading-relaxed prose max-w-none"
                                    v-html="
                                        presentation.content ||
                                        'Contenu à venir...'
                                    "
                                ></div>
                            </div>
                            <div class="flex justify-end gap-4">
                                <img
                                    src="/images/Sam-Jayne.jpg"
                                    alt="Photo du salon"
                                    class="max-h-[400px] w-auto object-contain rounded-lg border-4 border-pastel-purple/30 shadow-lg"
                                />
                                <img
                                    src="/images/Fifi.jpg"
                                    alt="Photo de présentation"
                                    class="max-h-[400px] w-auto object-contain rounded-lg border-4 border-pastel-purple/30 shadow-lg"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- À propos -->
                    <div
                        class="border-t border-gray-200 p-6 lg:p-8 bg-gradient-to-r from-white to-pastel-purple/10"
                    >
                        <h3 class="text-2xl font-caveat text-gray-800 mb-4">
                            {{ presentation.about_title || "À propos" }}
                        </h3>
                        <div class="prose max-w-none">
                            <div
                                class="text-gray-600 leading-relaxed prose max-w-none"
                                v-html="
                                    presentation.about_content ||
                                    'Contenu à venir...'
                                "
                            ></div>
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
