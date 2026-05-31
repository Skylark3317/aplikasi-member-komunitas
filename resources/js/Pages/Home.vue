<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import PillButton from "../Components/Ui/PillButton.vue";
import HomeLayout from "../Layouts/HomeLayout.vue";

const page = usePage();

const appUrl = computed(() => page.props.appUrl);
const storageUrl = computed(() => page.props.storageUrl);
const settings = computed(() => page.props.settings);
const posts = computed(() => page.props.posts);
const memberStats = computed(() => page.props.memberStats);
</script>

<template>
    <HomeLayout>
        <main class="relative p-8 lg:h-136 lg:p-0 lg:px-4 lg:flex lg:justify-center lg:items-center">
            <img class="absolute top-0 left-0 w-full h-full object-cover saturate-0 -z-2" :src="settings.background_image ? `${storageUrl}/${settings.background_image}` : `${appUrl}/images/background.jpg`" alt="Background">
            <div class="absolute top-0 left-0 w-full h-full -z-1 bg-primary opacity-80" />
            <div class="lg:w-full lg:max-w-270">
                <div class="flex flex-col gap-8 items-start lg:max-w-150">
                    <h1 class="font-medium text-3xl text-white">{{ settings.hero_title || "Bangun Koneksi dan Tumbuh Bersama" }}</h1>
                    <p class="text-white">{{ settings.hero_description || "Terhubung dengan individu dari berbagai latar belakang, berbagi ide, dan membangun kolaborasi dalam komunitas inklusif untuk berkembang bersama serta menciptakan dampak nyata." }}</p>
                    <PillButton :as="Link" :href="route('register')" class="bg-black">
                        Gabung Membership
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                    </PillButton>
                </div>
            </div>
        </main>
        <section id="about" class="p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
            <div class="flex flex-col gap-8 lg:flex-row lg:justify-between lg:items-center lg:w-full lg:max-w-270">
                <img class="w-full lg:w-80 aspect-square object-cover" :src="settings.about_image ? `${storageUrl}/${settings.about_image}` : `${appUrl}/images/about.jpg`" alt="About">
                <div class="flex flex-col gap-6 lg:max-w-150">
                    <div class="flex items-center gap-2.5">
                        <div class="shrink-0 w-8 h-8 flex justify-center items-center">
                            <span class="w-6 h-0.5 bg-primary" />
                        </div>
                        <h2 class="font-medium text-2xl">Tentang</h2>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="shrink-0 w-8 h-8" />
                        <p class="text-onyx-400">{{ settings.about_description || "Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif. Kami menghadirkan berbagai kesempatan untuk bertukar wawasan, membangun relasi, serta berpartisipasi dalam kegiatan yang mendorong pertumbuhan pribadi maupun profesional. Dengan semangat kebersamaan, kami percaya bahwa setiap individu memiliki potensi untuk memberikan kontribusi dan menciptakan dampak yang berarti. Di sini, kamu tidak hanya menjadi bagian dari komunitas, tetapi juga bagian dari perjalanan untuk tumbuh dan berkembang bersama." }}</p>
                    </div>
                </div>
            </div>
        </section>
        <section class="relative p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
            <img class="absolute top-0 left-0 w-full h-full object-cover saturate-0 -z-2" :src="settings.background_image ? `${storageUrl}/${settings.background_image}` : `${appUrl}/images/background.jpg`" alt="Background">
            <div class="absolute top-0 left-0 w-full h-full -z-1 bg-primary opacity-80" />
            <div class="lg:w-full lg:max-w-270 flex flex-col items-start lg:items-center gap-8">
                <div class="flex flex-col gap-8 items-start lg:max-w-150">
                    <h2 class="w-full font-medium text-2xl lg:text-center text-white">Komunitas Kami dalam Angka</h2>
                    <p class="w-full text-white lg:text-center">Jadilah bagian dari komunitas yang terus berkembang dan berkolaborasi untuk mencapai dampak nyata.</p>
                </div>
                <div class="w-full flex flex-col lg:flex-row gap-px">
                    <div class="w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur">
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.aktif }}</span>
                        <h3 class="font-medium text-center">Member Aktif</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Keanggotaan aktif saat ini</p>
                    </div>
                    <div class="w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur">
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.pasif }}</span>
                        <h3 class="font-medium text-center">Member Pasif</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Belum memperpanjang membership</p>
                    </div>
                    <div class="w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur">
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-icon lucide-briefcase"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.company }}</span>
                        <h3 class="font-medium text-center">Member Company</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Perusahaan &amp; institusi</p>
                    </div>
                    <div class="w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur">
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.personal }}</span>
                        <h3 class="font-medium text-center">Member Personal</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Individu dari berbagai bidang</p>
                    </div>
                </div>
                <PillButton class="bg-white text-primary" :as="Link" :href="route('register')">
                    Mulai perjalananmu
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                </PillButton>
            </div>
        </section>
        <section class="p-8 bg-onyx-50 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center">
            <div class="flex flex-col items-start gap-6 lg:w-full lg:max-w-270">
                <div class="flex items-center gap-2.5">
                    <div class="shrink-0 w-8 h-8 flex justify-center items-center">
                        <span class="w-6 h-0.5 bg-primary" />
                    </div>
                    <h2 class="font-medium text-2xl">Postingan Terbaru</h2>
                </div>
                <div v-if="posts.length > 0" class="flex flex-col gap-6 lg:grid lg:grid-cols-4">
                    <div v-for="post in posts" :key="post.id" class="flex flex-col gap-6 justify-between p-6 bg-white">
                        <div class="flex flex-col items-start gap-6">
                            <span class="p-1 bg-primary text-sm text-white">{{ post.date }}</span>
                            <h3 class="font-medium">{{ post.title }}</h3>
                            <p class="text-onyx-400">{{ post.excerpt }}</p>
                        </div>
                        <div class="flex flex-col gap-6">
                            <span class="h-px bg-onyx-200" />
                            <Link class="flex items-center gap-3 font-medium" :href="route('blog.show', post.slug)">
                                Lanjutkan membaca
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                            </Link>
                        </div>
                    </div>
                </div>
                <p v-else>Tidak ada postingan.</p>
                <PillButton :as="Link" :href="route('blog.index')" variant="outlined">
                    Lihat semua
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                </PillButton>
            </div>
        </section>
    </HomeLayout>
</template>
