<script setup>
import { ref, computed, watch } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import ActionSection from "@/Components/ActionSection.vue";
import ConfirmsPassword from "@/Components/ConfirmsPassword.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    requiresConfirmation: Boolean,
});

const page = usePage();
const enabling = ref(false);
const confirming = ref(false);
const disabling = ref(false);
const qrCode = ref(null);
const setupKey = ref(null);
const recoveryCodes = ref([]);

const confirmationForm = useForm({
    code: "",
});

const twoFactorEnabled = computed(
    () => !enabling.value && page.props.auth.user?.two_factor_enabled
);

watch(twoFactorEnabled, () => {
    if (!twoFactorEnabled.value) {
        confirmationForm.reset();
        confirmationForm.clearErrors();
    }
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    router.post(
        route("two-factor.enable"),
        {},
        {
            preserveScroll: true,
            onSuccess: () =>
                Promise.all([
                    showQrCode(),
                    showSetupKey(),
                    showRecoveryCodes(),
                ]),
            onFinish: () => {
                enabling.value = false;
                confirming.value = props.requiresConfirmation;
            },
        }
    );
};

const showQrCode = () => {
    return axios.get(route("two-factor.qr-code")).then((response) => {
        qrCode.value = response.data.svg;
    });
};

const showSetupKey = () => {
    return axios.get(route("two-factor.secret-key")).then((response) => {
        setupKey.value = response.data.secretKey;
    });
};

const showRecoveryCodes = () => {
    return axios.get(route("two-factor.recovery-codes")).then((response) => {
        recoveryCodes.value = response.data;
    });
};

const confirmTwoFactorAuthentication = () => {
    confirmationForm.post(route("two-factor.confirm"), {
        errorBag: "confirmTwoFactorAuthentication",
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            qrCode.value = null;
            setupKey.value = null;
        },
    });
};

const regenerateRecoveryCodes = () => {
    axios
        .post(route("two-factor.recovery-codes"))
        .then(() => showRecoveryCodes());
};

