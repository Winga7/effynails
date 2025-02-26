<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <Link :href="'/'">
        <img
            src="/images/logo.png"
            alt="Logo"
            class="w-auto h-20 object-contain"
        />
    </Link>
</template>
