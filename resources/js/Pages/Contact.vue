<script setup>
/**
 * 📞 Page de Contact
 *
 * Interface de contact avec formulaire et informations de localisation.
 * Permet aux utilisateurs d'envoyer des messages avec validation en temps réel.
 * Intègre Google Maps et affiche les coordonnées de l'établissement.
 *
 * @component
 * @requires AppLayout
 * @requires Head
 * @requires useForm
 * @requires Breadcrumbs
 */

import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Breadcrumbs from "@/Components/Breadcrumbs.vue";

// 📨 Props pour la gestion des messages flash
const props = defineProps({
    errors: Object, // Erreurs de validation
    success: String, // Message de succès
    error: String, // Message d'erreur
});

// 📝 Formulaire de contact avec validation
const form = useForm({
    firstName: "", // Prénom du contact
    lastName: "", // Nom du contact
    email: "", // Email du contact
    phone: "", // Téléphone du contact
    subject: "", // Sujet du message
    message: "", // Contenu du message
});

// 🔄 États réactifs pour la gestion du formulaire
const isSubmitting = ref(false); // État de soumission
const showSuccessMessage = ref(!!props.success); // Affichage message succès
const showErrorMessage = ref(!!props.error); // Affichage message erreur

/**
 * 🔍 Vérification des erreurs de formulaire
 *
 * @computed hasErrors
 * @returns {boolean} True si le formulaire contient des erreurs
 */
const hasErrors = computed(() => {
    return Object.keys(form.errors).length > 0;
});

/**
 * 📤 Soumission du formulaire de contact
 *
 * @function submit
 * @description Envoie les données du formulaire au serveur
 */
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

