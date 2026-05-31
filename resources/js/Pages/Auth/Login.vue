<script setup>
import { Form, Link } from "@inertiajs/vue3";
import { ref } from "vue";
import PillButton from "../../Components/Ui/PillButton.vue";
import HomeLayout from "../../Layouts/HomeLayout.vue";

const passwordVisible = ref(false);
</script>

<template>
    <HomeLayout>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-8 lg:flex lg:justify-center bg-primary">
            <div class="lg:w-full lg:max-w-270 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-white">
                <h1 class="font-medium text-2xl">Login</h1>
            </div>
        </section>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
            <Form class="flex flex-col gap-8 lg:w-full lg:max-w-lg" :action="route('login.store')" method="post" v-slot="{ errors }">
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="email">Email</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="email" id="email" type="email" placeholder="Email">
                    <p class="text-danger-500 text-sm" v-if="errors.email">{{ errors.email }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="password">Password</label>
                    <div class="relative">
                        <input class="w-full pl-6 pr-10 py-2 ring ring-inset ring-onyx-400 rounded-full placeholder:text-onyx-400" name="password" id="password" :type="passwordVisible ? 'text' : 'password'" placeholder="Password">
                        <button type="button" class="absolute top-0 right-0 h-full aspect-square flex justify-center items-center" @click="passwordVisible = !passwordVisible">
                            <svg v-if="passwordVisible" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <p class="text-danger-500 text-sm" v-if="errors.password">{{ errors.password }}</p>
                    <Link :href="route('password.request')" class="self-end text-primary">Lupa password?</Link>
                </div>
                <PillButton class="justify-center">Login</PillButton>
                <p class="text-center">
                    Belum daftar membership?
                    <Link :href="route('register')" class="text-primary">Daftar membership</Link>
                </p>
            </Form>
        </section>
    </HomeLayout>
</template>
