<script setup>
import { ref, onMounted, onUnmounted, computed } from "vue";
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Footer from "@/Components/footer.vue";
import CookieConsent from "@/Components/CookieConsent.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const showScrollButton = ref(false);

// Obtenir la route actuelle
const page = usePage();
const currentRoute = computed(() => route().current());

// Définir les couleurs de fond pour chaque page
const headerBackgroundClass = computed(() => {
    switch (currentRoute.value) {
        case "home":
            return "bg-gradient-to-r from-white to-pastel-peach";
        case "presentation":
            return "bg-gradient-to-r from-white to-pastel-purple";
        case "tarifs":
            return "bg-gradient-to-r from-white to-pastel-pink";
        case "portfolio":
            return "bg-gradient-to-r from-white to-pastel-peach";
        case "contact":
            return "bg-gradient-to-r from-white to-pastel-pink";
        case "reservation":
            return "bg-gradient-to-r from-white to-pastel-purple";
        default:
            return "bg-white bg-opacity-50"; // Fallback pour les autres pages
    }
});

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveScroll: false,
        }
    );
};

const logout = () => {
    router.post(route("logout"));
};

// Fonction pour fermer le menu
const closeMenu = () => {
    showingNavigationDropdown.value = false;
};

// Gestionnaire de clic en dehors du menu
const handleClickOutside = (event) => {
    const menu = document.getElementById("mobile-menu");
    const hamburgerButton = document.getElementById("hamburger-button");

    if (
        menu &&
        !menu.contains(event.target) &&
        !hamburgerButton.contains(event.target)
    ) {
        closeMenu();
    }
};

// Fonction pour gérer l'affichage du bouton de retour en haut
const handleScroll = () => {
    showScrollButton.value = window.scrollY > 300;
};

// Fonction pour remonter en haut
const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: "smooth",
    });
};

// Ajout/suppression des event listeners
onMounted(() => {
    document.addEventListener("click", handleClickOutside);
    window.addEventListener("scroll", handleScroll);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
    window.removeEventListener("scroll", handleScroll);
});

