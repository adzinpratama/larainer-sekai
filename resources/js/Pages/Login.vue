<script setup>
import { useLayout } from '@/Layouts/composables/layout';
import { ref, computed } from '@vue/reactivity';
import AppConfig from '@/Layouts/AppConfig.vue';
import AppLayout from '../Layouts/AppLayout.vue';
import { asset } from '../Libs/supports';
import { formHandle } from "../Libs/handle";
import { useForm } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const { layoutConfig } = useLayout();

const forms = useForm({
    email: null,
    password: null,
    remember: false
})

const logoUrl = computed(() => {
    return asset(`layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`);
});

onMounted(() => document.getElementById('preloader')?.remove())

</script>

<template>
    <div class="surface-ground flex align-items-center justify-content-center min-h-screen min-w-screen overflow-hidden">
        <div class="flex flex-column align-items-center justify-content-center">
            <img :src="logoUrl" alt="Sakai logo" class="mb-5 w-6rem flex-shrink-0" />
            <div
                style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full surface-card py-8 px-5 sm:px-8" style="border-radius: 53px">
                    <div class="text-center mb-5">
                        <!-- <img src="/demo/images/login/avatar.png" alt="Image" height="50" class="mb-3" />
                        <div class="text-900 text-3xl font-medium mb-3">Welcome, Isabel!</div> -->
                        <span class="text-600 font-medium">Sign in to continue</span>
                    </div>

                    <form @submit.prevent="formHandle(forms, {
                        routeName: 'auth.process',
                        onSuccess: log,
                        onError: log
                    })">
                        <label for="email1" class="block text-900 text-xl font-medium mb-2">Email</label>
                        <InputText id="email1" type="text" placeholder="Email address" class="w-full md:w-30rem mb-5"
                            style="padding: 1rem" v-model="forms.email" :aria-errormessage="forms.errors.email" />

                        <label for="password1" class="block text-900 font-medium text-xl mb-2">Password</label>
                        <Password id="password1" v-model="forms.password" placeholder="Password" :toggleMask="true"
                            class="w-full mb-3" inputClass="w-full" :inputStyle="{ padding: '1rem' }"
                            :aria-errormessage="forms.errors.password"></Password>

                        <div class="flex align-items-center justify-content-between mb-5 gap-5">
                            <div class="flex align-items-center">
                                <Checkbox v-model="forms.remember" id="rememberme1" binary class="mr-2"></Checkbox>
                                <label for="rememberme1">Ingat Saya</label>
                            </div>
                            <a class="font-medium no-underline ml-2 text-right cursor-pointer"
                                style="color: var(--primary-color)">Forgot password?</a>
                        </div>
                        <Button type="submit" label="Sign In" class="w-full p-3 text-xl"
                            :loading="forms.processing"></Button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <AppConfig simple />
</template>

<style scoped>
.pi-eye {
    transform: scale(1.6);
    margin-right: 1rem;
}

.pi-eye-slash {
    transform: scale(1.6);
    margin-right: 1rem;
}
</style>
