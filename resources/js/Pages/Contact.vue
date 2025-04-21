<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    email: "",
    message: "",
});

const submit = () => {
    // À implémenter plus tard
    form.post(route("contact.send"));
};
</script>

<template>
    <Head title="Contact" />

    <AppLayout class="bg-gradient-to-br from-white to-pastel-pink">
        <template #header>
            <h2
                class="font-semibold text-2xl text-gray-800 font-caveat leading-tight"
            >
                Contact
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg"
                >
                    <div class="p-6 lg:p-8">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Informations de contact -->
                            <div class="space-y-6">
                                <h3 class="text-lg font-semibold text-gray-100">
                                    Coordonnées
                                </h3>
                                <div class="space-y-4 text-gray-400">
                                    <p class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                            />
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                            />
                                        </svg>
                                        Adresse du salon
                                    </p>
                                    <p class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                            />
                                        </svg>
                                        Téléphone
                                    </p>
                                </div>
                            </div>

                            <!-- Formulaire de contact -->
                            <form @submit.prevent="submit" class="space-y-6">
                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-300"
                                    >
                                        Nom
                                    </label>
                                    <input
                                        type="text"
                                        v-model="form.name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    />
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-300"
                                    >
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        v-model="form.email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    />
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-300"
                                    >
                                        Message
                                    </label>
                                    <textarea
                                        v-model="form.message"
                                        rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Envoyer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