// Schema.org markup
const schemaMarkup = {
    "@context": "https://schema.org",
    "@type": "BeautySalon",
    name: "EFFYNAILS",
    image: "/images/hero-nails.jpg",
    description:
        "Salon de manucure à Wavre par Steffi Ledoux. Découvrez nos services de nail art et pose d'ongles en gel.",
    address: {
        "@type": "PostalAddress",
        addressLocality: "Wavre",
        addressCountry: "BE",
    },
    url: "https://effynails.be",
    priceRange: "€€",
    openingHoursSpecification: {
        "@type": "OpeningHoursSpecification",
        dayOfWeek: [
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday",
        ],
        opens: "09:00",
        closes: "18:00",
    },
    sameAs: [
        "https://www.facebook.com/profile.php?id=61576011179310",
        "https://www.instagram.com/nailseffy/",
        "https://wa.me/324XXXXXXXX",
    ],
    hasOfferCatalog: {
        "@type": "OfferCatalog",
        name: "Services de Manucure",
        itemListElement: [
            {
                "@type": "Offer",
                itemOffered: {
                    "@type": "Service",
                    name: "Manucure",
                },
            },
            {
                "@type": "Offer",
                itemOffered: {
                    "@type": "Service",
                    name: "Pose d'ongles en gel",
                },
            },
            {
                "@type": "Offer",
                itemOffered: {
                    "@type": "Service",
                    name: "Nail Art",
                },
            },
        ],
    },
    areaServed: {
        "@type": "City",
        name: "Wavre",
    },
    keywords:
        "manucure, nail art, pose d'ongles en gel, beauté des ongles, Wavre",
    slogan: "Votre expert en beauté des ongles à Wavre",
};
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <Head :title="title">
            <!-- Meta tags de base -->
            <meta charset="utf-8" />
            <meta
                name="viewport"
                content="width=device-width, initial-scale=1"
            />
            <meta name="robots" content="index, follow" />
            <meta name="language" content="French" />
            <meta name="revisit-after" content="7 days" />
            <meta name="author" content="Steffi Ledoux" />

            <!-- Open Graph / Facebook -->
            <meta property="og:type" content="website" />
            <meta
                property="og:url"
                :content="'https://effynails.be' + route().current()"
            />
            <meta property="og:title" :content="title" />
            <meta
                property="og:description"
                content="Salon de manucure à Wavre par Steffi Ledoux. Découvrez nos services de nail art et pose d'ongles en gel. Expert en beauté des ongles à Wavre."
            />
            <meta property="og:image" content="/images/hero-nails.jpg" />
            <meta property="og:locale" content="fr_BE" />
            <meta property="og:site_name" content="EFFYNAILS" />

            <!-- Schema.org markup -->
            <component
                :is="'script'"
                type="application/ld+json"
                v-html="JSON.stringify(schemaMarkup)"
            />
        </Head>
        <Banner />

        <div class="flex-grow flex flex-col">
            <nav
                :class="[
                    headerBackgroundClass,
                    'transition-colors duration-300',
                ]"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Logo et Nom -->
                        <div class="flex items-center">
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                    <img
                                        src="/images/logo.png"
                                        alt="Effy Nails"
                                        class="h-8 w-auto"
                                    />
                                </Link>
                            </div>
                            <span class="ml-3 text-xl font-bold font-caveat"
                                >EFFYNAILS</span
                            >
                        </div>

                        <!-- Bouton hamburger -->
                        <div class="min-[770px]:hidden flex items-center">
                            <button
                                id="hamburger-button"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center p-2 rounded-md text-black hover:text-gray-700 focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>

                        <!-- Navigation Links Desktop -->
                        <div
                            class="hidden min-[770px]:flex space-x-8 items-center"
                        >
                            <NavLink
                                v-for="(link, index) in [
                                    { name: 'Accueil', route: 'home' },
                                    {
                                        name: 'Présentation',
                                        route: 'presentation',
                                    },
                                    { name: 'Tarifs', route: 'tarifs' },
                                    { name: 'Portfolio', route: 'portfolio' },
                                    { name: 'Contact', route: 'contact' },
                                ]"
                                :key="index"
                                :href="route(link.route)"
                                :active="route().current(link.route)"
                            >
                                {{ link.name }}
                            </NavLink>
                            <Link
                                :href="route('reservation')"
                                class="ml-4 px-4 py-2 bg-pastel-yellow-btn text-sm font-medium rounded-md hover:bg-pastel-yellow transition-colors duration-300"
                            >
                                Réservation
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Menu Mobile -->
                <div
                    id="mobile-menu"
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="min-[770px]:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            v-for="(link, index) in [
                                { name: 'Accueil', route: 'home' },
                                { name: 'Présentation', route: 'presentation' },
                                { name: 'Tarifs', route: 'tarifs' },
                                { name: 'Portfolio', route: 'portfolio' },
                                { name: 'Contact', route: 'contact' },
                            ]"
                            :key="index"
                            :href="route(link.route)"
                            :active="route().current(link.route)"
                        >
                            {{ link.name }}
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('reservation')"
                            class="bg-pastel-yellow-btn hover:bg-pastel-yellow"
                        >
                            Réservation
                        </ResponsiveNavLink>

                        <!-- Séparation Administrative -->
                        <div v-if="$page.props.auth.user" class="my-3">
                            <div class="border-t-4 border-pink-200"></div>
                            <div
                                class="py-3 px-3 bg-gradient-to-r from-pink-100 to-purple-100"
                            >
                                <div
                                    class="text-sm font-semibold text-gray-700 uppercase tracking-wider"
                                >
                                    Mode Administration
                                </div>
                            </div>
                            <ResponsiveNavLink
                                v-for="(link, index) in [
                                    { name: 'Dashboard', route: 'dashboard' },
                                    {
                                        name: 'Tarifs Admin',
                                        route: 'admin.tarifsadmin',
                                    },
                                    {
                                        name: 'Portfolio Admin',
                                        route: 'admin.portfolioadmin',
                                    },
                                    {
                                        name: 'Présentation',
                                        route: 'admin.presentation.edit',
                                    },
                                ]"
                                :key="'admin-' + index"
                                :href="route(link.route)"
                                :active="route().current(link.route)"
                                class="bg-gray-50"
                            >
                                {{ link.name }}
                            </ResponsiveNavLink>
                        </div>

                        <div
                            v-if="$page.props.auth.user"
                            class="pt-4 pb-1 border-t border-gray-200"
                        >
                            <div class="flex items-center px-4">
                                <div>
                                    <div
                                        class="font-medium text-base text-gray-800"
                                    >
                                        {{ $page.props.auth.user.name }}
                                    </div>
                                    <div
                                        class="font-medium text-sm text-gray-500"
                                    >
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink
                                    :href="route('profile.show')"
                                >
                                    Profile
                                </ResponsiveNavLink>

                                <!-- Déconnexion -->
                                <form method="POST" @submit.prevent="logout">
                                    <ResponsiveNavLink as="button">
                                        Déconnexion
                                    </ResponsiveNavLink>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Navigation Administrative Desktop -->
            <nav
                v-if="$page.props.auth.user"
                class="hidden min-[770px]:block border-b border-gray-100 bg-gradient-to-r from-pink-100 to-purple-100"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-12">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm font-semibold text-gray-700"
                                >Mode Administration</span
                            >
                            <NavLink
                                v-for="(link, index) in [
                                    { name: 'Dashboard', route: 'dashboard' },
                                    {
                                        name: 'Tarifs Admin',
                                        route: 'admin.tarifsadmin',
                                    },
                                    {
                                        name: 'Portfolio Admin',
                                        route: 'admin.portfolioadmin',
                                    },
                                    {
                                        name: 'Présentation',
                                        route: 'admin.presentation.edit',
                                    },
                                ]"
                                :key="index"
                                :href="route(link.route)"
                                :active="route().current(link.route)"
                                class="text-sm font-medium text-gray-600 hover:text-gray-900"
                            >
                                {{ link.name }}
                            </NavLink>
                        </div>

                        <div class="flex items-center">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button
                                        class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition duration-150 ease-in-out"
                                    >
                                        <div>
                                            {{ $page.props.auth.user.name }}
                                        </div>

                                        <div class="ml-1">
                                            <svg
                                                class="fill-current h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </div>
                                    </button>
                                </template>

                                <template #content>
                                    <!-- Profile -->
                                    <DropdownLink :href="route('profile.show')">
                                        Profile
                                    </DropdownLink>

                                    <!-- Déconnexion -->
                                    <div class="border-t border-gray-200" />

                                    <form @submit.prevent="logout">
                                        <DropdownLink as="button">
                                            Déconnexion
                                        </DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="bg-transparent flex-grow flex flex-col">
                <div v-if="$slots.header" class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </div>
                <slot />
            </main>

            <Footer />
        </div>

        <CookieConsent />

        <!-- Bouton retour en haut -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-10 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="transform translate-y-0 opacity-100"
            leave-to-class="transform translate-y-10 opacity-0"
        >
            <button
                v-show="showScrollButton"
                @click="scrollToTop"
                class="fixed bottom-8 right-8 bg-pastel-yellow-btn hover:bg-pastel-yellow text-gray-700 rounded-full p-3 shadow-lg transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-pastel-yellow focus:ring-offset-2 z-50"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 10l7-7m0 0l7 7m-7-7v18"
                    />
                </svg>
            </button>
        </transition>
    </div>
</template>
