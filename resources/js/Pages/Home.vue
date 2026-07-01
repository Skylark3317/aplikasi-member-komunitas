<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, onMounted } from "vue";
import PillButton from "../Components/Ui/PillButton.vue";
import HomeLayout from "../Layouts/HomeLayout.vue";
import { useScrollReveal } from "@/composables/useScrollReveal";

const page = usePage();

const appUrl = computed(() => page.props.appUrl);
const storageUrl = computed(() => page.props.storageUrl);
const settings = computed(() => page.props.settings);
const posts = computed(() => page.props.posts);
const memberStats = computed(() => page.props.memberStats);

// Helper function untuk variasi transform post cards
function getPostCardTransform(index) {
  const patterns = [
    'scale(0.8) rotate(-5deg)',      // Card 1: Scale + rotate left
    'translateY(40px) scale(0.9)',   // Card 2: Slide up + scale
    'translateY(40px) scale(0.9)',   // Card 3: Slide up + scale
    'scale(0.8) rotate(5deg)',       // Card 4: Scale + rotate right
    'translateX(-40px) scale(0.9)',  // Card 5: Slide from left
    'translateY(40px)',              // Card 6: Slide up
    'translateY(40px)',              // Card 7: Slide up
    'translateX(40px) scale(0.9)',   // Card 8: Slide from right
  ];
  return patterns[index % patterns.length];
}

// Initialize scroll reveal animations with repeat enabled
useScrollReveal('.scroll-reveal', { repeat: true });
useScrollReveal('.scroll-reveal-once', { repeat: false });

// Individual staggered animations for stat cards and post cards
onMounted(() => {
  setTimeout(() => {
    // Animate each stat card individually with repeatable scroll reveal
    const statCards = document.querySelectorAll('.stat-card-individual');
    if (statCards.length > 0) {
      const statObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('revealed');
            } else {
              // Remove revealed class when element exits viewport (repeatable)
              entry.target.classList.remove('revealed');
            }
          });
        },
        {
          threshold: 0.2,
          rootMargin: '0px 0px -50px 0px'
        }
      );

      statCards.forEach((card) => {
        statObserver.observe(card);
      });
    }

    // Animate each post card individually with repeatable scroll reveal
    const postCards = document.querySelectorAll('.post-card-individual');
    if (postCards.length > 0) {
      const postObserver = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('revealed');
            } else {
              // Remove revealed class when element exits viewport (repeatable)
              entry.target.classList.remove('revealed');
            }
          });
        },
        {
          threshold: 0.15,
          rootMargin: '0px 0px -50px 0px'
        }
      );

      postCards.forEach((card) => {
        postObserver.observe(card);
      });
    }
  }, 100);
});
</script>

