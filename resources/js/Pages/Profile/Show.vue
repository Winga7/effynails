<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import LogoutOtherBrowserSessionsForm from "@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue";
import SectionBorder from "@/Components/SectionBorder.vue";
import TwoFactorAuthenticationForm from "@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout
        title="Profil"
        class="bg-gradient-to-br from-white to-pastel-peach"
    >
        <template #header>
            <h2 class="font-caveat text-3xl text-pink-600">Profil</h2>
        </template>

        <div
            class="min-h-[70vh] flex items-center justify-center max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"
        >
            <div class="w-full space-y-6">
                <div
                    v-if="$page.props.jetstream.canUpdateProfileInformation"
                    class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                >
                    <UpdateProfileInformationForm
                        :user="$page.props.auth.user"
                    />
                    <SectionBorder />
                </div>

                <div
                    v-if="$page.props.jetstream.canUpdatePassword"
                    class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                >
                    <UpdatePasswordForm class="mt-10 sm:mt-0" />
                    <SectionBorder />
                </div>

                <div
                    v-if="
                        $page.props.jetstream.canManageTwoFactorAuthentication
                    "
                    class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                >
                    <TwoFactorAuthenticationForm
                        :requires-confirmation="confirmsTwoFactorAuthentication"
                        class="mt-10 sm:mt-0"
                    />
                    <SectionBorder />
                </div>

                <div
                    class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1"
                >
                    <LogoutOtherBrowserSessionsForm
                        :sessions="sessions"
                        class="mt-10 sm:mt-0"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style>
.font-caveat {
    font-family: "Caveat", cursive;
}
</style>
