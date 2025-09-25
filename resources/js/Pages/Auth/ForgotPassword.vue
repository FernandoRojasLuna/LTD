<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-50 via-white to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-indigo-600 via-sky-500 to-emerald-500"></div>
                <div class="px-8 py-8 sm:px-10 sm:py-10">
                    <div class="flex flex-col items-center mb-6">
                        <div class="bg-white rounded-full p-3 shadow-lg -mt-12 border border-gray-100">
                            <AuthenticationCardLogo class="w-14 h-14" />
                        </div>
                        <h2 class="mt-4 text-2xl font-extrabold text-gray-900 tracking-tight">Recuperar contraseña</h2>
                        <p class="mt-2 text-sm text-gray-500 text-center">Indícanos tu correo y te enviaremos un enlace para restablecer la contraseña.</p>
                    </div>

                    <div v-if="status" class="mb-4 font-medium text-sm text-green-600 text-center">
                        {{ status }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <InputLabel for="email" value="Email" />
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <TextInput
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="block w-full h-12 rounded-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                            </div>
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="flex items-center justify-end">
                            <PrimaryButton class="w-full py-3 text-sm bg-gradient-to-r from-indigo-600 to-sky-500 hover:from-indigo-700 hover:to-sky-600 shadow-lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Enviar enlace
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-gray-400">© {{ new Date().getFullYear() }} Laboratorio de Transformación Digital</p>
        </div>
    </div>
</template>
