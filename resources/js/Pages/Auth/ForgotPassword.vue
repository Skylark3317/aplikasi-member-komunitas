<script setup>
import { Form, usePage } from "@inertiajs/vue3";
import PillButton from "../../Components/Ui/PillButton.vue";
import HomeLayout from "../../Layouts/HomeLayout.vue";

defineProps({
  status: String,
});
</script>

<template>
    <HomeLayout>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-8 lg:flex lg:justify-center bg-primary">
            <div class="lg:w-full lg:max-w-270 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-white">
                <h1 class="font-medium text-2xl">Lupa Password</h1>
            </div>
        </section>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
            <Form class="flex flex-col gap-8 lg:w-full lg:max-w-lg" :action="route('password.email')" method="post" v-slot="{ errors }">
                <p>Masukkan email Anda untuk menerima tautan reset password.</p>
                <p v-if="status" class="text-success-600">{{ status }}</p>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="email">Email</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="email" id="email" type="email" placeholder="Email">
                    <p class="text-danger-500 text-sm" v-if="errors.email">{{ errors.email }}</p>
                </div>
                <PillButton class="self-start">Kirim email tautan reset password</PillButton>
            </Form>
        </section>
    </HomeLayout>
</template>
