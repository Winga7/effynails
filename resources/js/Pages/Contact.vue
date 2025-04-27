<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";

// Récupérer les messages flash éventuels
const props = defineProps({
    errors: Object,
    success: String,
    error: String,
});

const form = useForm({
    name: "",
    email: "",
    message: "",
});

const isSubmitting = ref(false);
const showSuccessMessage = ref(!!props.success);
const showErrorMessage = ref(!!props.error);

// Vérifier si le formulaire a des erreurs
const hasErrors = computed(() => {
    return Object.keys(form.errors).length > 0;
});

const submit = () => {
    isSubmitting.value = true;
    showSuccessMessage.value = false;
    showErrorMessage.value = false;

    form.post(route("contact.send"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            isSubmitting.value = false;
            showSuccessMessage.value = true;
        },
        onError: () => {
            isSubmitting.value = false;
            showErrorMessage.value = true;
        },
    });
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
                <!-- Messages de notification -->
                <div
                    v-if="showSuccessMessage || success"
                    class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-700 rounded-lg"
                >
                    <p>
                        {{
                            success ||
                            "Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais."
                        }}
                    </p>
                </div>

                <div
                    v-if="showErrorMessage || error || hasErrors"
                    class="mb-4 px-4 py-3 bg-red-100 border border-red-200 text-red-700 rounded-lg"
                >
                    <p v-if="error">{{ error }}</p>
                    <p v-else-if="hasErrors">
                        Veuillez corriger les erreurs dans le formulaire.
                    </p>
                    <p v-else>
                        Une erreur est survenue lors de l'envoi de votre
                        message. Veuillez réessayer ultérieurement.
                    </p>
                </div>

                <div
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-pink-100"
                >
                    <div class="p-6 lg:p-8">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Informations de contact -->
                            <div class="space-y-6">
                                <h3 class="text-xl font-semibold text-pink-600">
                                    Coordonnées
                                </h3>
                                <div class="space-y-4 text-gray-700">
                                    <p class="flex items-center">
                                        <svg
                                            class="w-5 h-5 mr-2 text-pink-500"
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
                                            class="w-5 h-5 mr-2 text-pink-500"
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
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Nom
                                    </label>
                                    <input
                                        type="text"
                                        v-model="form.name"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-300 bg-white/70"
                                        :class="{
                                            'border-red-500': form.errors.name,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.name"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.name }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Email
                                    </label>
                                    <input
                                        type="email"
                                        v-model="form.email"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-300 bg-white/70"
                                        :class="{
                                            'border-red-500': form.errors.email,
                                        }"
                                    />
                                    <p
                                        v-if="form.errors.email"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.email }}
                                    </p>
                                </div>

                                <div>
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Message
                                    </label>
                                    <textarea
                                        v-model="form.message"
                                        rows="4"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-300 bg-white/70"
                                        :class="{
                                            'border-red-500':
                                                form.errors.message,
                                        }"
                                    ></textarea>
                                    <p
                                        v-if="form.errors.message"
                                        class="mt-1 text-sm text-red-600"
                                    >
                                        {{ form.errors.message }}
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-800 bg-pastel-yellow-btn hover:bg-pastel-yellow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pastel-yellow transition-colors duration-300"
                                    :disabled="isSubmitting"
                                >
                                    <svg
                                        v-if="isSubmitting"
                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-800"
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                    >
                                        <circle
                                            class="opacity-25"
                                            cx="12"
                                            cy="12"
                                            r="10"
                                            stroke="currentColor"
                                            stroke-width="4"
                                        ></circle>
                                        <path
                                            class="opacity-75"
                                            fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                        ></path>
                                    </svg>
                                    {{
                                        isSubmitting
                                            ? "Envoi en cours..."
                                            : "Envoyer"
                                    }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
