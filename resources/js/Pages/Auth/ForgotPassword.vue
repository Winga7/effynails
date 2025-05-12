<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    status: String,
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-white to-pastel-peach"
    >
        <Head title="Mot de passe oublié" />
        <AuthenticationCard>
            <template #logo>
                <AuthenticationCardLogo />
            </template>

            <div class="mb-4 text-sm text-peach-600 dark:text-peach-400">
                Mot de passe oublié ? Pas de problème. Il suffit de nous donner
                votre adresse email et nous vous enverrons un lien de
                réinitialisation de mot de passe qui vous permettra de choisir
                un nouveau mot de passe.
            </div>

            <div
                v-if="status"
                class="mb-4 font-medium text-sm text-peach-600 dark:text-peach-400"
            >
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton
                        :class="{
                            'opacity-25': form.processing,
                            'bg-peach-500 hover:bg-peach-600 text-white': true,
                        }"
                        :disabled="form.processing"
                    >
                        Envoyer le lien de réinitialisation
                    </PrimaryButton>
                </div>
            </form>
        </AuthenticationCard>
    </div>
</template>
