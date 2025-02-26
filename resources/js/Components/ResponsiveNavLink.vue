<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
});

const classes = computed(() => {
    return props.active
        ? "block w-full pl-3 pr-4 py-2 border-l-4 border-black text-base font-medium text-black bg-gray-50 focus:outline-none transition duration-150 ease-in-out"
        : "block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-black hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:outline-none transition duration-150 ease-in-out";
});
</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-left">
            <slot />
        </button>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>
