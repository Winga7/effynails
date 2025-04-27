<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    presentation: Object
});

const form = useForm({
    content: props.presentation?.content || '',
    about_title: props.presentation?.about_title || '',
    about_content: props.presentation?.about_content || '',
    experience_title: props.presentation?.experience_title || '',
    experience_content: props.presentation?.experience_content || ''
});

const submit = () => {
    form.put(route('admin.presentation.update'));
};
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestion de la Présentation
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Introduction générale -->
                        <div>
                            <InputLabel for="content" value="Introduction générale" />
                            <textarea
                                id="content"
                                v-model="form.content"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                rows="4"
                            ></textarea>
                        </div>

                        <!-- À propos -->
                        <div>
                            <InputLabel for="about_title" value="Titre de la section À propos" />
                            <TextInput
                                id="about_title"
                                v-model="form.about_title"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>

                        <div>
                            <InputLabel for="about_content" value="Contenu de la section À propos" />
                            <textarea
                                id="about_content"
                                v-model="form.about_content"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                rows="6"
                            ></textarea>
                        </div>

                        <!-- Expérience -->
                        <div>
                            <InputLabel for="experience_title" value="Titre de la section Expérience" />
                            <TextInput
                                id="experience_title"
                                v-model="form.experience_title"
                                type="text"
                                class="mt-1 block w-full"
                            />
                        </div>

                        <div>
                            <InputLabel for="experience_content" value="Contenu de la section Expérience" />
                            <textarea
                                id="experience_content"
                                v-model="form.experience_content"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50"
                                rows="6"
                            ></textarea>
                        </div>

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
