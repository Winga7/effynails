<template>
    <footer class="text-gray-700 py-8">
        <!-- Réseaux sociaux -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center space-x-6">
                <a
                    href="https://facebook.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-gray-700 hover:text-gray-900"
                >
                    <i class="fab fa-facebook-f text-2xl"></i>
                </a>
                <a
                    href="https://whatsapp.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-gray-700 hover:text-gray-900"
                >
                    <i class="fab fa-whatsapp text-2xl"></i>
                </a>
                <a
                    href="https://threads.net"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-gray-700 hover:text-gray-900"
                >
                    <i class="fab fa-pinterest text-2xl"></i>
                </a>
                <a
                    href="https://instagram.com"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-gray-700 hover:text-gray-900"
                >
                    <i class="fab fa-instagram text-2xl"></i>
                </a>
            </div>
        </div>

        <!-- Ligne de séparation -->
        <div class="border-t border-gray-800 my-6 mx-auto w-[15%]"></div>

        <!-- Mentions légales -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-center space-x-4 text-sm">
                <Link
                    :href="route('mentions-legales')"
                    class="hover:underline text-gray-700 hover:text-gray-900"
                >
                    Mentions légales
                </Link>
                <span>|</span>
                <Link
                    :href="route('plan-du-site')"
                    class="hover:underline text-gray-700 hover:text-gray-900"
                >
                    Plan du site
                </Link>
                <span>|</span>
                <Link
                    :href="route('politique-confidentialite')"
                    class="hover:underline text-gray-700 hover:text-gray-900"
                >
                    Politique de confidentialité
                </Link>
            </div>
        </div>

        <!-- Ligne de séparation -->
        <div class="border-t border-gray-800 my-6 mx-auto w-[35%]"></div>

        <!-- Made with ❤️ -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-sm text-gray-600">
                Made with ❤️ by <a href="https://www.winga.be">Winga</a> &copy;
                {{ new Date().getFullYear() }}
            </div>
        </div>
    </footer>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<style scoped>
/* Ajout d'un style personnalisé pour les lignes */
footer div.border-t {
    border-color: #202020;
}
</style>
