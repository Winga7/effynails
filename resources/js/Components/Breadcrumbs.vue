<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    items: {
        type: Array,
        required: true,
        // Format attendu: [{ label: 'Accueil', route: 'home' }, { label: 'Portfolio', route: 'portfolio' }]
    },
});
</script>

<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li
                v-for="(item, index) in items"
                :key="index"
                class="inline-flex items-center"
            >
                <template v-if="index === items.length - 1">
                    <span class="text-gray-500">{{ item.label }}</span>
                </template>
                <template v-else>
                    <Link
                        :href="route(item.route)"
                        class="text-gray-700 hover:text-pink-600"
                    >
                        {{ item.label }}
                    </Link>
                    <svg
                        class="w-3 h-3 mx-1 text-gray-400"
                        aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 6 10"
                    >
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="m1 9 4-4-4-4"
                        />
                    </svg>
                </template>
            </li>
        </ol>
    </nav>
</template>
