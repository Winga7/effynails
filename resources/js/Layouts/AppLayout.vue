<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

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

// Ajout/suppression des event listeners
onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div>
        <Head :title="title" />
        <Banner />

        <div class="min-h-screen">
            <nav class="border-b border-gray-100">
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
                                ]"
                                :key="'admin-' + index"
                                :href="route(link.route)"
                                :active="route().current(link.route)"
                                class="bg-gray-50"
                            >
                                {{ link.name }}
                            </ResponsiveNavLink>
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
                                ]"
                                :key="index"
                                :href="route(link.route)"
                                :active="route().current(link.route)"
                                class="text-sm font-medium text-gray-600 hover:text-gray-900"
                            >
                                {{ link.name }}
                            </NavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="bg-transparent">
                <div
                    v-if="$slots.header"
                    class="bg-white dark:bg-gray-800 shadow"
                >
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>
