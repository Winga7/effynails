<script setup>
import { ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    image: {
        type: Object,
        default: () => ({
            title: "",
            description: "",
            image_path: "",
        }),
    },
});

const emit = defineEmits(["close"]);

const close = () => {
    emit("close");
};
</script>

<template>
    <Modal :show="show" @close="close" max-width="4xl">
        <div class="p-4">
            <div class="relative">
                <!-- Image en grand -->
                <img
                    v-if="image && image.image_path"
                    :src="image.image_path"
                    :alt="image.title"
                    class="w-full h-auto max-h-[80vh] object-contain"
                />

                <!-- Informations de l'image -->
                <div
                    v-if="image && image.title"
                    class="absolute bottom-0 left-0 right-0 bg-black/50 text-white p-4"
                >
                    <h3 class="text-xl font-semibold">{{ image.title }}</h3>
                    <p v-if="image.description" class="text-sm mt-2">
                        {{ image.description }}
                    </p>
                </div>
            </div>
        </div>
    </Modal>
</template>
