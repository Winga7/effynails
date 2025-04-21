<script setup>
import { Head } from "@inertiajs/vue3";
import PrivacyPolicy from "@/Pages/PrivacyPolicy.vue";

defineProps({
    policy: String,
});
</script>

<template>
    <PrivacyPolicy :policy="policy" />
</template>