// 🗺️ Configuration des éléments de navigation
const breadcrumbItems = [
    { label: "Accueil", route: "home" },
    { label: "Contact", route: "contact" },
];
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
                <!-- Breadcrumbs -->
                <div
                    class="p-6 border-b bg-white/90 backdrop-blur-sm rounded-t-lg border border-pink-100"
                >
                    <Breadcrumbs :items="breadcrumbItems" />
                </div>

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
                    class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border border-pink-100 border-t-0"
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
                                            class="w-5 h-5 text-pink-400 z-10"
                                            style="
                                                color: #ec4899;
                                                opacity: 1;
                                                width: 20px;
                                                height: 20px;
                                                z-index: 10;
                                            "
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
                                        Effynails, Rue de la manucure 1, 1300
                                        Wavre
                                    </p>
                                    <p class="flex items-center">
                                        <svg
                                            class="w-5 h-5 text-pink-400 z-10"
                                            style="
                                                color: #ec4899;
                                                opacity: 1;
                                                width: 20px;
                                                height: 20px;
                                                z-index: 10;
                                            "
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
                                        <a
                                            href="tel:+324XXXXXXXX"
                                            class="hover:text-pink-600 transition-colors"
                                            >+32 4XXXXXXXX</a
                                        >
                                    </p>
                                </div>

                                <!-- Google Maps -->
                                <div class="mt-6">
                                    <iframe
                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d795.7288750944803!2d4.601806313951563!3d50.71471786475913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c17dcb3d2be3a5%3A0x1ba7122e08fb20cf!2sLa%20Sucrerie!5e0!3m2!1sfr!2sbe!4v1746707711436!5m2!1sfr!2sbe"
                                        width="100%"
                                        height="300"
                                        style="border: 0"
                                        allowfullscreen=""
                                        loading="lazy"
                                        referrerpolicy="no-referrer-when-downgrade"
                                        class="rounded-lg shadow-md"
                                    >
                                    </iframe>
                                </div>
                            </div>

                            <!-- Formulaire de contact -->
                            <form @submit.prevent="submit" class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Prénom</label>
                                        <div class="relative">
                                            <svg
                                                class="input-icon w-5 h-5 text-pink-400 z-10"
                                                style="
                                                    color: #ec4899;
                                                    opacity: 1;
                                                    width: 20px;
                                                    height: 20px;
                                                    z-index: 10;
                                                "
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                            <input
                                                type="text"
                                                v-model="form.firstName"
                                                class="form-input input-with-icon"
                                                :class="{
                                                    'border-red-500':
                                                        form.errors.firstName,
                                                }"
                                                placeholder="Votre prénom"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.firstName"
                                            class="form-error"
                                        >
                                            {{ form.errors.firstName }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Nom</label>
                                        <div class="relative">
                                            <svg
                                                class="input-icon w-5 h-5 text-pink-400 z-10"
                                                style="
                                                    color: #ec4899;
                                                    opacity: 1;
                                                    width: 20px;
                                                    height: 20px;
                                                    z-index: 10;
                                                "
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                                />
                                            </svg>
                                            <input
                                                type="text"
                                                v-model="form.lastName"
                                                class="form-input input-with-icon"
                                                :class="{
                                                    'border-red-500':
                                                        form.errors.lastName,
                                                }"
                                                placeholder="Votre nom"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.lastName"
                                            class="form-error"
                                        >
                                            {{ form.errors.lastName }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label class="form-label">Email</label>
                                        <div class="relative">
                                            <svg
                                                class="input-icon w-5 h-5 text-pink-400 z-10"
                                                style="
                                                    color: #ec4899;
                                                    opacity: 1;
                                                    width: 20px;
                                                    height: 20px;
                                                    z-index: 10;
                                                "
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                />
                                            </svg>
                                            <input
                                                type="email"
                                                v-model="form.email"
                                                class="form-input input-with-icon"
                                                :class="{
                                                    'border-red-500':
                                                        form.errors.email,
                                                }"
                                                placeholder="votre@email.com"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.email"
                                            class="form-error"
                                        >
                                            {{ form.errors.email }}
                                        </p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label"
                                            >Téléphone</label
                                        >
                                        <div class="relative">
                                            <svg
                                                class="input-icon w-5 h-5 text-pink-400 z-10"
                                                style="
                                                    color: #ec4899;
                                                    opacity: 1;
                                                    width: 20px;
                                                    height: 20px;
                                                    z-index: 10;
                                                "
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
                                            <input
                                                type="tel"
                                                v-model="form.phone"
                                                class="form-input input-with-icon"
                                                :class="{
                                                    'border-red-500':
                                                        form.errors.phone,
                                                }"
                                                placeholder="+32 4XX XX XX XX"
                                            />
                                        </div>
                                        <p
                                            v-if="form.errors.phone"
                                            class="form-error"
                                        >
                                            {{ form.errors.phone }}
                                        </p>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Sujet</label>
                                    <div class="relative">
                                        <svg
                                            class="input-icon w-5 h-5 text-pink-400 z-10"
                                            style="
                                                color: #ec4899;
                                                opacity: 1;
                                                width: 20px;
                                                height: 20px;
                                                z-index: 10;
                                            "
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                            />
                                        </svg>
                                        <input
                                            type="text"
                                            v-model="form.subject"
                                            class="form-input input-with-icon"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.subject,
                                            }"
                                            placeholder="Sujet de votre message"
                                        />
                                    </div>
                                    <p
                                        v-if="form.errors.subject"
                                        class="form-error"
                                    >
                                        {{ form.errors.subject }}
                                    </p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Message</label>
                                    <div class="relative">
                                        <svg
                                            class="input-icon w-5 h-5 text-pink-400 z-10"
                                            style="
                                                color: #ec4899;
                                                opacity: 1;
                                                width: 20px;
                                                height: 20px;
                                                z-index: 10;
                                            "
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                                            />
                                        </svg>
                                        <textarea
                                            v-model="form.message"
                                            rows="4"
                                            class="form-input input-with-icon"
                                            :class="{
                                                'border-red-500':
                                                    form.errors.message,
                                            }"
                                            placeholder="Votre message..."
                                        ></textarea>
                                    </div>
                                    <p
                                        v-if="form.errors.message"
                                        class="form-error"
                                    >
                                        {{ form.errors.message }}
                                    </p>
                                </div>

                                <button
                                    type="submit"
                                    class="submit-btn"
                                    :disabled="isSubmitting"
                                >
                                    <div
                                        class="flex items-center justify-center"
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
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.form-input {
    @apply w-full px-4 py-3 bg-white/50 border-0 rounded-lg shadow-sm focus:ring-2 focus:ring-pink-300 focus:bg-white/80 transition-all duration-300;
    backdrop-filter: blur(4px);
}

.form-input::placeholder {
    @apply text-gray-400;
}

.form-label {
    @apply block text-sm font-medium text-gray-700 mb-1.5;
}

.form-group {
    @apply relative;
}

.form-error {
    @apply mt-1 text-sm text-red-600;
}

.input-icon {
    @apply absolute left-3 top-1/2 -translate-y-1/2 text-gray-400;
}

.input-with-icon {
    @apply pl-10;
}

.submit-btn {
    @apply w-full py-3 px-6 text-gray-800 bg-pastel-yellow-btn hover:bg-pastel-yellow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pastel-yellow transition-all duration-300 rounded-lg font-medium shadow-sm hover:shadow-md;
}

.submit-btn:disabled {
    @apply opacity-70 cursor-not-allowed;
}
</style>
