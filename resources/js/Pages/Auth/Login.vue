<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <!-- Corporate-style centered layout -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-gray-50 via-white to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-transform duration-300 hover:scale-[1.003]">
                <!-- top accent bar -->
                <div class="h-2 bg-gradient-to-r from-indigo-600 via-sky-500 to-emerald-500"></div>
                <div class="px-8 py-8 sm:px-10 sm:py-10">
                    <div class="flex flex-col items-center mb-6">
                        <div class="bg-white rounded-full p-3 shadow-lg -mt-12 border border-gray-100">
                            <!-- larger logo circle -->
                            <AuthenticationCardLogo class="w-16 h-16" />
                        </div>
                        <h2 class="mt-4 text-2xl font-extrabold text-gray-900 tracking-tight">Iniciar sesión</h2>
                        <p class="mt-2 text-sm text-gray-500">Accede a tu panel del Laboratorio</p>
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
                                    class="block w-full h-12 rounded-md border-gray-200 bg-white pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                            </div>
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password" />
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                    <i class="fa-solid fa-lock"></i>
                                </span>
                                <TextInput
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    class="block w-full h-12 rounded-md border-gray-200 bg-white pl-10 pr-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    required
                                    autocomplete="current-password"
                                />
                            </div>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <Checkbox v-model:checked="form.remember" name="remember" />
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-300">Recordarme</span>
                            </label>

                            <div class="text-sm">
                                <Link v-if="canResetPassword" :href="route('password.request')" class="font-medium text-indigo-600 hover:text-indigo-500">¿Olvidaste tu contraseña?</Link>
                            </div>
                        </div>

                        <div class="flex justify-center">
                            <PrimaryButton class="inline-flex items-center px-6 py-3 text-sm bg-gradient-to-r from-indigo-600 to-sky-500 hover:from-indigo-700 hover:to-sky-600 transform transition-all duration-200 shadow-lg rounded-md min-w-[180px] justify-center" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Iniciar sesión
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
            <p class="mt-6 text-center text-xs text-gray-400">© {{ new Date().getFullYear() }} Laboratorio de Transformación Digital</p>
        </div>
    </div>
</template>