<template>
    <HomeLayout>
        <!-- Hero Section with entrance animations -->
        <main class="relative p-8 lg:h-136 lg:p-0 lg:px-4 lg:flex lg:justify-center lg:items-center overflow-hidden">
            <img class="absolute top-0 left-0 w-full h-full object-cover saturate-0 -z-2 animate-fade-in" :src="settings.bg_image ? `${storageUrl}/${settings.bg_image}` : `${appUrl}/images/background.jpg`" alt="Background">
            <div class="absolute top-0 left-0 w-full h-full -z-1 bg-primary opacity-80" />
            <div class="lg:w-full lg:max-w-270">
                <div class="flex flex-col gap-8 items-start lg:max-w-150">
                    <h1 class="font-medium text-3xl text-white animate-fade-in-up animate-delay-200 animate-fill-both">
                        {{ settings.hero_title || "Bangun Koneksi dan Tumbuh Bersama" }}
                    </h1>
                    <p class="text-white animate-fade-in-up animate-delay-400 animate-fill-both">
                        {{ settings.hero_description || "Terhubung dengan individu dari berbagai latar belakang, berbagi ide, dan membangun kolaborasi dalam komunitas inklusif untuk berkembang bersama serta menciptakan dampak nyata." }}
                    </p>
                    <PillButton 
                        :as="Link" 
                        :href="route('register')" 
                        class="bg-black hover-lift animate-fade-in-up animate-delay-600 animate-fill-both"
                    >
                        Gabung Membership
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                    </PillButton>
                </div>
            </div>
        </main>

        <!-- About Section with scroll reveal -->
        <section id="about" class="p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center scroll-reveal">
            <div class="flex flex-col gap-8 lg:flex-row lg:justify-between lg:items-center lg:w-full lg:max-w-270">
                <img 
                    class="w-full lg:w-80 aspect-square object-cover hover-scale" 
                    style="transition: transform 0.5s ease;"
                    :src="settings.about_image ? `${storageUrl}/${settings.about_image}` : `${appUrl}/images/about.jpg`" 
                    alt="About"
                >
                <div class="flex flex-col gap-6 lg:max-w-150">
                    <div class="flex items-center gap-2.5">
                        <div class="shrink-0 w-8 h-8 flex justify-center items-center">
                            <span class="w-6 h-0.5 bg-primary" />
                        </div>
                        <h2 class="font-medium text-2xl">Tentang</h2>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <div class="shrink-0 w-8 h-8" />
                        <p class="text-onyx-400">
                            {{ settings.about_description || "Komunitas ini adalah ruang terbuka bagi siapa saja yang ingin belajar, berkembang, dan saling terhubung dalam lingkungan yang positif dan kolaboratif. Kami menghadirkan berbagai kesempatan untuk bertukar wawasan, membangun relasi, serta berpartisipasi dalam kegiatan yang mendorong pertumbuhan pribadi maupun profesional. Dengan semangat kebersamaan, kami percaya bahwa setiap individu memiliki potensi untuk memberikan kontribusi dan menciptakan dampak yang berarti. Di sini, kamu tidak hanya menjadi bagian dari komunitas, tetapi juga bagian dari perjalanan untuk tumbuh dan berkembang bersama." }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section with animations -->
        <section class="relative p-8 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center overflow-hidden scroll-reveal">
            <img class="absolute top-0 left-0 w-full h-full object-cover saturate-0 -z-2" :src="settings.bg_image ? `${storageUrl}/${settings.bg_image}` : `${appUrl}/images/background.jpg`" alt="Background">
            <div class="absolute top-0 left-0 w-full h-full -z-1 bg-primary opacity-80" />
            <div class="lg:w-full lg:max-w-270 flex flex-col items-start lg:items-center gap-8">
                <div class="flex flex-col gap-8 items-start lg:max-w-150">
                    <h2 class="w-full font-medium text-2xl lg:text-center text-white">
                        Komunitas Kami dalam Angka
                    </h2>
                    <p class="w-full text-white lg:text-center">
                        Jadilah bagian dari komunitas yang terus berkembang dan berkolaborasi untuk mencapai dampak nyata.
                    </p>
                </div>
                <div class="w-full flex flex-col lg:flex-row gap-px">
                    <!-- Stat Card 1: Slide from Left -->
                    <div 
                        class="stat-card-individual w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur hover-lift" 
                        style="--animation-delay: 0ms; opacity: 0; transform: translateX(-50px);"
                    >
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)] animate-pulse-scale">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.aktif }}</span>
                        <h3 class="font-medium text-center">Member Aktif</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Keanggotaan aktif saat ini</p>
                    </div>
                    
                    <!-- Stat Card 2: Slide from Bottom -->
                    <div 
                        class="stat-card-individual w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur hover-lift" 
                        style="--animation-delay: 150ms; opacity: 0; transform: translateY(50px);"
                    >
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)] animate-pulse-scale">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.pasif }}</span>
                        <h3 class="font-medium text-center">Member Pasif</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Belum memperpanjang membership</p>
                    </div>
                    
                    <!-- Stat Card 3: Slide from Bottom -->
                    <div 
                        class="stat-card-individual w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur hover-lift" 
                        style="--animation-delay: 300ms; opacity: 0; transform: translateY(50px);"
                    >
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)] animate-pulse-scale">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-briefcase-icon lucide-briefcase"><path d="M16 20V4a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/><rect width="20" height="14" x="2" y="6" rx="2"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.company }}</span>
                        <h3 class="font-medium text-center">Member Company</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Perusahaan &amp; institusi</p>
                    </div>
                    
                    <!-- Stat Card 4: Slide from Right -->
                    <div 
                        class="stat-card-individual w-full bg-[rgba(255,255,255,0.2)] text-white flex flex-col gap-4 p-8 items-center backdrop-blur hover-lift" 
                        style="--animation-delay: 450ms; opacity: 0; transform: translateX(50px);"
                    >
                        <div class="p-4 rounded-lg bg-[rgba(255,255,255,0.2)] animate-pulse-scale">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <span class="font-medium text-4xl text-center">{{ memberStats.personal }}</span>
                        <h3 class="font-medium text-center">Member Personal</h3>
                        <p class="text-center text-[rgba(255,255,255,0.5)]">Individu dari berbagai bidang</p>
                    </div>
                </div>
                <PillButton class="bg-white text-primary hover-lift" :as="Link" :href="route('register')">
                    Mulai perjalananmu
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                </PillButton>
            </div>
        </section>

        <!-- Blog Section with staggered animations -->
        <section class="p-8 bg-onyx-50 lg:p-0 lg:px-4 lg:py-16 lg:flex lg:justify-center scroll-reveal">
            <div class="flex flex-col items-start gap-6 lg:w-full lg:max-w-270">
                <div class="flex items-center gap-2.5">
                    <div class="shrink-0 w-8 h-8 flex justify-center items-center">
                        <span class="w-6 h-0.5 bg-primary" />
                    </div>
                    <h2 class="font-medium text-2xl">Postingan Terbaru</h2>
                </div>
                <div v-if="posts.length > 0" class="flex flex-col gap-6 lg:grid lg:grid-cols-4">
                    <div 
                        v-for="(post, index) in posts" 
                        :key="post.id" 
                        class="post-card-individual flex flex-col gap-6 justify-between p-6 bg-white hover-lift" 
                        :style="{
                            '--animation-delay': `${index * 80}ms`,
                            opacity: 0,
                            transform: getPostCardTransform(index)
                        }"
                    >
                        <div class="flex flex-col items-start gap-6">
                            <span class="p-1 bg-primary text-sm text-white">{{ post.date }}</span>
                            <h3 class="font-medium">{{ post.title }}</h3>
                            <p class="text-onyx-400">{{ post.excerpt }}</p>
                        </div>
                        <div class="flex flex-col gap-6">
                            <span class="h-px bg-onyx-200" />
                            <Link class="flex items-center gap-3 font-medium hover:gap-4" style="transition: gap 0.3s ease;" :href="route('blog.show', post.slug)">
                                Lanjutkan membaca
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                            </Link>
                        </div>
                    </div>
                </div>
                <p v-else class="animate-fade-in">Tidak ada postingan.</p>
                <PillButton :as="Link" :href="route('blog.index')" variant="outlined" class="hover-scale">
                    Lihat semua
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-move-right-icon lucide-move-right"><path d="M18 8L22 12L18 16"/><path d="M2 12H22"/></svg>
                </PillButton>
            </div>
        </section>
    </HomeLayout>
</template>
