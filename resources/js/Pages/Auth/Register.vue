<script setup>
import { Form, Link, usePage } from "@inertiajs/vue3";
import { ref, watch, nextTick } from "vue";
import PillButton from "../../Components/Ui/PillButton.vue";
import HomeLayout from "../../Layouts/HomeLayout.vue";
import '@vueup/vue-quill/dist/vue-quill.snow.css';

const passwordVisible = ref(false);
const passwordConfirmationVisible = ref(false);

const props = defineProps({
    terms_and_conditions: String,
    terms_last_updated: String,
});

const showTerms = ref(false);

function openTerms() {
    showTerms.value = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function closeTerms() {
    showTerms.value = false;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

const page = usePage();

watch(
    () => page.props.errors,
    (errors) => {
        if (errors && Object.keys(errors).length > 0) {
            nextTick(() => {
                // Urutan field dari atas ke bawah
                const fields = [
                    'name', 'email', 'phone', 'gender', 'blood_type', 
                    'last_education', 'institution', 'department', 
                    'address', 'password', 'password_confirmation', 'terms'
                ];
                const firstErrorField = fields.find(f => errors[f]);
                
                if (firstErrorField) {
                    let el = document.getElementById(firstErrorField) || document.getElementsByName(firstErrorField)[0];
                    if (!el && firstErrorField === 'gender') el = document.getElementById('gender_male');
                    if (!el && firstErrorField === 'blood_type') el = document.getElementById('blood_type_a');
                    
                    if (el) {
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        el.focus({ preventScroll: true });
                    }
                }
            });
        }
    },
    { deep: true, immediate: true }
);
</script>

<template>
    <HomeLayout>
        <div v-show="!showTerms">
            <section class="p-8 lg:p-0 lg:px-4 lg:py-8 lg:flex lg:justify-center bg-primary">
                <div class="lg:w-full lg:max-w-270 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 text-white">
                    <h1 class="font-medium text-2xl">Daftar Membership</h1>
                </div>
            </section>
            <section class="p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
            <Form class="flex flex-col gap-8 lg:w-full lg:max-w-lg" :action="route('register')" method="post" v-slot="{ errors }">
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="name">Nama lengkap *</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="name" id="name" placeholder="Nama lengkap">
                    <p class="text-danger-500 text-sm" v-if="errors.name">{{ errors.name }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="email">Email *</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="email" id="email" type="email" placeholder="Email">
                    <p class="text-danger-500 text-sm" v-if="errors.email">{{ errors.email }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="phone">Nomor telepon</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="phone" id="phone" type="tel" placeholder="Nomor telepon">
                    <p class="text-danger-500 text-sm" v-if="errors.phone">{{ errors.phone }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="font-medium">Jenis kelamin</span>
                    <div class="flex flex-col gap-2">
                      <label class="flex items-center gap-2" for="gender_male">
                        <input type="radio" name="gender" id="gender_male" value="Laki-laki">
                        <span>Laki-laki</span>
                      </label>
                      <label class="flex items-center gap-2" for="gender_female">
                        <input type="radio" name="gender" id="gender_female" value="Perempuan">
                        <span>Perampuan</span>
                      </label>
                      <label class="flex items-center gap-2" for="gender_none">
                        <input class="hidden" type="radio" name="gender" id="gender_none" value="" checked>
                        <PillButton as="span" variant="outlined" type="button">Batalkan pilihan</PillButton>
                      </label>
                    </div>
                    <p class="text-danger-500 text-sm" v-if="errors.gender">{{ errors.gender }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <span class="font-medium">Golongan darah</span>
                    <div class="flex flex-col gap-2">
                      <label class="flex items-center gap-2" for="blood_type_a">
                        <input type="radio" name="blood_type" id="blood_type_a" value="A">
                        <span>A</span>
                      </label>
                      <label class="flex items-center gap-2" for="blood_type_b">
                        <input type="radio" name="blood_type" id="blood_type_b" value="B">
                        <span>B</span>
                      </label>
                      <label class="flex items-center gap-2" for="blood_type_ab">
                        <input type="radio" name="blood_type" id="blood_type_ab" value="AB">
                        <span>AB</span>
                      </label>
                      <label class="flex items-center gap-2" for="blood_type_o">
                        <input type="radio" name="blood_type" id="blood_type_o" value="O">
                        <span>O</span>
                      </label>
                      <label class="flex items-center gap-2" for="blood_type_none">
                        <input class="hidden" type="radio" name="blood_type" id="blood_type_none" value="" checked>
                        <PillButton as="span" variant="outlined" type="button">Batalkan pilihan</PillButton>
                      </label>
                    </div>
                    <p class="text-danger-500 text-sm" v-if="errors.blood_type">{{ errors.blood_type }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="last_education">Pendidikan Terakhir</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="last_education" id="last_education" placeholder="Pendidikan terakhir">
                    <p class="text-danger-500 text-sm" v-if="errors.institution">{{ errors.last_education }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="institution">Institusi</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="institution" id="institution" placeholder="Institusi">
                    <p class="text-danger-500 text-sm" v-if="errors.institution">{{ errors.institution }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="department">Jurusan</label>
                    <input class="w-full px-6 py-2 rounded-full ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="department" id="department" placeholder="Jurusan">
                    <p class="text-danger-500 text-sm" v-if="errors.department">{{ errors.department }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="address">Alamat rumah</label>
                    <textarea class="w-full px-6 py-2 rounded-[1.25rem] min-h-32 ring ring-inset ring-onyx-400 placeholder:text-onyx-400" name="address" id="address" placeholder="Alamat rumah" />
                    <p class="text-danger-500 text-sm" v-if="errors.address">{{ errors.address }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="password">Password *</label>
                    <div class="relative">
                        <input class="w-full pl-6 pr-10 py-2 ring ring-inset ring-onyx-400 rounded-full placeholder:text-onyx-400" name="password" id="password" :type="passwordVisible ? 'text' : 'password'" placeholder="Password">
                        <button type="button" class="absolute top-0 right-0 h-full aspect-square flex justify-center items-center" @click="passwordVisible = !passwordVisible">
                            <svg v-if="passwordVisible" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <p class="text-danger-500 text-sm" v-if="errors.password">{{ errors.password }}</p>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="font-medium" for="password">Konfirmasi password *</label>
                    <div class="relative">
                        <input class="w-full pl-6 pr-10 py-2 ring ring-inset ring-onyx-400 rounded-full placeholder:text-onyx-400" name="password_confirmation" id="password_confirmation" :type="passwordConfirmationVisible ? 'text' : 'password'" placeholder="Konfirmasi password">
                        <button type="button" class="absolute top-0 right-0 h-full aspect-square flex justify-center items-center" @click="passwordConfirmationVisible = !passwordConfirmationVisible">
                            <svg v-if="passwordConfirmationVisible" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                            <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                    </div>
                    <p class="text-danger-500 text-sm" v-if="errors.password_confirmation">{{ errors.password_confirmation }}</p>
                </div>
                <div class="flex gap-2 text-onyx-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    Password minimal 8 karakter dengan kombinasi huruf dan angka
                </div>
                <div class="flex gap-2 text-onyx-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-alert-icon lucide-circle-alert"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                    * wajib diisi
                </div>
                <div class="flex flex-col gap-2">
                    <label class="flex items-start gap-2 cursor-pointer" for="terms">
                        <input type="checkbox" name="terms" id="terms" class="mt-1">
                        <span>
                            Saya telah membaca dan menyetujui 
                            <button type="button" @click.stop="openTerms" class="text-primary hover:underline">Syarat & Ketentuan</button> 
                            yang berlaku.
                        </span>
                    </label>
                    <p class="text-danger-500 text-sm" v-if="errors.terms">{{ errors.terms }}</p>
                </div>
                <PillButton class="justify-center">Daftar membership</PillButton>
                <p class="text-center">
                    Sudah memiliki akun?
                    <Link :href="route('login')" class="text-primary">Login</Link>
                </p>
            </Form>
            </section>
        </div>

        <div v-if="showTerms">
            <section class="p-8 lg:p-0 lg:px-4 lg:py-8 lg:flex lg:justify-center bg-primary">
                <div class="lg:w-full lg:max-w-4xl flex items-center gap-4 text-white">
                    <button type="button" @click="closeTerms" class="w-8 h-8 flex justify-center items-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors" title="Kembali">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <h1 class="font-medium text-2xl">Syarat dan Ketentuan</h1>
                </div>
            </section>
            <section class="p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
                <div class="lg:w-full lg:max-w-4xl bg-white rounded-[1.25rem] p-8 shadow-sm text-onyx-800">
                    <div v-if="terms_and_conditions" class="ql-editor" style="padding:0 !important; font-family: inherit; font-size: 1rem; color: inherit;" v-html="terms_and_conditions"></div>
                    <div v-else class="flex flex-col gap-4 text-justify leading-relaxed">
                        <p>Selamat datang di Aplikasi Member Komunitas. Dengan mendaftar dan menggunakan aplikasi ini, Anda menyatakan setuju untuk terikat dan mematuhi Syarat dan Ketentuan di bawah ini. Harap membaca dengan cermat.</p>
                        <h2 class="text-xl font-semibold mt-4 text-onyx-900">1. Ketentuan Umum</h2>
                        <ul class="list-disc pl-6 flex flex-col gap-2">
                            <li>Layanan ini disediakan untuk memudahkan pengelolaan dan komunikasi antar anggota komunitas.</li>
                            <li>Setiap member diwajibkan memberikan data diri yang benar, akurat, dan dapat dipertanggungjawabkan pada saat pendaftaran.</li>
                            <li>Pihak pengelola berhak untuk menonaktifkan atau menghapus akun jika ditemukan pelanggaran atau penyalahgunaan.</li>
                        </ul>
                    </div>
                    
                    <div class="mt-8 pt-4 border-t border-gray-100">
                        <p class="font-medium text-onyx-600">
                            Terakhir diperbarui: {{ new Date(terms_last_updated || Date.now()).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </p>
                    </div>
                </div>
            </section>
        </div>
    </HomeLayout>
</template>
