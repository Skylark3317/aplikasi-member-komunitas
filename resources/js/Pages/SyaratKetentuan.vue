<script setup>
import { Head } from "@inertiajs/vue3";
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import HomeLayout from "../Layouts/HomeLayout.vue";

defineProps({
    terms_and_conditions: String,
    last_updated: String
});
</script>

<template>
    <Head title="Syarat dan Ketentuan" />
    <HomeLayout>
        <section class="p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
            <div class="lg:w-full lg:max-w-4xl bg-white rounded-[1.25rem] p-8 shadow-sm text-onyx-800">
                <h1 class="text-2xl font-bold mb-6 text-primary">Syarat dan Ketentuan</h1>
                
                <div v-if="terms_and_conditions" class="ql-editor" style="padding:0 !important; font-family: inherit; font-size: 1rem; color: inherit;" v-html="terms_and_conditions"></div>
                <div v-else class="flex flex-col gap-4 text-justify leading-relaxed">
                    <p>
                        Selamat datang di Aplikasi Member Komunitas. Dengan mendaftar dan menggunakan aplikasi ini, Anda menyatakan setuju untuk terikat dan mematuhi Syarat dan Ketentuan di bawah ini. Harap membaca dengan cermat.
                    </p>

                    <h2 class="text-xl font-semibold mt-4 text-onyx-900">1. Ketentuan Umum</h2>
                    <ul class="list-disc pl-6 flex flex-col gap-2">
                        <li>Layanan ini disediakan untuk memudahkan pengelolaan dan komunikasi antar anggota komunitas.</li>
                        <li>Setiap member diwajibkan memberikan data diri yang benar, akurat, dan dapat dipertanggungjawabkan pada saat pendaftaran.</li>
                        <li>Pihak pengelola berhak untuk menonaktifkan atau menghapus akun jika ditemukan pelanggaran atau penyalahgunaan.</li>
                    </ul>

                    <h2 class="text-xl font-semibold mt-4 text-onyx-900">2. Keanggotaan dan Akun</h2>
                    <ul class="list-disc pl-6 flex flex-col gap-2">
                        <li>Setiap individu hanya diperbolehkan memiliki satu akun aktif.</li>
                        <li>Anda bertanggung jawab penuh atas kerahasiaan kata sandi (password) dan semua aktivitas yang dilakukan melalui akun Anda.</li>
                        <li>Segera laporkan kepada admin jika Anda mencurigai adanya akses tidak sah ke akun Anda.</li>
                    </ul>

                    <h2 class="text-xl font-semibold mt-4 text-onyx-900">3. Privasi dan Data Pribadi</h2>
                    <ul class="list-disc pl-6 flex flex-col gap-2">
                        <li>Kami menghargai privasi Anda dan akan menjaga kerahasiaan data pribadi sesuai dengan kebijakan privasi yang berlaku.</li>
                        <li>Data pribadi yang dikumpulkan hanya akan digunakan untuk keperluan administratif komunitas dan tidak akan diperjualbelikan kepada pihak ketiga.</li>
                    </ul>

                    <h2 class="text-xl font-semibold mt-4 text-onyx-900">4. Perubahan Syarat dan Ketentuan</h2>
                    <p>
                        Pengelola komunitas berhak untuk memperbarui atau mengubah Syarat dan Ketentuan ini sewaktu-waktu. Perubahan akan diinformasikan kepada member melalui platform, dan kelanjutan penggunaan layanan setelah perubahan menandakan persetujuan Anda atas Syarat dan Ketentuan yang baru.
                    </p>
                </div>
                <div class="mt-8 pt-4 border-t border-gray-100">
                    <p class="font-medium text-onyx-600">
                        Terakhir diperbarui: {{ new Date(last_updated || Date.now()).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </p>
                </div>
            </div>
        </section>
    </HomeLayout>
</template>
