<script setup>
import { onMounted, ref } from "vue";

defineProps({
    modelValue: String,
});

defineEmits(["update:modelValue"]);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute("autofocus")) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        ref="input"
        class="form-input w-full px-4 py-3 bg-white border border-pink-200 rounded-lg shadow focus:border-pink-500 focus:ring-2 focus:ring-pink-300 focus:bg-white/90 hover:border-pink-400 transition-all duration-300 placeholder:text-gray-400 cursor-pointer"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>

<style scoped>
.form-input {
    backdrop-filter: blur(4px);
    outline: none;
}
</style>
