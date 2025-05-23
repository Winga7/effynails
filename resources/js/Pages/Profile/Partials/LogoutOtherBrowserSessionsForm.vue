<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import ActionMessage from "@/Components/ActionMessage.vue";
import ActionSection from "@/Components/ActionSection.vue";
import DialogModal from "@/Components/DialogModal.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

defineProps({
    sessions: Array,
});

const confirmingLogout = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: "",
});

const confirmLogout = () => {
    confirmingLogout.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const logoutOtherBrowserSessions = () => {
    form.delete(route("other-browser-sessions.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingLogout.value = false;

    form.reset();
};
</script>

<template>
    <ActionSection>
        <template #title>
            <h3 class="font-caveat text-2xl text-pink-600">
                Sessions de navigateur
            </h3>
        </template>
        <template #description>
            <p class="text-gray-600">
                Gérez et déconnectez-vous de vos sessions actives sur d'autres
                navigateurs et appareils.
            </p>
        </template>
        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Si nécessaire, vous pouvez vous déconnecter de toutes vos autres
                sessions sur tous vos appareils. Certaines de vos sessions
                récentes sont listées ci-dessous ; cependant, cette liste peut
                ne pas être exhaustive. Si vous pensez que votre compte a été
                compromis, vous devriez également mettre à jour votre mot de
                passe.
            </div>

            <!-- Other Browser Sessions -->
            <div v-if="sessions.length > 0" class="mt-5 space-y-6">
                <div
                    v-for="(session, i) in sessions"
                    :key="i"
                    class="flex items-center"
                >
                    <div>
                        <svg
                            v-if="session.agent.is_desktop"
                            class="size-8 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25"
                            />
                        </svg>

                        <svg
                            v-else
                            class="size-8 text-gray-500"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
                            />
                        </svg>
                    </div>

                    <div class="ms-3">
                        <div class="text-sm text-gray-600">
                            {{
                                session.agent.platform
                                    ? session.agent.platform
                                    : "Unknown"
                            }}
                            -
                            {{
                                session.agent.browser
                                    ? session.agent.browser
                                    : "Unknown"
                            }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},
                                <span
                                    v-if="session.is_current_device"
                                    class="text-green-500 font-semibold"
                                    >Cet appareil</span
                                >
                                <span v-else
                                    >Dernière activité
                                    {{ session.last_active }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center mt-5">
                <PrimaryButton
                    @click="confirmLogout"
                    class="bg-pink-600 hover:bg-pink-700"
                >
                    Déconnexion des sessions de navigateur
                </PrimaryButton>

                <ActionMessage
                    :on="form.recentlySuccessful"
                    class="ms-3 text-green-600"
                >
                    Terminé.
                </ActionMessage>
            </div>

            <!-- Log Out Other Devices Confirmation Modal -->
            <DialogModal :show="confirmingLogout" @close="closeModal">
                <template #title>
                    <h3 class="font-caveat text-2xl text-pink-600">
                        Déconnexion des sessions de navigateur
                    </h3>
                </template>

                <template #content>
                    <p class="text-gray-600">
                        Veuillez entrer votre mot de passe pour confirmer que
                        vous souhaitez vous déconnecter de vos sessions de
                        navigateur sur tous vos appareils.
                    </p>

                    <div class="mt-4">
                        <TextInput
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-1 block w-3/4 border-gray-300 focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            placeholder="Mot de passe"
                            autocomplete="current-password"
                            @keyup.enter="logoutOtherBrowserSessions"
                        />

                        <InputError
                            :message="form.errors.password"
                            class="mt-2"
                        />
                    </div>
                </template>

                <template #footer>
                    <SecondaryButton
                        @click="closeModal"
                        class="bg-pastel-pink hover:bg-pink-300 text-gray-700"
                    >
                        Annuler
                    </SecondaryButton>

                    <PrimaryButton
                        class="ms-3 bg-pink-600 hover:bg-pink-700"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="logoutOtherBrowserSessions"
                    >
                        Déconnexion des sessions de navigateur
                    </PrimaryButton>
                </template>
            </DialogModal>
        </template>
    </ActionSection>
</template>

<style>
.font-caveat {
    font-family: "Caveat", cursive;
}
</style>
