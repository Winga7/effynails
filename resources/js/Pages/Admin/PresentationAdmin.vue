<script setup>
/**
 * 📝 Administration de la Présentation
 *
 * Interface d'administration pour gérer le contenu de la page de présentation.
 * Permet la modification du texte d'introduction et de la section "À propos"
 * avec un éditeur de texte riche (QuillEditor).
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires useForm
 * @requires QuillEditor
 */

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";

// 📊 Props reçues du serveur
const props = defineProps({
    presentation: Object, // Données de présentation existantes
});

// 📝 Formulaire réactif avec les données existantes
const form = useForm({
    content: props.presentation?.content || "", // Contenu de l'introduction
    about_title: props.presentation?.about_title || "", // Titre de la section À propos
    about_content: props.presentation?.about_content || "", // Contenu de la section À propos
    experience_title: props.presentation?.experience_title || "", // Titre de la section Expérience (futur)
    experience_content: props.presentation?.experience_content || "", // Contenu de la section Expérience (futur)
});

/**
 * 💾 Soumission du formulaire
 *
 * @function submit
 * @description Envoie les modifications au serveur via une requête PUT
 */
const submit = () => {
    form.put(route("admin.presentation.update"));
};
</script>

<template>
    <!-- 🏗️ Layout avec dégradé violet -->
    <AppLayout class="bg-gradient-to-br from-white to-pastel-purple">
        <!-- 📊 En-tête de page -->
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion de la Présentation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"
                >
                    <!-- 📝 Formulaire principal -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- 📄 Section Introduction générale -->
                        <div>
                            <InputLabel
                                for="content"
                                value="Introduction générale"
                            />
                            <QuillEditor
                                :content="form.content"
                                @update:content="form.content = $event"
                                theme="snow"
                                contentType="html"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                :toolbar="[
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ color: [] }, { background: [] }],
                                    [{ align: [] }],
                                    [
                                        {
                                            size: [
                                                'small',
                                                false,
                                                'large',
                                                'huge',
                                            ],
                                        },
                                    ],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    ['link'],
                                ]"
                            />
                        </div>

                        <!-- 🏷️ Section À propos - Titre -->
                        <div>
                            <InputLabel
                                for="about_title"
                                value="Titre de la section À propos"
                            />
                            <TextInput
                                id="about_title"
                                v-model="form.about_title"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>

                        <!-- 📝 Section À propos - Contenu -->
                        <div>
                            <InputLabel
                                for="about_content"
                                value="Contenu de la section À propos"
                            />
                            <QuillEditor
                                :content="form.about_content"
                                @update:content="form.about_content = $event"
                                theme="snow"
                                contentType="html"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                :toolbar="[
                                    ['bold', 'italic', 'underline', 'strike'],
                                    [{ color: [] }, { background: [] }],
                                    [{ align: [] }],
                                    [
                                        {
                                            size: [
                                                'small',
                                                false,
                                                'large',
                                                'huge',
                                            ],
                                        },
                                    ],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    ['link'],
                                ]"
                            />
                        </div>

                        <!-- 💡 Section Expérience (commentée pour usage futur) -->
                        <!--
                        <div>
                            <InputLabel
                                for="experience_title"
                                value="Titre de la section Expérience"
                            />
                            <TextInput
                                id="experience_title"
                                v-model="form.experience_title"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>

                        <div>
                            <InputLabel
                                for="experience_content"
                                value="Contenu de la section Expérience"
                            />
                            <QuillEditor
                                :content="form.experience_content"
                                @update:content="form.experience_content = $event"
                                theme="snow"
                                contentType="html"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                :toolbar="[
                                    ['bold', 'italic', 'underline'],
                                    [{ list: 'ordered' }, { list: 'bullet' }],
                                    ['link'],
                                ]"
                            />
                        </div>
                        -->

                        <!-- 💾 Bouton de sauvegarde -->
                        <div class="flex items-center justify-end">
                            <PrimaryButton
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Enregistrer les modifications
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