const disableTwoFactorAuthentication = () => {
    disabling.value = true;

    router.delete(route("two-factor.disable"), {
        preserveScroll: true,
        onSuccess: () => {
            disabling.value = false;
            confirming.value = false;
        },
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            <h3 class="font-caveat text-2xl text-pink-600">
                Authentification à deux facteurs
            </h3>
        </template>
        <template #description>
            <p class="text-gray-600">
                Ajoutez une sécurité supplémentaire à votre compte en utilisant
                l'authentification à deux facteurs.
            </p>
        </template>
        <template #content>
            <h3
                v-if="twoFactorEnabled && !confirming"
                class="text-lg font-medium text-gray-900"
            >
                Vous avez activé l'authentification à deux facteurs.
            </h3>

            <h3
                v-else-if="twoFactorEnabled && confirming"
                class="text-lg font-medium text-gray-900"
            >
                Terminez l'activation de l'authentification à deux facteurs.
            </h3>

            <h3 v-else class="text-lg font-medium text-gray-900">
                Vous n'avez pas activé l'authentification à deux facteurs.
            </h3>

            <div class="mt-3 max-w-xl text-sm text-gray-600">
                <p>
                    Lorsque l'authentification à deux facteurs est activée, vous
                    serez invité à entrer un jeton sécurisé et aléatoire lors de
                    l'authentification. Vous pouvez récupérer ce jeton depuis
                    l'application Google Authenticator de votre téléphone.
                </p>
            </div>

            <div v-if="twoFactorEnabled">
                <div v-if="qrCode">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p v-if="confirming" class="font-semibold">
                            Pour terminer l'activation de l'authentification à
                            deux facteurs, scannez le code QR suivant à l'aide
                            de l'application authenticateur de votre téléphone
                            ou entrez la clé de configuration et fournissez le
                            code OTP généré.
                        </p>

                        <p v-else>
                            L'authentification à deux facteurs est maintenant
                            activée. Scannez le code QR suivant à l'aide de
                            l'application authenticateur de votre téléphone ou
                            entrez la clé de configuration.
                        </p>
                    </div>

                    <div
                        class="mt-4 p-2 inline-block bg-white rounded-lg shadow-md"
                        v-html="qrCode"
                    />

                    <div
                        v-if="setupKey"
                        class="mt-4 max-w-xl text-sm text-gray-600"
                    >
                        <p class="font-semibold">
                            Clé de configuration:
                            <span v-html="setupKey"></span>
                        </p>
                    </div>

                    <div v-if="confirming" class="mt-4">
                        <InputLabel
                            for="code"
                            value="Code"
                            class="text-gray-700"
                        />

                        <TextInput
                            id="code"
                            v-model="confirmationForm.code"
                            type="text"
                            name="code"
                            class="block mt-1 w-1/2 border-gray-300 focus:border-pink-300 focus:ring focus:ring-pink-200 focus:ring-opacity-50 rounded-md shadow-sm"
                            inputmode="numeric"
                            autofocus
                            autocomplete="one-time-code"
                            @keyup.enter="confirmTwoFactorAuthentication"
                        />

                        <InputError
                            :message="confirmationForm.errors.code"
                            class="mt-2"
                        />
                    </div>
                </div>

                <div v-if="recoveryCodes.length > 0 && !confirming">
                    <div class="mt-4 max-w-xl text-sm text-gray-600">
                        <p class="font-semibold">
                            Stockez ces codes de récupération dans un
                            gestionnaire de mots de passe sécurisé. Ils peuvent
                            être utilisés pour récupérer l'accès à votre compte
                            si votre dispositif d'authentification à deux
                            facteurs est perdu.
                        </p>
                    </div>

                    <div
                        class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg shadow-sm"
                    >
                        <div v-for="code in recoveryCodes" :key="code">
                            {{ code }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <div v-if="!twoFactorEnabled">
                    <ConfirmsPassword
                        @confirmed="enableTwoFactorAuthentication"
                    >
                        <PrimaryButton
                            type="button"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                            class="bg-pink-600 hover:bg-pink-700"
                        >
                            Activer
                        </PrimaryButton>
                    </ConfirmsPassword>
                </div>

                <div v-else>
                    <ConfirmsPassword
                        @confirmed="confirmTwoFactorAuthentication"
                    >
                        <PrimaryButton
                            v-if="confirming"
                            type="button"
                            class="me-3 bg-pink-600 hover:bg-pink-700"
                            :class="{ 'opacity-25': enabling }"
                            :disabled="enabling"
                        >
                            Confirmer
                        </PrimaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="regenerateRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length > 0 && !confirming"
                            class="me-3 bg-pastel-yellow-btn hover:bg-pastel-yellow text-gray-700"
                        >
                            Régénérer les codes de récupération
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword @confirmed="showRecoveryCodes">
                        <SecondaryButton
                            v-if="recoveryCodes.length === 0 && !confirming"
                            class="me-3 bg-pastel-yellow-btn hover:bg-pastel-yellow text-gray-700"
                        >
                            Afficher les codes de récupération
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword
                        @confirmed="disableTwoFactorAuthentication"
                    >
                        <SecondaryButton
                            v-if="confirming"
                            class="me-3 bg-pastel-pink hover:bg-pink-300 text-gray-700"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                        >
                            Annuler
                        </SecondaryButton>
                    </ConfirmsPassword>

                    <ConfirmsPassword
                        @confirmed="disableTwoFactorAuthentication"
                    >
                        <DangerButton
                            v-if="!confirming"
                            :class="{ 'opacity-25': disabling }"
                            :disabled="disabling"
                            class="bg-red-600 hover:bg-red-700"
                        >
                            Désactiver
                        </DangerButton>
                    </ConfirmsPassword>
                </div>
            </div>
        </template>
    </ActionSection>
</template>

<style>
.font-caveat {
    font-family: "Caveat", cursive;
}
</style>
